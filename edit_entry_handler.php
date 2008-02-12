<?php
/**
 * Handles an edit of an entry
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
## Includes ##

require_once 'grab_globals.php';

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
/** E-Mail helper functions */
require_once 'schoorbs-includes/mail.functions.php';
/** Helper function for this page */
require_once 'schoorbs-includes/edit_entry_handler.functions.php';

## Input ##

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();
$area = input_Area();
$name = input_Name();

if (isset($_REQUEST['id'])) {
	$id = intval($_REQUEST['id']);
} else {
	$id = -1;
}

// TODO: cleaner

if (isset($_REQUEST['all_day'])) $all_day = $_REQUEST['all_day'];
if (isset($_REQUEST['reptype'])) $rep_type = $_REQUEST['reptype'];
if (isset($_REQUEST['rep_end_month'])) $rep_end_month = $_REQUEST['rep_end_month'];
if (isset($_REQUEST['rep_end_day'])) $rep_end_day = $_REQUEST['rep_end_day'];
if (isset($_REQUEST['rep_end_year'])) $rep_end_year = $_REQUEST['rep_end_year'];
if (isset($_REQUEST['rep_day'])) $rep_day = $_REQUEST['rep_day'];
if (isset($_REQUEST['rep_opt'])) $rep_day = $_REQUEST['rep_opt'];
if (isset($_REQUEST['ampm'])) $rep_day = $_REQUEST['ampm'];

if (isset($_REQUEST['rooms'])) {
    $rooms = $_REQUEST['rooms'];
} else {
    fatal_error(true, 'No room selected');
}

if (isset($_REQUEST['hour'])) $hour = intval($_REQUEST['hour']);
if (isset($_REQUEST['minute'])) $minute = intval($_REQUEST['minute']);
if (isset($_REQUEST['period'])) $period = intval($_REQUEST['period']);
list($duration, $dur_units, $units) = input_Duration();
if ($enable_periods) {
	$hour = 12;
	$minute = $period;
	$max_periods = count($periods);
}

## Main ##

if ($id != -1) {
	$sQuery = sprintf(
		'SELECT create_by FROM %s WHERE id = %d', 
		$tbl_entry, $id
	);
	$create_by = sql_query1($sQuery);
	var_dump($sQuery);
	var_dump($create_by);
} else {
	$create_by = getUserName();
}

if(!getAuthorised(1) || !getWritable($create_by, getUserName())) showAccessDenied();

