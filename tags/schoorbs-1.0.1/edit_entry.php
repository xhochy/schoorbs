<?php
/**
 * This page will either add or modify a booking
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
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
/** Database helper functions */
require_once 'schoorbs-includes/database/schoorbs_sql.php';


## Var Init ##

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();
/** area */
$area = input_Area();
/** room */
$room = input_Room();
/** id **/
if(isset($_REQUEST['id'])) $id = intval($_REQUEST['id']);

if (isset($_REQUEST['edit_type'])) {
	$edit_type = trim(strtolower($_REQUEST['edit_type']));
} else {
	$edit_type = '';
}


## Main ##

if (!getAuthorised(1)) showAccessDenied();

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
if (isset($id)) {
	$sQuery = sprintf(
		'SELECT name, create_by, description, start_time, end_time, type, '
		.'room_id, entry_type, repeat_id FROM %s WHERE id = %d',
		$tbl_entry, $id
	);
	$res = sql_query($sQuery);
	if (!$res) fatal_error(true, sql_error());
	if (sql_count($res) != 1) {
		fatal_error(true, get_vocab('entryid').$id.get_vocab('not_found'));
	}
	
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
	
	if ($entry_type >= 1) {
		$res = sql_query(sprintf(
			'SELECT rep_type, start_time, end_date, rep_opt, rep_num_weeks '
			.'FROM %s WHERE id = %d',
			$tbl_repeat, $rep_id
		));
		if (!$res) fatal_error(1, sql_error());
		if (sql_count($res) != 1) {
			fatal_error(true, get_vocab('repeat_id').$rep_id
				.get_vocab('not_found'));
		}
		
		$row = sql_row($res, 0);
		sql_free($res);
		
		$rep_type = $row[0];

		if ($edit_type == 'series') {
			$start_day     = intval(strftime('%d', $row[1]));
			$start_month   = intval(strftime('%m', $row[1]));
			$start_year    = intval(strftime('%Y', $row[1]));
			$rep_end_day   = intval(strftime('%d', $row[2]));
			$rep_end_month = intval(strftime('%m', $row[2]));
			$rep_end_year  = intval(strftime('%Y', $row[2]));
			
			switch($rep_type) {
			case 2:
			case 6:
				$rep_day[0] = $row[3][0] != '0';
				$rep_day[1] = $row[3][1] != '0';
				$rep_day[2] = $row[3][2] != '0';
				$rep_day[3] = $row[3][3] != '0';
				$rep_day[4] = $row[3][4] != '0';
				$rep_day[5] = $row[3][5] != '0';
				$rep_day[6] = $row[3][6] != '0';

				if ($rep_type == 6) {
					$rep_num_weeks = $row[4];
				}				
				break;
			default: $rep_day = array(0, 0, 0, 0, 0, 0, 0);
			}
		} else {
			$rep_type     = $row[0];
			$rep_end_date = utf8_strftime('%A %d %B %Y',$row[2]);
			$rep_opt      = $row[3];
		}
	}
} else {
	# It is a new booking. The data comes from whichever button the user clicked
	$edit_type   = 'series';
	$name        = '';
	$create_by   = getUserName();
	$description = '';
	$start_day   = $day;
	$start_month = $month;
	$start_year  = $year;
    // Avoid notices for $hour and $minute if periods is enabled
    if (isset($_REQUEST['hour'])) $start_hour = intval($_REQUEST['hour']);
    elseif ($enable_periods) $start_hour = 12;
	if (isset($_REQUEST['minute'])) $start_min = intval($_REQUEST['minute']);
	elseif (isset($_REQUEST['period']) && $enable_periods) { 
		$start_min = intval($_REQUEST['period']);
	}
	$duration    = ($enable_periods ? 60 : 60 * 60);
	$type        = 'I';
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
if (empty($start_hour) && ($morningstarts < 10)) {
	$start_hour = "0$morningstarts";
}
if (empty($start_hour)) $start_hour = "$morningstarts";
if (empty($start_min)) $start_min = '00';
if (!isset($rep_num_weeks)) {
    $rep_num_weeks = '';
}

if ($enable_periods) {
	toPeriodString($start_min, $duration, $dur_units);
} else {
	toTimeString($duration, $dur_units);
}

#now that we know all the data to fill the form with we start drawing it
if (!getWritable($create_by, getUserName())) showAccessDenied();

print_header();

# Determine the area id of the room in question first
$area_id = mrbsGetRoomArea($room_id);
# determine if there is more than one area
$sQuery = 'SELECT id FROM '.$tbl_area;
$res = sql_query($sQuery);
$num_areas = sql_count($res);
# if there is more than one area then give the option
# to choose areas.
$change_room_js_add = ''; $js_add1 = '';
if ($num_areas > 1) {
	# get the area id for case statement
	$sQuery = "SELECT id, area_name FROM $tbl_area ORDER BY area_name";
    $res = sql_query($sQuery);
	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++) {
		$change_room_js_add.= '      case "'.$row[0]."\":\n";
        # get rooms for this area
        $res2 = sql_query(sprintf(
        	'SELECT id, room_name FROM %s WHERE area_id = %d ORDER BY room_name',
        	$tbl_room, $row[0]
        ));
		if ($res2) for ($j = 0; ($row2 = sql_row($res2, $j)); $j++) {
			$change_room_js_add.= "        roomsObj.options[$j] = new Option(\"".str_replace('"','\\"',$row2[1]).'",'.$row2[0] .")\n";
        }
		# select the first entry by default to ensure
		# that one room is selected to begin with
		$change_room_js_add.= "        roomsObj.options[0].selected = true\n";
		$change_room_js_add.= "        break\n";
	}
	
	# get list of areas
	$sql = "SELECT id, area_name FROM $tbl_area ORDER BY area_name";
	$res = sql_query($sql);
	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++) {
		$selected = '';
		if ($row[0] == $area_id) {
			$selected = "selected=\\\"selected\\\"";
		}
		$js_add1.= "this.document.writeln(\"            <option $selected value=\\\"".$row[0]."\\\">".$row[1]."\")\n";
	}
}

