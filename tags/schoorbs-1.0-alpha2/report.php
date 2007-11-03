<?php
/**
 * Displays a report of the bookings for a certain time span
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, thierry_bo
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

require_once 'config.inc.php';
require_once 'schoorbs-includes/global.web.php';
require_once 'schoorbs-includes/global.functions.php';
require_once 'schoorbs-includes/report.functions.php';
require_once "schoorbs-includes/database/$dbsys.php";

## Var Init ##

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();

/** area **/
$area = input_Area();

if(isset($_REQUEST['areamatch']))
{
	# Resubmit - reapply parameters as defaults.
	# Make sure these are not escape-quoted:
	$areamatch = unslashes($_REQUEST['areamatch']);
	$roommatch = unslashes($_REQUEST['roommatch']);
	$namematch = unslashes($_REQUEST['namematch']);
	$descrmatch = unslashes($_REQUEST['descrmatch']);
    $creatormatch = unslashes($_REQUEST['creatormatch']);
    if(isset($_REQUEST['typematch']))
    	$typematch = $_REQUEST['typematch'];

	# Make default values when the form is reused.
	$areamatch_default = htmlspecialchars($areamatch);
	$roommatch_default = htmlspecialchars($roommatch);
    (isset($typematch)) ? $typematch_default = $typematch :
    	$typematch_default = "";
	$namematch_default = htmlspecialchars($namematch);
	$descrmatch_default = htmlspecialchars($descrmatch);
    $creatormatch_default = htmlspecialchars($creatormatch);


} else {
	# New report - use defaults.
	$areamatch_default = "";
	$roommatch_default = "";
	$typematch_default = array();
	$namematch_default = "";
	$descrmatch_default = "";
    $creatormatch_default = "";
	$From_day = $day;
	$From_month = $month;
	$From_year = $year;
	$To_time = mktime(0, 0, 0, $month, $day + $default_report_days, $year);
	$To_day   = date("d", $To_time);
	$To_month = date("m", $To_time);
	$To_year  = date("Y", $To_time);
}

// $summarize: 1=report only, 2=summary only, 3=both.
if(isset($_REQUEST['summarize']))
	if(!empty($_REQUEST['summarize']))
	{
		$summarize = intval($_REQUEST['summarize']);
		if($summarize > 3 || $summarize < 1)
			$summarize = 1;	
	}
	else
		$summarize = 1;
else
	$summarize = 1;

// $sumby: d=by brief description, c=by creator.
if(isset($_REQUEST['sumby']))
	if(!empty($_REQUEST['sumby']))
	{
		$sumby = $_REQUEST['sumby'];
		if($sumby != 'd' && $sumby != 'c')
			$sumby = 'd';
	}
	else
		$sumby = 'd';
else
	$sumby = 'd';
	
// $sortby: r=room, s=start date/time.
if(isset($_REQUEST['sortby']))
	if(!empty($_REQUEST['sortby']))
	{
		$sortby = $_REQUEST['sortby'];
		if($sortby != 'r' && $sortby != 's')
			$sortby = 'r';
	}
	else
		$sortby = 'r';
else
	$sortby = 'r';
	
// $display: d=duration, e=start date/time and end date/time.	
if(isset($_REQUEST['display']))
	if(!empty($_REQUEST['display']))
	{
		$display = $_REQUEST['display'];
		if($display != 'd' && $display != 'e')
			$display = 'd';
	}
	else
		$display = 'd';
else
	$display = 'd';

## Main ##

# print the page header
print_header($day, $month, $year, $area);

$smarty->assign('pview',$pview);
$smarty->assign('From_day',$From_day);
$smarty->assign('From_month',$From_month);
$smarty->assign('From_year',$From_year);
$smarty->assign('To_day',$To_day);
$smarty->assign('To_month',$To_month);
$smarty->assign('To_year',$To_year);
$smarty->assign('areamatch_default',$areamatch_default);
$smarty->assign('roommatch_default',$roommatch_default);
$smarty->assign('namematch_default',$namematch_default);
$smarty->assign('descrmatch_default',$descrmatch_default);
$smarty->assign('creatormatch_default',$creatormatch_default);
$smarty->assign('summarize',$summarize);
$smarty->assign('sortby',$sortby);
$smarty->assign('display',$display);
$smarty->assign('sumby',$sumby);
$aTypel = array();
foreach( $typel as $key => $val )
{
	if (!empty($val))
		$aTypel[] = array('key' => $key, 'val' => $val,
			 'selected' => (is_array($typematch_default) && in_array ( $key, $typematch_default ) ? "true" : "false"));
}
$smarty->assign('typel',$aTypel);
$smarty->display('report.tpl');

