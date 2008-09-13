<?php
/**
 * Handles an edit of an entry
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/// Includes ///

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
/** Helper function for this page */
require_once 'schoorbs-includes/edit_entry_handler.functions.php';
/** The logging wrapper */
require_once 'schoorbs-includes/logging.functions.php';

/// Input ///

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear('edit_');

//var_dump($month);die();
/** rep_end_ + (day, month, year) */
list($rep_end_day, $rep_end_month, $rep_end_year) = input_DayMonthYear('rep_end_');
// if really not set, unset them again
if (!isset($_REQUEST['rep_end_day'])) unset($rep_end_day);
if (!isset($_REQUEST['rep_end_month'])) unset($rep_end_month);
if (!isset($_REQUEST['rep_end_year'])) unset($rep_end_year);
/** duration & Co. */
list($duration, $dur_units, $units) = input_Duration();
/** name */
$name = input_Name();
/** description */
$description = input_Description();
/** all_day */
$all_day = input_All_Day();

if (isset($_REQUEST['type'])) {
	$type = trim(strtoupper($_REQUEST['type']));
	if (empty($type)) $type = 'I';
} else {
	$type = 'I';
}

if (isset($_REQUEST['id'])) {
	$id = intval($_REQUEST['id']);
} else {
	$id = -1;
}

if ($enable_periods) {
	if (isset($_REQUEST['period'])) {
		$period = intval($_REQUEST['period']);
	} else {
		$period = 0;
	}

	$hour = 12;
	$minute = $period;
	$max_periods = count($periods);
} else {
	if (isset($_REQUEST['hour'])) {
		$hour = intval($_REQUEST['hour']);
	} else {
		$hour = 12;
	}
	
	if (isset($_REQUEST['minute'])) {
		$minute = intval($_REQUEST['minute']);
	} else {
		$minute = 0;
	}
}

if (isset($_REQUEST['ampm'])) {
	$ampm = strtolower($_REQUEST['ampm']);
}

if (isset($_REQUEST['reptype'])) {
	$rep_type = intval($_REQUEST['reptype']);
	if ($rep_type < 0) $rep_type = 0;
	if ($rep_type > 5) {
		fatal_error(true, 'Internal error: reptype of >5 not supported');
	}
} else {
	$rep_type = 0;
}

if (isset($_REQUEST['rep_day']) && is_array($_REQUEST['rep_day'])) {
	$rep_day = $_REQUEST['rep_day'];
} else {
	$rep_day = array();
}

if (isset($_REQUEST['rep_num_weeks'])) {
	$rep_num_weeks = intval($_REQUEST['rep_num_weeks']);
	if ($rep_num_weeks < 0) $rep_num_weeks = 0;
}

if (isset($_REQUEST['rooms']) && is_array($_REQUEST['rooms'])) {
    $rooms = $_REQUEST['rooms'];
} else {
    fatal_error(true, 'No room selected');
}

if (isset($_REQUEST['edit_type'])) {
	$edit_type = trim(strtolower($_REQUEST['edit_type']));
} else {
	$edit_type = '';
}

if (isset($_REQUEST['returl'])) {
	$returl = unslashes($_REQUEST['returl']);
} else {
	$returl = 'index.php';
}

## Main ##

if ($id != -1) {
	$sQuery = sprintf(
		'SELECT create_by FROM %s WHERE id = %d', 
		$tbl_entry, $id
	);
	$create_by = sql_query1($sQuery);
} else {
	$create_by = getUserName();
}

if(!getAuthorised(1) || !getWritable($create_by, getUserName())) showAccessDenied();

if ($all_day == 'yes') {
    list($starttime, $endtime) = allDayStartEndTime();
} else {
	if (!$twentyfourhour_format) {
		if (isset($ampm) && ($ampm == "pm") && ($hour < 12)) {
			$hour += 12;
		}
		if (isset($ampm) && ($ampm == "am") && ($hour > 11)) {
			$hour -= 12;
		}
	}

	list($starttime, $endtime) = commonStartEndTime($hour, $minute, $units, $duration);
}

if (($rep_type != 0) && isset($rep_end_month) && isset($rep_end_day) && isset($rep_end_year)) {
    // Get the repeat entry settings
    $rep_enddate = mktime($hour, $minute, 0, $rep_end_month, $rep_end_day, $rep_end_year);
} else {
    $rep_type = 0;
}

// For weekly repeat(2), build string of weekdays to repeat on:
$rep_opt = '';
if ($rep_type == 2) {
    for ($i = 0; $i < 7; $i++) $rep_opt .= empty($rep_day[$i]) ? '0' : '1';
}

// Expand a series into a list of start times:
if ($rep_type != 0)
    $reps = mrbsGetRepeatEntryList($starttime, isset($rep_enddate) ? $rep_enddate : 0,
        $rep_type, $rep_opt, $max_rep_entrys, $rep_num_weeks);

