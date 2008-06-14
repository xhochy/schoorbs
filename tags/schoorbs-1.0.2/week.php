<?php
/**
 * The view of one week
 * 
 * @author gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The Schoorbs configuration */
require_once "config.inc.php";
/** The global include file for webscripts */
require_once 'schoorbs-includes/global.web.php';
/** The common global include file */
require_once 'schoorbs-includes/global.functions.php';
/** The database functions */
require_once "schoorbs-includes/database/$dbsys.php";
/** The authentication functions */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** The mini calendars */
require_once 'schoorbs-includes/minicals.php';

## Var Init ##

list($day, $month, $year) = input_DayMonthYear();
$area = input_Area();
$room = input_Room();

## Main ##

print_header();

$num_of_days = 7; #could also pass this in as a parameter or whatever

# Set the date back to the previous $weekstarts day (Sunday, if 0):
$time = mktime(12, 0, 0, $month, $day, $year);
if (($weekday = (date("w", $time) - $weekstarts + 7) % 7) > 0) {
	$time -= $weekday * 86400;
	$day   = date("d", $time);
	$month = date("m", $time);
	$year  = date("Y", $time);
}

// y? are year, month and day of yesterday
list($yd, $ym, $yy) = getLastWeek($day, $month, $year);
// t? are year, month and day of tomorrow
list($td, $tm, $ty) = getNextWeek($day, $month, $year);

# Define the start and end of each day of the week in a way which is not
# affected by daylight saving...
for ($j = 0; $j<=($num_of_days-1); $j++) {
	# are we entering or leaving daylight saving
	# dst_change:
	# -1 => no change
	#  0 => entering DST
	#  1 => leaving DST
	$dst_change[$j] = is_dst($month,$day+$j,$year);
	$am7[$j] = mktime($morningstarts,$morningstarts_minutes,0,$month,$day+$j,$year,is_dst($month,$day+$j,$year,$morningstarts));
	$pm7[$j] = mktime($eveningends,$eveningends_minutes,0,$month,$day+$j,$year,is_dst($month,$day+$j,$year,$eveningends));
}

if($pview != 1)
{
    # need to show either a select box or a normal html list,
    # depending on the settings in config.inc.php
    if ($area_list_format == "select") {
        $smarty->assign('area_select_list',make_area_select_html('week.php', $area, $year, $month, $day)); # from functions.inc
        $this_area_name = sql_query1("SELECT area_name FROM $tbl_area WHERE id = $area");
        $this_room_name = sql_query1("SELECT room_name FROM $tbl_room WHERE id = $room");
    } else {
    	# show the standard html list
	$sQuery = "SELECT id, area_name FROM $tbl_area ORDER BY area_name";
   	$res = sql_query($sQuery);
   	$rows = array();
   	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++) {
   		if ($row[0] == $area) {
				$this_area_name = htmlspecialchars($row[1]);
		}	
   	        $rows[] = array('id' => $row[0], 'area_name' => $row[1]);
   	}
        $smarty->assign('areas',$rows);
    }
   
    $smarty->assign(array(
       'area' => $area, 'day' => $day,
       'month' => $month, 'year' => $year,
       'dwm' => 'week.php',
       'area_list_format' => $area_list_format
    ));
    $smarty->display('area_list.tpl');
    
    // Show the list of rooms
    if ($area_list_format == "select") {
        $smarty->assign('room_list_select',make_room_select_html('week.php', $area, $room, $year, $month, $day));
    } else {
        $sQuery = "SELECT id, room_name, description FROM $tbl_room WHERE area_id = $area ORDER BY room_name";
        $res = sql_query($sQuery);
        $aRooms = array();
		
        if($res) for ($i = 0; ($row = sql_row($res, $i)); $i++) {
            $aRooms[] = array('id' => $row[0], 'name' => $row[1], 'description' => $row[2]);
	    if($row[0] == $room) {
                $this_room_name = $row[1]; 
            }
        }
        $smarty->assign('rooms',$aRooms);
    }
    $smarty->assign('room', $room);
    $smarty->display('room_list.tpl');
    
    #Draw the three month calendars
    minicals($year, $month, $day, $area, '', 'day');
    puts('</tr></table>');
}