# Lower part: Results, if called with parameters:
if (isset($areamatch))
{
	# Make sure these are not escape-quoted:
	$areamatch = unslashes($areamatch);
	$roommatch = unslashes($roommatch);
	$namematch = unslashes($namematch);
	$descrmatch = unslashes($descrmatch);

	# Start and end times are also used to clip the times for summary info.
	$report_start = mktime(0, 0, 0, $From_month+0, $From_day+0, $From_year+0);
	$report_end = mktime(0, 0, 0, $To_month+0, $To_day+1, $To_year+0);

#   SQL result will contain the following columns:
# Col Index  Description:
#   1  [0]   Entry ID, not displayed -- used for linking to View script.
#   2  [1]   Start time as Unix time_t
#   3  [2]   End time as Unix time_t
#   4  [3]   Entry name or short description, must be HTML escaped
#   5  [4]   Entry description, must be HTML escaped
#   6  [5]   Type, single char mapped to a string
#   7  [6]   Created by (user name or IP addr), must be HTML escaped
#   8  [7]   Creation timestamp, converted to Unix time_t by the database
#   9  [8]   Area name, must be HTML escaped
#  10  [9]   Room name, must be HTML escaped

	$sql = "SELECT e.id, e.start_time, e.end_time, e.name, e.description, "
		. "e.type, e.create_by, "
		.  sql_syntax_timestamp_to_unix("e.timestamp")
		. ", a.area_name, r.room_name"
		. " FROM $tbl_entry e, $tbl_area a, $tbl_room r"
		. " WHERE e.room_id = r.id AND r.area_id = a.id"
		. " AND e.start_time < $report_end AND e.end_time > $report_start";

	if (!empty($areamatch))
		$sql .= " AND" .  sql_syntax_caseless_contains("a.area_name", $areamatch);
	if (!empty($roommatch))
		$sql .= " AND" .  sql_syntax_caseless_contains("r.room_name", $roommatch);
	if (!empty($typematch)) {
		$sql .= " AND ";
		if( count( $typematch ) > 1 )
		{
			$or_array = array();
			foreach ( $typematch as $type ){
				$or_array[] = "e.type = '$type'";
			}
			$sql .= "(". implode( " OR ", $or_array ) .")";
		}
		else
		{
			$sql .= "e.type = '".$typematch[0]."'";
		}
	}
	if (!empty($namematch))
		$sql .= " AND" .  sql_syntax_caseless_contains("e.name", $namematch);
	if (!empty($descrmatch))
		$sql .= " AND" .  sql_syntax_caseless_contains("e.description", $descrmatch);
    if (!empty($creatormatch))
        $sql .= " AND" .  sql_syntax_caseless_contains("e.create_by", $creatormatch);

	
	if( $sortby == "r" )
		# Order by Area, Room, Start date/time
		$sql .= " ORDER BY 9,10,2";
	else
		# Order by Start date/time, Area, Room
		$sql .= " ORDER BY 2,9,10";

	# echo "<p>DEBUG: SQL: <tt> $sql </tt>\n";

	$res = sql_query($sql);
	if (! $res) fatal_error(0, sql_error());
	$nmatch = sql_count($res);
	echo "<div class=\"default_class\">";
	if ($nmatch == 0)
	{
		echo "<strong>" . get_vocab("nothing_found") . "</strong><br />\n";
		sql_free($res);
	}
	else
	{
		$last_area_room = "";
		$last_date = "";
		echo "<strong>" . $nmatch . " "
		. ($nmatch == 1 ? get_vocab("entry_found") : get_vocab("entries_found"))
		.  "</strong><br />\n";

		for ($i = 0; ($row = sql_row($res, $i)); $i++)
		{
			if ($summarize & 1)
				reporton($row, $last_area_room, $last_date, $sortby, $display);

			if ($summarize & 2)
				(empty($enable_periods) ?
                                 accumulate($row, $count, $hours, $report_start, $report_end,
					$room_hash, $name_hash) :
                                 accumulate_periods($row, $count, $hours, $report_start, $report_end,
					$room_hash, $name_hash)
                                );
		}
		if ($summarize & 2)
			do_summary($count, $hours, $room_hash, $name_hash);
	}
	echo "</div>";
}

require_once 'schoorbs-includes/trailer.php';