if (isset($all_day) && ($all_day == 'yes')) {
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

if (isset($rep_type) && isset($rep_end_month) && isset($rep_end_day) && isset($rep_end_year)) {
    // Get the repeat entry settings
    $rep_enddate = mktime($hour, $minute, 0, $rep_end_month, $rep_end_day, $rep_end_year);
} else {
    $rep_type = 0;
}

if(!isset($rep_day)) $rep_day = array();

# For weekly repeat(2), build string of weekdays to repeat on:
$rep_opt = "";
if (($rep_type == 2) || ($rep_type == 6))
    for ($i = 0; $i < 7; $i++) $rep_opt .= empty($rep_day[$i]) ? "0" : "1";

# Expand a series into a list of start times:
if ($rep_type != 0)
    $reps = mrbsGetRepeatEntryList($starttime, isset($rep_enddate) ? $rep_enddate : 0,
        $rep_type, $rep_opt, $max_rep_entrys, $rep_num_weeks);

# When checking for overlaps, for Edit (not New), ignore this entry and series:
$repeat_id = 0;
if (isset($id)) {
    $ignore_id = $id;
    $repeat_id = sql_query1("SELECT repeat_id FROM $tbl_entry WHERE id=$id");
    if ($repeat_id < 0)
        $repeat_id = 0;
}
else
    $ignore_id = 0;

# Acquire mutex to lock out others trying to book the same slot(s).
if (!sql_mutex_lock("$tbl_entry"))
    fatal_error(1, get_vocab("failed_to_acquire"));
    
# Check for any schedule conflicts in each room we're going to try and
# book in
$err = "";
foreach ( $rooms as $room_id ) {
  if ($rep_type != 0 && !empty($reps))
  {
    if(count($reps) < $max_rep_entrys)
    {
        
        for($i = 0; $i < count($reps); $i++)
        {
	    # calculate diff each time and correct where events
	    # cross DST
            $diff = $endtime - $starttime;
            $diff += cross_dst($reps[$i], $reps[$i] + $diff);
    
	    $tmp = schoorbsCheckFree($room_id, $reps[$i], $reps[$i] + $diff, $ignore_id, $repeat_id);

            if(!empty($tmp))
                $err = $err . $tmp;
        }
    }
    else
    {
        $err        .= get_vocab("too_may_entrys") . "<br /><br />";
        $hide_title  = 1;
    }
  }
  else
    $err .= schoorbsCheckFree($room_id, $starttime, $endtime-1, $ignore_id, 0);

} # end foreach rooms

if(empty($err))
{
    foreach ( $rooms as $room_id ) {
        if($edit_type == "series")
        {
            $new_id = mrbsCreateRepeatingEntrys($starttime, $endtime,   $rep_type, $rep_enddate, $rep_opt,
                                      $room_id,   $create_by, $name,     $type,        $description,
                                      isset($rep_num_weeks) ? $rep_num_weeks : 0);
            // Send a mail to the Administrator
            if (MAIL_ADMIN_ON_BOOKINGS or MAIL_AREA_ADMIN_ON_BOOKINGS or
                MAIL_ROOM_ADMIN_ON_BOOKINGS or MAIL_BOOKER)
            {
                // Send a mail only if this a new entry, or if this is an
                // edited entry but we have to send mail on every change,
                // and if mrbsCreateRepeatingEntrys is successful
                if ( ( (isset($id) && MAIL_ADMIN_ALL) or !isset($id) ) && (0 != $new_id) )
                {
                    // Get room name and area name. Would be better to avoid
                    // a database access just for that. Ran only if we need
                    // details
                    if (MAIL_DETAILS)
                    {
                        $sql = "SELECT r.id, r.room_name, r.area_id, a.area_name ";
                        $sql .= "FROM $tbl_room r, $tbl_area a ";
                        $sql .= "WHERE r.id=$room_id AND r.area_id = a.id";
                        $res = sql_query($sql);
                        $row = sql_row($res, 0);
                        $room_name = $row[1];
                        $area_name = $row[3];
                    }
                    // If this is a modified entry then call
                    // getPreviousEntryData to prepare entry comparison.
                    if ( isset($id) )
                    {
                        $mail_previous = getPreviousEntryData($id, 1);
                    }
                    $result = notifyAdminOnBooking(!isset($id), $new_id);
                }
            }
        }
        else
        {
            # Mark changed entry in a series with entry_type 2:
            if ($repeat_id > 0)
                $entry_type = 2;
            else
                $entry_type = 0;

            # Create the entry:
            $new_id = schoorbsCreateSingleEntry($starttime, $endtime, $entry_type, $repeat_id, $room_id,
                                     $create_by, $name, $type, $description);
            // Send a mail to the Administrator
            if (MAIL_ADMIN_ON_BOOKINGS or MAIL_AREA_ADMIN_ON_BOOKINGS or
                MAIL_ROOM_ADMIN_ON_BOOKINGS or MAIL_BOOKER)
            {
                // Send a mail only if this a new entry, or if this is an
                // edited entry but we have to send mail on every change,
                // and if mrbsCreateRepeatingEntrys is successful
                if ( ( (isset($id) && MAIL_ADMIN_ALL) or !isset($id) ) && (0 != $new_id) )
                {
                    // Get room name and are name. Would be better to avoid
                    // a database access just for that. Ran only if we need
                    // details.
                    if (MAIL_DETAILS)
                    {
                        $sql = "SELECT r.id, r.room_name, r.area_id, a.area_name ";
                        $sql .= "FROM $tbl_room r, $tbl_area a ";
                        $sql .= "WHERE r.id=$room_id AND r.area_id = a.id";
                        $res = sql_query($sql);
                        $row = sql_row($res, 0);
                        $room_name = $row[1];
                        $area_name = $row[3];
                    }
                    // If this is a modified entry then call
                    // getPreviousEntryData to prepare entry comparison.
                   if ( isset($id) )
                    {
                        $mail_previous = getPreviousEntryData($id, 0);
                    }
                    $result = notifyAdminOnBooking(!isset($id), $new_id);
                }
            }
        }
    } # end foreach $rooms

    # Delete the original entry
    if(isset($id))
        schoorbsDelEntry(getUserName(), $id, ($edit_type == "series"), 1);

    sql_mutex_unlock($tbl_entry);
    
    $area = mrbsGetRoomArea($room_id);
    
    # Now its all done go back to the day view
    header("Location: day.php?year=$year&month=$month&day=$day&area=$area");
    exit;
}

# The room was not free.
sql_mutex_unlock($tbl_entry);

if (strlen($err) > 0) {
    print_header();
    
    echo "<h2>" . get_vocab("sched_conflict") . "</h2>";
    if(!isset($hide_title))
    {
        echo get_vocab("conflict");
        echo "<ul>";
    }
    
    echo $err;
    
    if(!isset($hide_title))
        echo "</ul>";
}

echo "<a href=\"$returl\">".get_vocab("returncal")."</a><br /><br />";

require_once 'schoorbs-includes/trailer.php';
