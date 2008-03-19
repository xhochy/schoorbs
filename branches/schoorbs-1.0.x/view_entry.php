<?php
/**
 * View a single entry
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, gwalker
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The main Schoorbs configuration */
require_once "config.inc.php";
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";

## Var Init ##

#If we dont know the right date then make it up
list($day, $month, $year) = input_DayMonthYear();

$area = input_Area();

if (empty($_REQUEST['series'])) {
    $series = false;
} else {
    $series = true;
} 

if (isset($_REQUEST['id'])) {
    if (empty($_REQUEST['id'])) {
	        fatal_error(true, "A room id must be secified"); 
	} else {
	        $id = intval($_REQUEST['id']);
    }
} else {
	    fatal_error(true, "A room id must be secified");
}
	
## Main ##

print_header();

if($series){
	$sQuery = "SELECT ${tbl_repeat}.name, ${tbl_repeat}.description, ${tbl_repeat}.create_by,"
	    ."${tbl_room}.room_name, ${tbl_area}.area_name, ${tbl_repeat}.type, ${tbl_repeat}.room_id,"
        .sql_syntax_timestamp_to_unix("${tbl_repeat}.timestamp").
        ", (${tbl_repeat}.end_time - ${tbl_repeat}.start_time), ${tbl_repeat}.start_time,"
        ."${tbl_repeat}.end_time, ${tbl_repeat}.rep_type, ${tbl_repeat}.end_date,"
        ."${tbl_repeat}.rep_opt, ${tbl_repeat}.rep_num_weeks FROM  ${tbl_repeat}, ${tbl_room},"
        ."${tbl_area} WHERE ${tbl_repeat}.room_id = ${tbl_room}.id AND "
        ."${tbl_room}.area_id = ${tbl_area}.id AND ${tbl_repeat}.id = ".$id; 
        // $id doesn't need to be escaped since we've done a intval() on it 
}
else {
	$sQuery = "SELECT ${tbl_entry}.name, ${tbl_entry}.description, ${tbl_entry}.create_by,"
        ."${tbl_room}.room_name, ${tbl_area}.area_name, ${tbl_entry}.type, ${tbl_entry}.room_id,"
        .sql_syntax_timestamp_to_unix("${tbl_entry}.timestamp")
        .", (${tbl_entry}.end_time - ${tbl_entry}.start_time), ${tbl_entry}.start_time,"
        ."${tbl_entry}.end_time, ${tbl_entry}.repeat_id FROM  ${tbl_entry}, ${tbl_room},"
        ."${tbl_area} WHERE ${tbl_entry}.room_id = ${tbl_room}.id AND "
        ."${tbl_room}.area_id = ${tbl_area}.id AND ${tbl_entry}.id = ".$id;
        // $id doesn't need to be escaped since we've done a intval() on it
}

$res = sql_query($sQuery);
if (!$res) fatal_error(false, sql_error());

if(sql_count($res) < 1) {
	fatal_error(
        false,
		($series ? get_vocab("invalid_series_id") : get_vocab("invalid_entry_id"))
	);
}

$row = sql_row($res, 0);
sql_free($res);

# Note: Removed stripslashes() calls from name and description. Previous
# versions of MRBS mistakenly had the backslash-escapes in the actual database
# records because of an extra addslashes going on. Fix your database and
# leave this code alone, please.
$name         = ht($row[0]);
$description  = ht($row[1]);
$create_by    = ht($row[2]);
$room_name    = ht($row[3]);
$area_name    = ht($row[4]);
$type         = $row[5];
$room_id      = $row[6];
$updated      = time_date_string($row[7]);
# need to make DST correct in opposite direction to entry creation
# so that user see what he expects to see
$duration     = $row[8] - cross_dst($row[9], $row[10]);

// Collect data for microformat
$mfStartDateRaw = $row[9];
$mfEndDateRaw = $row[10];
$mfUpdatedRaw = $row[7];

if( $enable_periods )
	list( $start_period, $start_date) =  period_date_string($row[9]);
else
        $start_date = time_date_string($row[9]);

if( $enable_periods )
	list( , $end_date) =  period_date_string($row[10], -1);
else
        $end_date = time_date_string($row[10]);


$rep_type = 0;

