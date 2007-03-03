<?php
/**
 * The view of one day
 * 
 * @author gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 */

## Includes ##

require_once 'config.inc.php';
require_once 'functions.php';
require_once "db/$dbsys.php";
require_once 'auth/schoorbs_auth.php';
require_once 'mincals.php';

## Var Init ##

list($day, $month, $year) = input_DayMonthYear();
$area = input_Area();

## Main ##

# print the page header
print_header($day, $month, $year, $area);

$format = "Gi";
if( $enable_periods ) {
	$format = "i";
	$resolution = 60;
	$morningstarts = 12;
	$morningstarts_minutes = 0;
	$eveningends = 12;
	$eveningends_minutes = count($periods)-1;
}

# ensure that $morningstarts_minutes defaults to zero if not set
if( empty( $morningstarts_minutes ) )
	$morningstarts_minutes=0;

# Define the start and end of each day in a way which is not affected by
# daylight saving...
# dst_change:
# -1 => no change
#  0 => entering DST
#  1 => leaving DST
$dst_change = is_dst($month,$day,$year);
$am7=mktime($morningstarts,$morningstarts_minutes,0,$month,$day,$year,is_dst($month,$day,$year,$morningstarts));
$pm7=mktime($eveningends,$eveningends_minutes,0,$month,$day,$year,is_dst($month,$day,$year,$eveningends));

if($pview != 1)
{
    # need to show either a select box or a normal html list,
    # depending on the settings in config.inc.php
    if ($area_list_format == "select")
	    $smarty->assign('area_select_list',make_area_select_html('day.php', $area, $year, $month, $day)); # from functions.inc
    else
    {
    	# show the standard html list
	    $sQuery = "SELECT id, area_name FROM $tbl_area ORDER BY area_name";
   	    $res = sql_query($sQuery);
   	    $rows = array();
   	    if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++)
   	    {
   	        $rows[] = array('id' => $row[0], 'area_name' => $row[1]);
   	    }
        $smarty->assign('areas',$rows);
    }
   
    $smarty->assign('area',$area);
    $smarty->assign('day',$day);
    $smarty->assign('year',$year);
    $smarty->assign('month',$month);
    $smarty->assign('dwm','day.php');
    $smarty->assign('area_list_format',$area_list_format);
    $smarty->display('area_list.tpl');
    
    #Draw the three month calendars
    minicals($year, $month, $day, $area, '', 'day');
    echo "</tr></table>\n";
}

#y? are year, month and day of yesterday
#t? are year, month and day of tomorrow

$i= mktime(12,0,0,$month,$day-1,$year);
$yy = date("Y",$i);
$ym = date("m",$i);
$yd = date("d",$i);

$i= mktime(12,0,0,$month,$day+1,$year);
$ty = date("Y",$i);
$tm = date("m",$i);
$td = date("d",$i);

#We want to build an array containing all the data we want to show
#and then spit it out. 

#Get all appointments for today in the area that we care about
#Note: The predicate clause 'start_time <= ...' is an equivalent but simpler
#form of the original which had 3 BETWEEN parts. It selects all entries which
#occur on or cross the current day.
$sql = "SELECT $tbl_room.id, start_time, end_time, name, $tbl_entry.id, type,
        $tbl_entry.description, $tbl_entry.create_by
   FROM $tbl_entry, $tbl_room WHERE $tbl_entry.room_id = $tbl_room.id
   AND area_id = ".sql_escape_arg($area)." AND start_time <= $pm7 AND end_time > $am7";

$res = sql_query($sql);
if (! $res) fatal_error(0, sql_error());
for ($i = 0; ($row = sql_row($res, $i)); $i++) {
	# Each row weve got here is an appointment.
	#Row[0] = Room ID
	#row[1] = start time
	#row[2] = end time
	#row[3] = short description
	#row[4] = id of this booking
	#row[5] = type (internal/external)
	#row[6] = description
	#row[7] = creator

	# $today is a map of the screen that will be displayed
	# It looks like:
	#     $today[Room ID][Time][id]
	#                          [color]
	#                          [data]
	#                          [long_descr]

	# Fill in the map for this meeting. Start at the meeting start time,
	# or the day start time, whichever is later. End one slot before the
	# meeting end time (since the next slot is for meetings which start then),
	# or at the last slot in the day, whichever is earlier.
	# Time is of the format HHMM without leading zeros.
	#
	# Note: int casts on database rows for max may be needed for PHP3.
	# Adjust the starting and ending times so that bookings which don't
	# start or end at a recognized time still appear.
	$start_t = max(round_t_down($row[1], $resolution, $am7), $am7);
	$end_t = min(round_t_up($row[2], $resolution, $am7) - $resolution, $pm7);
	for ($t = $start_t; $t <= $end_t; $t += $resolution)
	{
		$today[$row[0]][date($format,$t)]["id"]    = $row[4];
		$today[$row[0]][date($format,$t)]["color"] = $row[5];
		$today[$row[0]][date($format,$t)]["data"]  = "";
		$today[$row[0]][date($format,$t)]["long_descr"]  = "";
		$today[$row[0]][date($format,$t)]['create_by'] = $row[7];
	}

	# Show the name of the booker in the first segment that the booking
	# happens in, or at the start of the day if it started before today.
	if ($row[1] < $am7)
	{
		$today[$row[0]][date($format,$am7)]["data"] = $row[3];
		$today[$row[0]][date($format,$am7)]["long_descr"] = $row[6];
	}
	else
	{
		$today[$row[0]][date($format,$start_t)]["data"] = $row[3];
		$today[$row[0]][date($format,$start_t)]["long_descr"] = $row[6];
	}
}

