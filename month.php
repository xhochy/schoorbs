<?php
/**
 * The view of one month
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
/** The authetication wrappers */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** The 3 minicalendars */
require_once 'schoorbs-includes/minicals.php';

## Var Init ##

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();
$day = 1;

/** area **/
$area = input_Area();
    
/** room **/
$room = input_Room();

## Main ##

# print the page header
print_header();

# Month view start time. This ignores morningstarts/eveningends because it
# doesn't make sense to not show all entries for the day, and it messes
# things up when entries cross midnight.
$month_start = mktime(0, 0, 0, $month, 1, $year);

# What column the month starts in: 0 means $weekstarts weekday.
$weekday_start = (date("w", $month_start) - $weekstarts + 7) % 7;

$days_in_month = date("t", $month_start);

$month_end = mktime(23, 59, 59, $month, $days_in_month, $year);

if( $enable_periods ) {
	$resolution = 60;
	$morningstarts = 12;
	$eveningends = 12;
	$eveningends_minutes = count($periods)-1;
}


# Define the start and end of each day of the month in a way which is not
# affected by daylight saving...
for ($j = 1; $j<=$days_in_month; $j++) {
	# are we entering or leaving daylight saving
	# dst_change:
	# -1 => no change
	#  0 => entering DST
	#  1 => leaving DST
	$dst_change[$j] = is_dst($month,$j,$year);
        if(empty( $enable_periods )){
		$midnight[$j]=mktime(0,0,0,$month,$j,$year, is_dst($month,$j,$year, 0));
		$midnight_tonight[$j]=mktime(23,59,59,$month,$j,$year, is_dst($month,$j,$year, 23));
	}
        else {
		$midnight[$j]=mktime(12,0,0,$month,$j,$year, is_dst($month,$j,$year, 0));
		$midnight_tonight[$j]=mktime(12,count($periods),59,$month,$j,$year, is_dst($month,$j,$year, 23));
        }
}

if($pview != 1)
{
    # need to show either a select box or a normal html list,
    # depending on the settings in config.inc.php
    if ($area_list_format == "select") 
    {
	    $smarty->assign('area_select_list',make_area_select_html('month.php', $area, $year, $month, $day)); # from functions.inc
	    $this_area_name = sql_query1("SELECT area_name FROM $tbl_area WHERE id = $area");
		$this_room_name = sql_query1("SELECT room_name FROM $tbl_room WHERE id = $room");
    }
    else
    {
    	# show the standard html list
	    $sQuery = "SELECT id, area_name FROM $tbl_area ORDER BY area_name";
   	    $res = sql_query($sQuery);
   	    $rows = array();
   	    if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++)
   	    {
   	    	if ($row[0] == $area)
			{
				$this_area_name = htmlspecialchars($row[1]);
			}	
   	        $rows[] = array('id' => $row[0], 'area_name' => $row[1]);
   	    }
        $smarty->assign('areas',$rows);
    }
   
    $smarty->assign('area',$area);
    $smarty->assign('day',$day);
    $smarty->assign('year',$year);
    $smarty->assign('month',$month);
    $smarty->assign('dwm','month.php');
    $smarty->assign('area_list_format',$area_list_format);
    $smarty->display('area_list.tpl');
    
    // Show the list of rooms
    if ($area_list_format == "select") 
		$smarty->assign('room_list_select',make_room_select_html('month.php', $area, $room, $year, $month, $day));
   	else 
   	{
   		$sQuery = "SELECT id, room_name, description FROM $tbl_room WHERE area_id = $area ORDER BY room_name";
		$res = sql_query($sQuery);
		$aRooms = array();
		
		if($res) 
			for ($i = 0; ($row = sql_row($res, $i)); $i++)
			{
				$aRooms[] = array('id' => $row[0], 'name' => $row[1], 'description' => $row[2]);
				if($row[0] == $room)
					$this_room_name = $row[1]; 
			}
				
		$smarty->assign('rooms',$aRooms);
   	}
   	$smarty->assign('room',$room);
    $smarty->display('room_list.tpl');
    
    #Draw the three month calendars
    minicals($year, $month, $day, $area, '', 'day');
    echo "</tr></table>\n";
}

# Don't continue if this area has no rooms:
if ($room <= 0)
{
    echo "<h1>".get_vocab("no_rooms_for_area")."</h1>";
    require_once 'schoorbs-includes/trailer.php';
    exit;
}

# Show Go to month before and after links
#y? are year and month of the previous month.
#t? are year and month of the next month.

$i= mktime(12,0,0,$month-1,1,$year);
$yy = date("Y",$i);
$ym = date("n",$i);

$i= mktime(12,0,0,$month+1,1,$year);
$ty = date("Y",$i);
$tm = date("n",$i);