if ($series) {
	$rep_type     = $row[11];
	$rep_end_date = utf8_strftime('%A %d %B %Y',$row[12]);
	$rep_opt      = $row[13];
	$rep_num_weeks = $row[14];
	# I also need to set $id to the value of a single entry as it is a
	# single entry from a series that is used by del_entry.php and
	# edit_entry.php
	# So I will look for the first entry in the series where the entry is
	# as per the original series settings
	$sQuery = "SELECT id FROM ${tbl_entry} WHERE repeat_id = \"${id}\" AND entry_type= \"1\" "
        ."ORDER BY start_time  LIMIT 1";
	$res = sql_query($sQuery);
	if (!$res) fatal_error(0, sql_error());
	if(sql_count($res) < 1) {
		# if all entries in series have been modified then
		# as a fallback position just select the first entry
		# in the series
		# hopefully this code will never be reached as
		# this page will display the start time of the series
		# but edit_entry.php will display the start time of the entry
		sql_free($res);
		$sql = "SELECT id FROM ${tbl_entry}	WHERE repeat_id=\"${id}\" ORDER BY start_time "
            ."LIMIT 1";
		$res = sql_query($sql);
		if (!$res) fatal_error(0, sql_error());
	}
	$row = sql_row($res, 0);
	$id = $row[0];
	sql_free($res);
} else {
	$repeat_id = $row[11];

	if ($repeat_id != 0) {
		$res = sql_query("SELECT rep_type, end_date, rep_opt, rep_num_weeks FROM ${tbl_repeat} "
            ."WHERE id = ${repeat_id}");
		if (!$res) fatal_error(0, sql_error());

		if (sql_count($res) == 1) {
			$row = sql_row($res, 0);

			$rep_type     = $row[0];
			$rep_end_date = utf8_strftime('%A %d %B %Y',$row[1]);
			$rep_opt      = $row[2];
			$rep_num_weeks = $row[3];
		}
		sql_free($res);
	}
}


$enable_periods ? toPeriodString($start_period, $duration, $dur_units) : toTimeString($duration, $dur_units);

$repeat_key = "rep_type_".$rep_type;

$sRepeatAppend = "";
if ($rep_type != 0) {
	$opt = "";
	if (($rep_type == 2) || ($rep_type == 6)) {
		# Display day names according to language and preferred weekday start.
		for ($i = 0; $i < 7; $i++) {
			$daynum = ($i + $weekstarts) % 7;
			if ($rep_opt[$daynum]) $opt .= day_name($daynum) . " ";
		}
	}
	if ($rep_type == 6) {
		$sRepeatAppend.= "<tr><td><strong>".get_vocab("rep_num_weeks").get_vocab("rep_for_nweekly")."</strong></td><td>${rep_num_weeks}</td></tr>\n";
	}
	
	if($opt) {
		$sRepeatAppend.= "<tr><td><strong>".get_vocab("rep_rep_day")."</strong></td><td>${opt}</td></tr>\n";
    }
	
	$sRepeatAppend.= "<tr><td><strong>".get_vocab("rep_end_date")."</strong></td><td>${rep_end_date}</td></tr>\n";
}

$sRepeatAppend2 = "";
if(!$series )
	$sRepeatAppend2.= "<a href=\"edit_entry.php?id=${id}\">". get_vocab("editentry") ."</a>";

if($repeat_id)
	$sRepeatAppend2.= " - ";

if($repeat_id || $series )
	$sRepeatAppend2.= "<a href=\"".ht("edit_entry.php?id=${id}&edit_type=series&day=${day}&month=${month}&year=${year}")."\">".get_vocab("editseries")."</a>";



$sRepeatAppend3 = "";
if(!$series) {
	$sRepeatAppend3.= "<a href=\"".ht("del_entry.php?id=${id}&series=0")."\" onclick=\"return confirm('"
        .get_vocab("confirmdel")."');\">".get_vocab("deleteentry")."</a>";
}

if($repeat_id)
	$sRepeatAppend3.= " - ";

if($repeat_id || $series ) {
	$sRepeatAppend3.= "<a href=\"".ht("del_entry.php?id=${id}&series=1&day=${day}&month=${month}&year=${year}")
        ."\" onclick=\"return confirm('".get_vocab("confirmdel")."');\">".get_vocab("deleteseries")."</a>";
}

// Generate data for the micorformat
if ($enable_periods) {
    $mfStartDate = strftime("%Y-%m-%d", $mfStartDateRaw);
    $mfEndDate = strftime("%Y-%m-%d", $mfEndDateRaw);
} else {
    $mfStartDate = strftime("%Y-%m-%dT%H:%M:00", $mfStartDateRaw);
    $mfEndDate = strftime("%Y-%m-%dT%H:%M:00", $mfEndDateRaw);
}
$mfUpdated = strftime("%Y-%m-%dT%H:%M:00", $mfUpdatedRaw);

# Now that we know all the data we start drawing it
$smarty->assign(array(
    'name' => $name,
    'description' => $description,
    'area_name' => $area_name,
    'room_name' => $room_name,
    'start_date' => $start_date,
    'duration' => $duration,
    'dur_units' => $dur_units,
    'end_date' => $end_date,
    'typelabel' => (empty($typel[$type]) ? "?$type?" : $typel[$type]),
    'create_by' => $create_by,
    'updated' => $updated,
    'repeat_key' => get_vocab($repeat_key),
    'repeatAppend' => $sRepeatAppend,
    'repeatAppend2' => $sRepeatAppend2,
    'repeatAppend3' => $sRepeatAppend3,
    'mfStartDate' => $mfStartDate,
    'mfEndDate' => $mfEndDate,
    'mfUpdated' => $mfUpdated
));
$smarty->display('view_entry.tpl');

/** The footer of the HTML Page */
require_once 'schoorbs-includes/trailer.php';