// When checking for overlaps, for Edit (not New), ignore this entry and series:
if ($id != -1) {
    $ignore_id = $id;
    $repeat_id = sql_query1(sprintf(
    	'SELECT repeat_id FROM %s WHERE id = %d',
    	$tbl_entry, $id
    ));
    if ($repeat_id < 0) $repeat_id = 0;
} else {
    $ignore_id = 0;
    $repeat_id = 0;
}

// Acquire mutex to lock out others trying to book the same slot(s).
if (!sql_mutex_lock($tbl_entry)) fatal_error(true, get_vocab("failed_to_acquire"));
    
// Check for any schedule conflicts in each room we're going to try and
// book in
$err = '';
foreach ($rooms as $room_id) {
	$room_id = intval($room_id);
	if ($rep_type != 0 && !empty($reps)) {
    	if(count($reps) < $max_rep_entrys) {
    	    for($i = 0; $i < count($reps); $i++) {
			    // calculate diff each time and correct where events
			    // cross DST
    	        $diff = $endtime - $starttime;
    	        $diff += cross_dst($reps[$i], $reps[$i] + $diff);
    	
			    $tmp = schoorbsCheckFree($room_id, $reps[$i], $reps[$i] + $diff, 
			    	$ignore_id, $repeat_id);
    	        if(!empty($tmp)) $err = $err.$tmp;
    	    }
	    } else {
    	    $err.= get_vocab('too_may_entrys').'<br /><br />';
    	    $hide_title = 1;
		}
  } else
    $err.= schoorbsCheckFree($room_id, $starttime, $endtime-1, $ignore_id, 0);
} // end foreach rooms



if (empty($err)) {
	foreach ( $rooms as $room_id ) {
		$room_id = intval($room_id);
        if ($edit_type == 'series') {
            $new_id = mrbsCreateRepeatingEntrys($starttime, $endtime, $rep_type, 
            	$rep_enddate, $rep_opt, $room_id, $create_by, $name, $type, 
            	$description, isset($rep_num_weeks) ? $rep_num_weeks : 0);
        } else {
            // Mark changed entry in a series with entry_type 2:
            if ($repeat_id > 0) {
                $entry_type = 2;
            } else {
                $entry_type = 0;
            }

            // Create the entry:
            $new_id = schoorbsCreateSingleEntry($starttime, $endtime, $entry_type, 
            	$repeat_id, $room_id, $create_by, $name, $type, $description);
        }

        $oNewEntry = Entry::getById($new_id);
        $aNewEntryInfo = array();
	$aNewEntryInfo['name'] = $oNewEntry->getName();
	$aNewEntryInfo['room_id'] = $oNewEntry->getRoom()->getId();
	$aNewEntryInfo['start_time'] = $oNewEntry->getStartTime();
	$aNewEntryInfo['end_time'] = $oNewEntry->getEndTime();
	$aNewEntryInfo['create_by'] = $oNewEntry->getCreateBy();
        if ($id != -1) { // Edit
		$oOldEntry = Entry::getById($id);
		$aOldEntryInfo = array();
		$aOldEntryInfo['name'] = $oOldEntry->getName();
		$aOldEntryInfo['room_id'] = $oOldEntry->getRoom()->getId();
		$aOldEntryInfo['start_time'] = $oOldEntry->getStartTime();
		$aOldEntryInfo['end_time'] = $oOldEntry->getEndTime();
		$aOldEntryInfo['create_by'] = $oOldEntry->getCreateBy();
        	schoorbsLogEditEntry($aOldEntryInfo, $aNewEntryInfo);
        } else { // Add
        	schoorbsLogAddEntry($aNewEntryInfo);
        }
    } 
    // end foreach $rooms

    // Delete the original entry
    if($id != -1) {
        schoorbsDelEntry(getUserName(), $id, ($edit_type == "series"), 1);
    }

    sql_mutex_unlock($tbl_entry);
    
    $area = mrbsGetRoomArea($room_id);
    
    // Now its all done go back to the day view
    header("Location: day-view.php?year=$year&month=$month&day=$day&area=$area");
    exit(0);
}

// The room was not free.
sql_mutex_unlock($tbl_entry);

print_header();

if (strlen($err) > 0) {
    echo '<h2>' . get_vocab('sched_conflict') . '</h2>';
    if (!isset($hide_title)) {
        echo get_vocab('conflict');
        echo '<ul>';
    }
    
    echo $err;
    
    if(!isset($hide_title)) echo '</ul>';
}

echo "<a href=\"$returl\">".get_vocab('returncal').'</a><br /><br />';

require_once 'schoorbs-includes/trailer.php';