# Used below: localized "all day" text but with non-breaking spaces:
$all_day = ereg_replace(" ", "&nbsp;", get_vocab("all_day"));

#Get all meetings for this month in the room that we care about
# row[0] = Start time
# row[1] = End time
# row[2] = Entry ID
# This data will be retrieved day-by-day fo the whole month
for ($day_num = 1; $day_num<=$days_in_month; $day_num++) {
	$sql = "SELECT start_time, end_time, id, name
	   FROM $tbl_entry
	   WHERE room_id=$room
	   AND start_time <= $midnight_tonight[$day_num] AND end_time > $midnight[$day_num]
	   ORDER by 1";

	# Build an array of information about each day in the month.
	# The information is stored as:
	#  d[monthday]["id"][] = ID of each entry, for linking.
	#  d[monthday]["data"][] = "start-stop" times or "name" of each entry.

	$res = sql_query($sql);
	if (! $res) echo sql_error();
	else for ($i = 0; ($row = sql_row($res, $i)); $i++)
	{
	        $d[$day_num]["id"][] = $row[2];
            $d[$day_num]["shortdescrip"][] = $row[3];

            # Describe the start and end time, accounting for "all day"
            # and for entries starting before/ending after today.
            # There are 9 cases, for start time < = or > midnight this morning,
            # and end time < = or > midnight tonight.
            # Use ~ (not -) to separate the start and stop times, because MSIE
            # will incorrectly line break after a -.

            if(empty( $enable_periods ) ){
		        switch (cmp3($row[0], $midnight[$day_num]) . cmp3($row[1], $midnight_tonight[$day_num] + 1))
		        {
		    	case "> < ":         # Starts after midnight, ends before midnight
		    	case "= < ":         # Starts at midnight, ends before midnight
		                $d[$day_num]["data"][] = utf8_strftime(hour_min_format(), $row[0]) . "~" . utf8_strftime(hour_min_format(), $row[1]);
		                break;
		    	case "> = ":         # Starts after midnight, ends at midnight
		                $d[$day_num]["data"][] = utf8_strftime(hour_min_format(), $row[0]) . "~24:00";
		                break;
		    	case "> > ":         # Starts after midnight, continues tomorrow
		                $d[$day_num]["data"][] = utf8_strftime(hour_min_format(), $row[0]) . "~====>";
		                break;
		    	case "= = ":         # Starts at midnight, ends at midnight
		                $d[$day_num]["data"][] = $all_day;
		                break;
		    	case "= > ":         # Starts at midnight, continues tomorrow
		                $d[$day_num]["data"][] = $all_day . "====>";
		                break;
		    	case "< < ":         # Starts before today, ends before midnight
		                $d[$day_num]["data"][] = "<====~" . utf8_strftime(hour_min_format(), $row[1]);
		                break;
		    	case "< = ":         # Starts before today, ends at midnight
		                $d[$day_num]["data"][] = "<====" . $all_day;
		                break;
		    	case "< > ":         # Starts before today, continues tomorrow
		                $d[$day_num]["data"][] = "<====" . $all_day . "====>";
		                break;
		        }
	    	}
            else
            {
	            $start_str = ereg_replace(" ", "&nbsp;", period_time_string($row[0]));
	            $end_str   = ereg_replace(" ", "&nbsp;", period_time_string($row[1], -1));
	            switch (cmp3($row[0], $midnight[$day_num]) . cmp3($row[1], $midnight_tonight[$day_num] + 1))
	            {
	        	case "> < ":         # Starts after midnight, ends before midnight
	        	case "= < ":         # Starts at midnight, ends before midnight
	                    $d[$day_num]["data"][] = $start_str . "~" . $end_str;
	                    break;
	        	case "> = ":         # Starts after midnight, ends at midnight
	                    $d[$day_num]["data"][] = $start_str . "~24:00";
	                    break;
	        	case "> > ":         # Starts after midnight, continues tomorrow
	                    $d[$day_num]["data"][] = $start_str . "~====>";
	                    break;
	        	case "= = ":         # Starts at midnight, ends at midnight
	                    $d[$day_num]["data"][] = $all_day;
	                    break;
	        	case "= > ":         # Starts at midnight, continues tomorrow
	                    $d[$day_num]["data"][] = $all_day . "====>";
	                    break;
	        	case "< < ":         # Starts before today, ends before midnight
	                    $d[$day_num]["data"][] = "<====~" . $end_str;
	                    break;
	        	case "< = ":         # Starts before today, ends at midnight
	                    $d[$day_num]["data"][] = "<====" . $all_day;
	                    break;
	        	case "< > ":         # Starts before today, continues tomorrow
	                    $d[$day_num]["data"][] = "<====" . $all_day . "====>";
	                    break;
	            }
            }


	}
}

