<?php
/**
 * Edit the data of an event
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

//TODO: grab_globals.php entfernen
require_once 'grab_globals.php';
require_once 'config.inc.php';
require_once 'schoorbs-includes/global.web.php';
require_once 'schoorbs-includes/global.functions.php';
require_once "schoorbs-includes/database/$dbsys.php";
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';

## Var Init ##

list($day, $month, $year) = input_DayMonthYear();
$area = input_Area();
$room = input_Room();
    
/** period **/
if(isset($_REQUEST['period']))
    $period = $_REQUEST['period'];
    
/** id **/
if(isset($_REQUEST['id']))
	$id = intval($id);
    
if(isset($_REQUEST['edit_type']))
	$edit_type = $_REQUEST['edit_type']; 

## Main ##

if (!getAuthorised(1)) {
	showAccessDenied();
}

# This page will either add or modify a booking

# We need to know:
#  Name of booker
#  Description of meeting
#  Date (option select box for day, month, year)
#  Time
#  Duration
#  Internal/External

# Firstly we need to know if this is a new booking or modifying an old one
# and if it's a modification we need to get all the old data from the db.
# If we had $id passed in then it's a modification.
if (isset($id))
{
	$sQuery = "SELECT name, create_by, description, start_time, end_time,
	        type, room_id, entry_type, repeat_id FROM $tbl_entry WHERE id = $id";
	
	$res = sql_query($sQuery);
	if (! $res) fatal_error(1, sql_error());
	if (sql_count($res) != 1) fatal_error(1, get_vocab("entryid") . $id . get_vocab("not_found"));
	
	$row = sql_row($res, 0);
	sql_free($res);
# Note: Removed stripslashes() calls from name and description. Previous
# versions of MRBS mistakenly had the backslash-escapes in the actual database
# records because of an extra addslashes going on. Fix your database and
# leave this code alone, please.
	$name        = $row[0];
	$create_by   = $row[1];
	$description = $row[2];
	$start_day   = strftime('%d', $row[3]);
	$start_month = strftime('%m', $row[3]);
	$start_year  = strftime('%Y', $row[3]);
	$start_hour  = strftime('%H', $row[3]);
	$start_min   = strftime('%M', $row[3]);
	$duration    = $row[4] - $row[3] - cross_dst($row[3], $row[4]);
	$type        = $row[5];
	$room_id     = $row[6];
	$entry_type  = $row[7];
	$rep_id      = $row[8];
	
	if($entry_type >= 1)
	{
		$sql = "SELECT rep_type, start_time, end_date, rep_opt, rep_num_weeks
		        FROM $tbl_repeat WHERE id=$rep_id";
		
		$res = sql_query($sql);
		if (! $res) fatal_error(1, sql_error());
		if (sql_count($res) != 1) fatal_error(1, get_vocab("repeat_id") . $rep_id . get_vocab("not_found"));
		
		$row = sql_row($res, 0);
		sql_free($res);
		
		$rep_type = $row[0];

		if($edit_type == "series")
		{
			$start_day   = (int)strftime('%d', $row[1]);
			$start_month = (int)strftime('%m', $row[1]);
			$start_year  = (int)strftime('%Y', $row[1]);
			
			$rep_end_day   = (int)strftime('%d', $row[2]);
			$rep_end_month = (int)strftime('%m', $row[2]);
			$rep_end_year  = (int)strftime('%Y', $row[2]);
			
			switch($rep_type)
			{
				case 2:
				case 6:
					$rep_day[0] = $row[3][0] != "0";
					$rep_day[1] = $row[3][1] != "0";
					$rep_day[2] = $row[3][2] != "0";
					$rep_day[3] = $row[3][3] != "0";
					$rep_day[4] = $row[3][4] != "0";
					$rep_day[5] = $row[3][5] != "0";
					$rep_day[6] = $row[3][6] != "0";

					if ($rep_type == 6)
					{
						$rep_num_weeks = $row[4];
					}
					
					break;
				
				default:
					$rep_day = array(0, 0, 0, 0, 0, 0, 0);
			}
		}
		else
		{
			$rep_type     = $row[0];
			$rep_end_date = utf8_strftime('%A %d %B %Y',$row[2]);
			$rep_opt      = $row[3];
		}
	}
}
else
{
	# It is a new booking. The data comes from whichever button the user clicked
	$edit_type   = "series";
	$name        = "";
	$create_by   = getUserName();
	$description = "";
	$start_day   = $day;
	$start_month = $month;
	$start_year  = $year;
    // Avoid notices for $hour and $minute if periods is enabled
    (isset($_REQUEST['hour'])) ? $start_hour = $_REQUEST['hour'] : '';
	(isset($_REQUEST['minute'])) ? $start_min = $_REQUEST['minute'] : '';
	$duration    = ($enable_periods ? 60 : 60 * 60);
	$type        = "I";
	$room_id     = $room;
    $id = -1;

	$rep_id        = 0;
	$rep_type      = 0;
	$rep_end_day   = $day;
	$rep_end_month = $month;
	$rep_end_year  = $year;
	$rep_day       = array(0, 0, 0, 0, 0, 0, 0);
}