# Don't continue if this area has no rooms:
if ($room <= 0) {
	puts('<h1>'.get_vocab('no_rooms_for_area').'</h1>');
        /** The page footer */
	require_once 'schoorbs-includes/trailer.php';
	exit();
}

#Get all appointments for this week in the room that we care about
# row[0] = Start time
# row[1] = End time
# row[2] = Entry type
# row[3] = Entry name (brief description)
# row[4] = Entry ID
# row[5] = Complete description
# row[6] = Creator
# This data will be retrieved day-by-day
for ($j = 0; $j<=($num_of_days-1) ; $j++) {
    $sQuery = "SELECT start_time, end_time, type, name, id, description, create_by"
        ." FROM $tbl_entry WHERE room_id = $room"
	." AND start_time <= $pm7[$j] AND end_time > $am7[$j]";

    # Each row returned from the query is a meeting. Build an array of the
    # form:  d[weekday][slot][x], where x = id, color, data, long_desc.
    # [slot] is based at 000 (HHMM) for midnight, but only slots within
    # the hours of interest (morningstarts : eveningends) are filled in.
    # [id], [data] and [long_desc] are only filled in when the meeting
    # should be labeled,  which is once for each meeting on each weekday.
    # Note: weekday here is relative to the $weekstarts configuration variable.
    # If 0, then weekday=0 means Sunday. If 1, weekday=0 means Monday.

    $res = sql_query($sQuery);
    if (!$res) {
        echo sql_error();
    } else for ($i = 0; ($row = sql_row($res, $i)); $i++) {
		
	 	# $d is a map of the screen that will be displayed
 		# It looks like:
 		#     $d[Day][Time][id]
 		#                  [color]
 		#                  [data]
 		# where Day is in the range 0 to $num_of_days. 
 	
 		# Fill in the map for this meeting. Start at the meeting start time,
 		# or the day start time, whichever is later. End one slot before the
 		# meeting end time (since the next slot is for meetings which start then),
 		# or at the last slot in the day, whichever is earlier.
 		# Note: int casts on database rows for max may be needed for PHP3.
 		# Adjust the starting and ending times so that bookings which don't
 		# start or end at a recognized time still appear.
 
		$start_t = max(round_t_down($row[0], $resolution, $am7[$j]), $am7[$j]);
 		$end_t = min(round_t_up($row[1], $resolution, $am7[$j]) - $resolution, $pm7[$j]);

 		for ($t = $start_t; $t <= $end_t; $t += $resolution) {
			$d[$j][date($format,$t)]["id"]    = $row[4];
 			$d[$j][date($format,$t)]["color"] = $row[2];
 			$d[$j][date($format,$t)]["data"]  = "";
 			$d[$j][date($format,$t)]["long_descr"]  = "";
 			$d[$j][date($format,$t)]["create_by"] = $row[6];
 		}
 
 		# Show the name of the booker in the first segment that the booking
 		# happens in, or at the start of the day if it started before today.
 		if ($row[1] < $am7[$j]) {
 			$d[$j][date($format,$am7[$j])]["data"] = $row[3];
 			$d[$j][date($format,$am7[$j])]["long_descr"] = $row[5];
		} else {
 			$d[$j][date($format,$start_t)]["data"] = $row[3];
 			$d[$j][date($format,$start_t)]["long_descr"] = $row[5];
		}
	}
} 

$aDays = array();
$dformat = "%a, %b %d";
for ($j = 0; $j <= ($num_of_days - 1); $j++) {
	$t = mktime( 12, 0, 0, $month, $day+$j, $year); 
	$aDays[] = array('year' => strftime("%Y", $t), 'month' => strftime("%m", $t), 'day' => strftime("%d", $t),
    	'text' => utf8_strftime($dformat, $t));
}