$aDaynames = array();
# Weekday name header row:
for ($weekcol = 0; $weekcol < 7; $weekcol++)
	$aDaynames[] = day_name(($weekcol + $weekstarts)%7);
$aSkipdays = array();
# Skip days in week before start of month:
for ($weekcol = 0; $weekcol < $weekday_start; $weekcol++)
	$aSkipdays[] = '';

# Draw the days of the month:
$aDays = array();
for ($cday = 1; $cday <= $days_in_month; $cday++)
{
    if ($weekcol == 0) 
    	$bBreakLine = 'true';
    else 
    	$bBreakLine = 'false';
    
    # Anything to display for this day?
    if (isset($d[$cday]["id"][0]))
    {
        $n = count($d[$cday]["id"]);
        # Show the start/stop times, 2 per line, linked to view_entry.
        # If there are 12 or fewer, show them, else show 11 and "...".
        for ($i = 0; $i < $n; $i++)
        {
            if ( ($i == 11 && $n > 12 && $monthly_view_entries_details != "both") or
                 ($i == 6 && $n > 6 && $monthly_view_entries_details == "both") )
            {
                $sOut = " ...\n";
                break;
            }
            if ( ($i > 0 && $i % 2 == 0) or
                ($monthly_view_entries_details == "both"  && $i > 0) )
            {
                $sOut = "<br />";
            }
            else
            {
                $sOut = " ";
            }
            switch ($monthly_view_entries_details)
            {
                case "description":
                {
                    $sOut = "<a href=\"view_entry.php?id=" . $d[$cday]["id"][$i]
                        . "&amp;day=$cday&amp;month=$month&amp;year=$year\" title=\""
                        . htmlspecialchars($d[$cday]["data"][$i]) . "\">"
                        . htmlspecialchars(substr($d[$cday]["shortdescrip"][$i], 0, 17))
                        . "</a>";
                    break;
                }
                case "slot":
                {
                    $sOut = "<a href=\"view_entry.php?id=" . $d[$cday]["id"][$i]
                        . "&amp;day=$cday&amp;month=$month&amp;year=$year\" title=\""
                        . htmlspecialchars(substr($d[$cday]["shortdescrip"][$i], 0, 17)) . "\">"
                        . htmlspecialchars($d[$cday]["data"][$i]) . "</a>";
                    break;
                }
                case "both":
                {
                    $sOut = "<a href=\"view_entry.php?id=" . $d[$cday]["id"][$i]
                        . "&amp;day=$cday&amp;month=$month&amp;year=$year\">"
                        . htmlspecialchars($d[$cday]["data"][$i]) . " "
                        . htmlspecialchars(substr($d[$cday]["shortdescrip"][$i], 0, 6)) . "</a>";
                    break;
                }
                default:
                {
                    $sOut = "error: unknown parameter";
                }
            }
        }
        $defined = 'true';
    }
    else
    {
    	$defined = 'false';
    	$sOut = '';
    }
    
    $aDays[] = array('breakline' => $bBreakLine, 'cday' => $cday, 'defined' => $defined, 'out' => $sOut);
    
    if (++$weekcol == 7) $weekcol = 0;
}

$aSkipdays2 = array();
# Skip days in week before start of month:
if ($weekcol > 0) for (; $weekcol < 7; $weekcol++)
	$aSkipdays2[] = '';


$smarty->assign('this_area_name', $this_area_name);
$smarty->assign('this_room_name', $this_room_name);
$smarty->assign('this_time_name', utf8_strftime("%B %Y", $month_start));
$smarty->assign('yy',$yy);
$smarty->assign('ym',$ym);
$smarty->assign('ty',$ty);
$smarty->assign('tm',$tm);
$smarty->assign('room',$room);
$smarty->assign('area',$area);
$smarty->assign('pview', $pview);
$smarty->assign('year',$year);
$smarty->assign('month',$month);
$smarty->assign('morningstarts',$morningstarts);
$smarty->assign('javascript_cursor',($javascript_cursor ? 'true' : 'false'));
$smarty->assign('show_plus_link',($show_plus_link ? 'true' : 'false'));
$smarty->assign('times_right_side',($times_right_side ? 'true' : 'false'));
$smarty->assign('highlight_method',$highlight_method);
$smarty->assign('enable_periods',($enable_periods ? 'true' : 'false'));
$smarty->assign('daynames', $aDaynames);
$smarty->assign('skipdays', $aSkipdays);
$smarty->assign('skipdays2', $aSkipdays2);
$smarty->assign('days',$aDays);
$smarty->display('month.tpl');

require_once 'schoorbs-includes/trailer.php';