# We need to know what all the rooms area called, so we can show them all
# pull the data from the db and store it. Convienently we can print the room
# headings and capacities at the same time

$sQuery = "SELECT room_name, capacity, id, description FROM $tbl_room WHERE area_id = "
	.sql_escape_arg($area)." ORDER BY 1";

$res = sql_query($sQuery);

# It might be that there are no rooms defined for this area.
# If there are none then show an error and dont bother doing anything
# else
if (! $res) fatal_error(0, sql_error());
if (sql_count($res) == 0)
{
	echo "<h1>".get_vocab("no_rooms_for_area")."</h1>";
	sql_free($res);
}
else
{
	$smarty->assign('am7', utf8_strftime("%A %d %B %Y", $am7));
	$smarty->assign('pview',$pview);
	$smarty->assign('yy',$yy);
	$smarty->assign('ym',$ym);
	$smarty->assign('yd',$yd);
	$smarty->assign('ty',$ty);
	$smarty->assign('tm',$tm);
	$smarty->assign('td',$td);
	$smarty->assign('year',$year);
	$smarty->assign('day',$day);
	$smarty->assign('month',$month);
	if($javascript_cursor)
		$smarty->assign('javascript_cursor','true');
	else
		$smarty->assign('javascript_cursor','false');
	if($show_plus_link)
		$smarty->assign('show_plus_link','true');
	else
		$smarty->assign('show_plus_link','false');
	if($times_right_side)
		$smarty->assign('times_right_side','true');
	else
		$smarty->assign('times_right_side','false');
	if($enable_periods)
		$smarty->assign('enable_periods','true');
	else
		$smarty->assign('enable_periods','false');
	$smarty->assign('highlight_method',$highlight_method);
	$smarty->assign('area',$area);
	$smarty->assign('period_title',$enable_periods ? get_vocab("period") : get_vocab("time"));
	
	$room_column_width = (int)(95 / sql_count($res));
	$aRooms = array();
	for ($i = 0; ($row = sql_row($res, $i)); $i++)
	{
	    $rooms[] = $row[2];
		$aRooms[] = array('title' => $row[0], 'capacity' => $row[1], 'id' => $row[2], 'description' => $row[3]);
	}
	$smarty->assign('room_column_width',$room_column_width);
	$smarty->assign('rooms',$aRooms);
	
	# URL for highlighting a time. Don't use REQUEST_URI or you will get
	# the timetohighlight parameter duplicated each time you click.
	$hilite_url="day.php?year=$year&amp;month=$month&amp;day=$day&amp;area=$area&amp;timetohighlight";
	$smarty->assign('hilite_url',$hilite_url);
	
	# This is the main bit of the display
	# We loop through time and then the rooms we just got

	# if the today is a day which includes a DST change then use
	# the day after to generate timesteps through the day as this
	# will ensure a constant time step
	( $dst_change != -1 ) ? $j = 1 : $j = 0;
	
	$times = array();
	$row_class = "even_row";
	for (
		$t = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day+$j, $year);
		$t <= mktime($eveningends, $eveningends_minutes, 0, $month, $day+$j, $year);
		$t += $resolution, $row_class = ($row_class == "even_row")?"odd_row":"even_row"
	)
	{
		# convert timestamps to HHMM format without leading zeros
		$time_t = date($format, $t);
		
		$cols = array();
		// Loop through the list of rooms we have for this area
		foreach($roms as $key=>$room)
		{
			// Array used to temporarly store the vars that will be sent to Smarty
			$aLoop = array();
			
			if(isset($today[$room][$time_t]['id']))
			{
				$aLoop['id'] = $today[$room][$time_t]["id"];
				$color = $today[$room][$time_t]["color"];
				$aLoop['descr'] = htmlspecialchars($today[$room][$time_t]["data"]);
				$aLoop['long_descr'] = htmlspecialchars($today[$room][$time_t]["long_descr"]);
				$aLoop['create_by'] = htmlspecialchars($today[$room][$time_t]["create_by"]);
			}
			
			# $c is the colour of the cell that the browser sees. White normally,
			# red if were hightlighting that line and a nice attractive green if the room is booked.
			# We tell if its booked by $id having something in it
			if (isset($aLoop['id']))
				$aLoop['css_class'] = $color;
			elseif (isset($timetohighlight) && ($time_t == $timetohighlight))
				$aLoop['css_class'] = "red";
			else
				$aLoop['css_class'] = $row_class; # Use the default color class for the row.
			
			# If the room isnt booked then allow it to be booked
			if(!isset($aLoop['id']))
			{
				$hour = date("H",$t);
				$minute  = date("i",$t);

				if($pview != 1) 
				{
					if( $enable_periods ) 
					{
						$time_t_stripped = preg_replace( "/^0/", "", $time_t ); 
						$aLoop['period_param'] = "&amp;period=$time_t_stripped";
					}
					else
						$aLoop['period_param'] = "&amp;hour=$hour&amp;minute=$minute";
				}
			}
			$aLoop['room'] = $room;
			$cols[] = $aLoop;
		}
      	
      	$time = array('time' => $time_t, 'cols' => $cols);
		if($enable_periods)
			$time['title'] = $periods[preg_replace( "/^0/", "", $time_t)];
		else
			$time['title'] = utf8_strftime(hour_min_format(),$t);
		$times[] = $time;
	}
	
	$smarty->assign('times',$times);
	$smarty->display('day.tpl');
	
    show_colour_key();
}

require_once 'trailer.php';
?>