# This is the main bit of the display. Outer loop is for the time slots,
# inner loop is for days of the week.

# if the first day of the week to be displayed contains as DST change then
# move to the next day to get the hours in the day.
( $dst_change[0] != -1 ) ? $j = 1 : $j = 0;

$aTimes = array();

$row_class = "even_row";
for (
	$t = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day+$j, $year);
	$t <= mktime($eveningends, $eveningends_minutes, 0, $month, $day+$j, $year);
	$t += $resolution, $row_class = ($row_class == "even_row")?"odd_row":"even_row"
) {
	# use hour:minute format
	$time_t = date($format, $t);

	$empty_color = "white";

	$aWeekDays = array();
	# See note above: weekday==0 is day $weekstarts, not necessarily Sunday.
	for ($thisday = 0; $thisday<=($num_of_days-1) ; $thisday++) {
		# Three cases:
		# color:  id:   Slot is:   Color:    Link to:
		# -----   ----- --------   --------- -----------------------
		# unset   -     empty      white,red add new entry
		# set     unset used       by type   none (unlabelled slot)
		# set     set   used       by type   view entry

		$wt = mktime( 12, 0, 0, $month, $day+$thisday, $year );
		$wday = date("d", $wt);
		$wmonth = date("m", $wt);
		$wyear = date("Y", $wt);

 		if(isset($d[$thisday][$time_t]["id"]))
 		{
 			$id  = $d[$thisday][$time_t]["id"];
 			$color = $d[$thisday][$time_t]["color"];
 			$descr = htmlspecialchars($d[$thisday][$time_t]["data"]);
 			$long_descr = htmlspecialchars($d[$thisday][$time_t]["long_descr"]);
 			$create_by = htmlspecialchars($d[$thisday][$time_t]["create_by"]);
 		} else {
 			unset($id);
		}
 		
 		# $c is the colour of the cell that the browser sees. White normally, 
 		# red if were hightlighting that line and a nice attractive green if the room is booked.
 		# We tell if its booked by $id having something in it
 		if (isset($id))
 			$c = $color;
 		else
 			$c = $row_class;
 			
 		$aWeekDays[] = array('color' => $c, 'id' => $id, 'description' => $descr, 'time_t_stripped' => preg_replace( "/^0/", "", $time_t ),
 			'wyear' => $wyear, 'wmonth' => $wmonth, 'wday' => $wday, 'hour' => date("H",$t), 'minute' => $minute,
 			'long_descr' => $long_descr, 'create_by' => $create_by);
 	
	}

	if($enable_periods) {
                $sTitle = $periods[preg_replace( "/^0/", "", $time_t)];
        } else {
                $sTitle = utf8_strftime(hour_min_format(), $t);
        }
	$aTimes[] = array('time_t' => $time_t, 'WeekDays' => $aWeekDays,
		'time' => $sTitle);
	
}

$smarty->assign(array(
    'this_area_name' => $this_area_name,
    'this_room_name' => $this_room_name,
    'pview' => $pview, 
    'times' => $aTimes,
    'yy' => $yy, 'ym' => $ym, 'yd' => $yd,
    'ty' => $ty, 'tm' => $tm, 'td' => $td,
    'area' => $area, 'room' => $room,
    'javascript_cursor' => ($javascript_cursor ? 'true' : 'false'),
    'show_plus_link' => ($show_plus_link ? 'true' : 'false'),
    'times_right_side' => ($times_right_side ? 'true' : 'false'),
    'highlight_method' => $highlight_method,
    'enable_periods' => ($enable_periods ? 'true' : 'false'),
    'days' => $aDays
));
$smarty->display('week.tpl');

show_colour_key();

/** The page footer */
require_once 'schoorbs-includes/trailer.php';