# These next 4 if statements handle the situation where
# this page has been accessed directly and no arguments have
# been passed to it.
# If we have not been provided with starting time
if( empty( $start_hour ) && $morningstarts < 10 )
	$start_hour = "0$morningstarts";

if( empty( $start_hour ) )
	$start_hour = "$morningstarts";

if( empty( $start_min ) )
	$start_min = "00";

// Remove "Undefined variable" notice
if (!isset($rep_num_weeks))
{
    $rep_num_weeks = "";
}

$enable_periods ? toPeriodString($start_min, $duration, $dur_units) : toTimeString($duration, $dur_units);

#now that we know all the data to fill the form with we start drawing it

if(!getWritable($create_by, getUserName()))
{
	showAccessDenied();
}

print_header();

# Determine the area id of the room in question first
$sQuery = "SELECT area_id FROM $tbl_room WHERE id = $room_id";
$res = sql_query($sQuery);
$row = sql_row($res, 0);
$area_id = $row[0];
# determine if there is more than one area
$sQuery = "SELECT id FROM $tbl_area";
$res = sql_query($sQuery);
$num_areas = sql_count($res);
# if there is more than one area then give the option
# to choose areas.
$change_room_js_add = '';
$js_add1 = '';
if($num_areas > 1)
{
	# get the area id for case statement
	$sQuery = "SELECT id, area_name FROM $tbl_area ORDER BY area_name";
    $res = sql_query($sQuery);
	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++)
	{
		$change_room_js_add.= "      case \"".$row[0]."\":\n";
        # get rooms for this area
		$sql2 = "SELECT id, room_name FROM $tbl_room WHERE area_id = '".$row[0]."' ORDER BY room_name";
        $res2 = sql_query($sql2);
		if ($res2) for ($j = 0; ($row2 = sql_row($res2, $j)); $j++)
		{
                	$change_room_js_add.= "        roomsObj.options[$j] = new Option(\"".str_replace('"','\\"',$row2[1])."\",".$row2[0] .")\n";
        }
		# select the first entry by default to ensure
		# that one room is selected to begin with
		$change_room_js_add.= "        roomsObj.options[0].selected = true\n";
		$change_room_js_add.= "        break\n";
	}
	
	# get list of areas
	$sql = "SELECT id, area_name FROM $tbl_area ORDER BY area_name";
	$res = sql_query($sql);
	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++)
	{
		$selected = "";
		if ($row[0] == $area_id) {
			$selected = "selected=\\\"selected\\\"";
		}
		$js_add1.= "this.document.writeln(\"            <option $selected value=\\\"".$row[0]."\\\">".$row[1]."\")\n";
	}
}

# select the rooms in the area determined above
$sQuery = "SELECT id, room_name FROM $tbl_room WHERE area_id = $area_id ORDER BY room_name";
$res = sql_query($sQuery);
$aRooms = array();
if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++)
{
	$aRooms[] = array('id' => $row[0], 'name' => $row[1]);
	$room_names[$i] = $row[1];
}