# select the rooms in the area determined above
$res = sql_query(sprintf(
	'SELECT id, room_name FROM %s WHERE area_id = %d ORDER BY room_name',
	$tbl_room, $area_id
));
$aRooms = array();
if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++) {
	$aRooms[] = array('id' => $row[0], 'name' => $row[1]);
}

$aTypes = array();
for ($c = 'A'; $c <= 'Z'; $c++) {
	if (isset($typel[$c]) && (!empty($typel[$c]))) {
		$aTypes[] = array('c' => $c, 'text' => $typel[$c]);
	}
}

$aRepTypes = array(); $aRepDays = array();
if ($edit_type == 'series') {
	for ($i = 0; isset($vocab["rep_type_$i"]); $i++) {
		$aRepTypes[] = array('text' => get_vocab("rep_type_$i"), 'id' => $i);
	}
	# Display day name checkboxes according to language and preferred weekday start.
	for ($i = 0; $i < 7; $i++) {
		$wday = ($i + $weekstarts) % 7;
		if ($rep_day[$wday]) {
			$checked = 'true';
		} else {
			$checked = 'false';
		}
		$aRepDays[] = array('checked' => $checked, 'wday' => $wday, 'name' => day_name($wday));
	}
} else {
	$key = 'rep_type_'.(isset($rep_type) ? $rep_type : "0");

	$smarty->assign('rep_key',$key);
	$sRepAdd = '';
	if(isset($rep_type) && ($rep_type != 0)) {
		$opt = "";
		if ($rep_type == 2) {
			# Display day names according to language and preferred weekday start.
			for ($i = 0; $i < 7; $i++) {
				$wday = ($i + $weekstarts) % 7;
				if ($rep_opt[$wday]) $opt .= day_name($wday) . " ";
			}
		}
		if($opt) $sRepAdd.= '<tr><td class="CR"><strong>'.get_vocab('rep_rep_day')."</strong></td><td class=\"CL\">$opt</td></tr>\n";

		$sRepAdd.= '<tr><td class="CR"><strong>'.get_vocab('rep_end_date')."</strong></td><td class=\"CL\">$rep_end_date</td></tr>\n";
	}
	$smarty->assign('rep_add', $sRepAdd);
}

/* We display the rep_num_weeks box only if:
   - this is a new entry ($id is not set)
   Xor
   - we are editing an existing repeating entry ($rep_type is set and
     $rep_type != 0 and $edit_type == "series" )
*/
if(($id == -1) || (isset($rep_type) && ($rep_type != 0) && ('series' == $edit_type))) {
	$smarty->assign('display_rep_num_week', 'true');
	$smarty->assign('rep_num_weeks', $rep_num_weeks);
} else {
	$smarty->assign('display_rep_num_week','false');
}

$smarty->assign(array(
	'rep_days' => $aRepDays,
	'rep_end_day' => $rep_end_day,
	'rep_end_month' => $rep_end_month,
	'rep_end_year' => $rep_end_year,
	'rep_types' => $aRepTypes,
	'rep_type' => $rep_type,
	'rep_id' => $rep_id,
	'edit_type' => $edit_type,
	'type' => $type,
	'types' => $aTypes,
	'change_room_js_add' => $change_room_js_add,
	'rooms' => $aRooms,
	'room_id' => $room_id,
	'js_add1'  => $js_add1,
	'num_areas' => $num_areas,
	'enable_periods' => ($enable_periods ? 'true' : 'false'),
	'twentyfourhour_format' => ($twentyfourhour_format ? 'true' : 'false'),
	'id' => $id,
	'edit_type' => $edit_type,
	'name' => $name,
	'description' => $description,
	'start_day' => $start_day,
	'start_month' => $start_month,
	'start_year' => $start_year,
	'start_hour' => $start_hour,
	'start_min' => $start_min,
	'periods' => $periods,
	'duration' => $duration,
	'dur_units' => $dur_units,
	'create_by' => $create_by
));

$smarty->display('edit_entry.tpl');

require_once 'schoorbs-includes/trailer.php';
