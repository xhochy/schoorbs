<?php
/**
 * The view of one day
 * 
 * @author gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** The 3 minicalendars */
require_once 'schoorbs-includes/minicals.php';

## Var Init ##

list($day, $month, $year) = input_DayMonthYear();
$area = input_Area();

$dst_change = is_dst($month,$day,$year);
$am7 = am7($day, $month, $year);
$pm7 = pm7($day, $month, $year);

#y? are year, month and day of yesterday
list($yd, $ym, $yy) = getYesterday($day, $month, $year);
#t? are year, month and day of tomorrow
list($td, $tm, $ty) = getTomorrow($day, $month, $year);

## Main ##

# print the page header
print_header($day, $month, $year, $area);

if ($pview != 1) {
    # need to show either a select box or a normal html list,
    # depending on the settings in config.inc.php
    if ($area_list_format == 'select') {
    	$smarty->assign('area_select_list', 
    		make_area_select_html('day.php', $area, $year, $month, $day));
    } else {
    	$smarty->assign('areas', getAreas()); // show the standard html list
    }
   
    $smarty->assign(array(
    	'area' => $area, 'dwm' => 'day.php',
    	'day' => $day, 'year' => $year, 'month' => $month,
    	'area_list_format' => $area_list_format
    ));
    $smarty->display('area_list.tpl');
    
    # Draw the three month calendars
    minicals($year, $month, $day, $area, '', 'day');
    puts('</tr></table>');
}

# We want to build an array containing all the data we want to show
# and then spit it out. 

# Get all appointments for today in the area that we care about
# Note: The predicate clause 'start_time <= ...' is an equivalent but simpler
# form of the original which had 3 BETWEEN parts. It selects all entries which
# occur on or cross the current day.
$sQuery = "SELECT $tbl_room.id, start_time, end_time, name, $tbl_entry.id, type,
        $tbl_entry.description, $tbl_entry.create_by
   FROM $tbl_entry, $tbl_room WHERE $tbl_entry.room_id = $tbl_room.id
   AND area_id = ".sql_escape_arg($area)
   ." AND start_time <= $pm7 AND end_time > $am7";

$res = sql_query($sQuery);
if (!$res) fatal_error(0, sql_error());
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
	for ($t = $start_t; $t <= $end_t; $t += $resolution) {
		$today[$row[0]][date($format,$t)]["id"]    = $row[4];
		$today[$row[0]][date($format,$t)]["color"] = $row[5];
		$today[$row[0]][date($format,$t)]["data"]  = "";
		$today[$row[0]][date($format,$t)]["long_descr"]  = "";
		$today[$row[0]][date($format,$t)]['create_by'] = $row[7];
	}

	# Show the name of the booker in the first segment that the booking
	# happens in, or at the start of the day if it started before today.
	if ($row[1] < $am7) {
		$today[$row[0]][date($format,$am7)]["data"] = $row[3];
		$today[$row[0]][date($format,$am7)]["long_descr"] = $row[6];
	} else {
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
if (sql_count($res) == 0) {
	echo "<h1>".get_vocab("no_rooms_for_area")."</h1>";
	sql_free($res);
} else {
	$room_column_width = (int)(95 / sql_count($res));
	
	$smarty->assign(array(
		'am7' => utf8_strftime("%A %d %B %Y", $am7),
		'pview' => $pview, 'area' => $area,
		'yy' => $yy, 'ym' => $ym, 'yd' => $yd,
		'ty' => $ty, 'tm' => $tm, 'td' => $td,
		'year' => $year, 'day' => $day, 'month' => $month,
		'javascript_cursor' => ($javascript_cursor ? 'true' : 'false'),
		'show_plus_link' => ($show_plus_link ? 'true' : 'false'),
		'times_right_side' => ($times_right_side ? 'true' : 'false'),
		'enable_periods' => ($enable_periods ? 'true' : 'false'),
		'highlight_method' => $highlight_method,
		'period_title' => ($enable_periods ? get_vocab("period") : get_vocab("time")),
		'room_column_width' => $room_column_width
	));
	
	
	$aRooms = array();
	for ($i = 0; ($row = sql_row($res, $i)); $i++) {
	    $rooms[] = $row[2];
	    $aRooms[] = array(
	    	'title' => $row[0], 'capacity' => $row[1], 
	    	'id' => $row[2], 'description' => $row[3]
	    );
	}
	$smarty->assign('rooms', $aRooms);
	
	# URL for highlighting a time. Don't use REQUEST_URI or you will get
	# the timetohighlight parameter duplicated each time you click.
	$hilite_url = ht("day.php?year=$year&month=$month&day=$day"
		."&area=$area&timetohighlight");
	$smarty->assign('hilite_url', $hilite_url);
	
	# This is the main bit of the display
	# We loop through time and then the rooms we just got

	# if the today is a day which includes a DST change then use
	# the day after to generate timesteps through the day as this
	# will ensure a constant time step
	$j = (($dst_change != -1) ? 1 : 0);
	
	$times = array();
	$row_class = "even_row";
	for (
		$t = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day+$j, $year);
		$t <= mktime($eveningends, $eveningends_minutes, 0, $month, $day+$j, $year);
		$t += $resolution, $row_class = ($row_class == "even_row")?"odd_row":"even_row"
	) {
		# convert timestamps to HHMM format without leading zeros
		$time_t = date($format, $t);
		
		$cols = array();
		// Loop through the list of rooms we have for this area
		foreach($rooms as $key => $room) {
			// Array used to temporarly store the vars that will be sent to Smarty
			$aLoop = array();
			
			if(isset($today[$room][$time_t]['id'])) {
				$aLoop['id'] = $today[$room][$time_t]["id"];
				$color = $today[$room][$time_t]["color"];
				$aLoop['descr'] = ht($today[$room][$time_t]["data"]);
				$aLoop['long_descr'] = ht($today[$room][$time_t]["long_descr"]);
				$aLoop['create_by'] = ht($today[$room][$time_t]["create_by"]);
			}
			
			# $c is the colour of the cell that the browser sees. White normally,
			# red if were hightlighting that line and a nice attractive green if the room is booked.
			# We tell if its booked by $id having something in it
			if (isset($aLoop['id'])) {
				$aLoop['css_class'] = $color;
			} elseif (isset($timetohighlight) && ($time_t == $timetohighlight)) {
				$aLoop['css_class'] = "red";
			} else {
				$aLoop['css_class'] = $row_class; # Use the default color class for the row.
			}
			
			# If the room isnt booked then allow it to be booked
			if (!isset($aLoop['id'])) {
				$hour = date("H",$t);
				$minute  = date("i",$t);
				
				if ($pview != 1) {
					if ($enable_periods) {
						$time_t_stripped = preg_replace( "/^0/", "", $time_t ); 
						$aLoop['period_param'] = ht("&period=$time_t_stripped");
					} else {
						$aLoop['period_param'] = ht("&hour=$hour&minute=$minute");
					}
				}
			}
			$aLoop['room'] = $room;
			$cols[] = $aLoop;
		}
      	
		if($enable_periods) {
			$sTitle = $periods[preg_replace( "/^0/", "", $time_t)];
		} else {
			$sTitle = utf8_strftime(hour_min_format(), $t);
		}
		$times[] = array('time' => $time_t, 'cols' => $cols, 'title' => $sTitle);
	}
	
	$smarty->assign('times', $times);
	$smarty->display('day.tpl');
	show_colour_key();
}

/** The footer of the HTML Page */
require_once 'schoorbs-includes/trailer.php';