$aTypes = array();
for ($c = "A"; $c <= "Z"; $c++)
{
	if (!empty($typel[$c]))
		$aTypes[] = array('c' => $c, 'text' => $typel[$c]);
}

$aRepTypes = array();
$aRepDays = array();
if($edit_type == "series")
{
	for($i = 0; isset($vocab["rep_type_$i"]); $i++)
	{
		$aRepTypes[] = array('text' => get_vocab("rep_type_$i"), 'id' => $i);
	}
	# Display day name checkboxes according to language and preferred weekday start.
	for ($i = 0; $i < 7; $i++)
	{
		$wday = ($i + $weekstarts) % 7;
		if ($rep_day[$wday]) 
			$checked = 'true';
		else
			$checked = 'false';
		$aRepDays[] = array('checked' => $checked, 'wday' => $wday, 'name' => day_name($wday));
	}
}
else
{
	$key = "rep_type_" . (isset($rep_type) ? $rep_type : "0");

	$smarty->assign('rep_key',$key);
	$sRepAdd = '';
	if(isset($rep_type) && ($rep_type != 0))
	{
		$opt = "";
		if ($rep_type == 2)
		{
			# Display day names according to language and preferred weekday start.
			for ($i = 0; $i < 7; $i++)
			{
				$wday = ($i + $weekstarts) % 7;
				if ($rep_opt[$wday]) $opt .= day_name($wday) . " ";
			}
		}
		if($opt)
			$sRepAdd.= "<tr><td class=\"CR\"><strong>".get_vocab("rep_rep_day")."</strong></td><td class=\"CL\">$opt</td></tr>\n";

		$sRepAdd.= "<tr><td class=\"CR\"><strong>".get_vocab("rep_end_date")."</strong></td><td class=\"CL\">$rep_end_date</td></tr>\n";
	}
	$smarty->assign('rep_add',$sRepAdd);
}

/* We display the rep_num_weeks box only if:
   - this is a new entry ($id is not set)
   Xor
   - we are editing an existing repeating entry ($rep_type is set and
     $rep_type != 0 and $edit_type == "series" )
*/
if(($id == -1) || (isset($rep_type) && ($rep_type != 0) && ('series' == $edit_type))) {
	$smarty->assign('display_rep_num_week','true');
	$smarty->assign('rep_num_weeks',$rep_num_weeks);
} else {
	$smarty->assign('display_rep_num_week','false');
}

$smarty->assign('rep_days',$aRepDays);
$smarty->assign('rep_end_day',$rep_end_day);
$smarty->assign('rep_end_month',$rep_end_month);
$smarty->assign('rep_end_year',$rep_end_year);
$smarty->assign('rep_types',$aRepTypes);
$smarty->assign('rep_type',$rep_type);
$smarty->assign('rep_id',$rep_id);
$smarty->assign('edit_type',$edit_type);
$smarty->assign('type',$type);
$smarty->assign('types',$aTypes);
$smarty->assign('change_room_js_add',$change_room_js_add);
$smarty->assign('rooms',$aRooms);
$smarty->assign('room_id',$room_id);
$smarty->assign('js_add1',$js_add1);
$smarty->assign('num_areas',$num_areas);
$smarty->assign('enable_periods',($enable_periods ? 'true' : 'false'));
$smarty->assign('twentyfourhour_format',($twentyfourhour_format ? 'true' : 'false'));
$smarty->assign('id',$id);
$smarty->assign('edit_type', $edit_type);
$smarty->assign('name',$name);
$smarty->assign('description',$description);
$smarty->assign('start_day',$start_day);
$smarty->assign('start_month',$start_month);
$smarty->assign('start_year',$start_year);
$smarty->assign('start_hour',$start_hour);
$smarty->assign('start_min',$start_min);
$smarty->assign('periods',$periods);
$smarty->assign('period',$period);
$smarty->assign('duration',$duration);
$smarty->assign('dur_units',$dur_units);
$smarty->assign('create_by',$create_by);
$smarty->display('edit_entry.tpl');

require_once 'schoorbs-includes/trailer.php';
