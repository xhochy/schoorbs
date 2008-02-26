<?php
/**
 * Some common functions for data handling
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * Get the name of a room/resource
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param $nResourceID int
 * @return string
 */
function schoorbsGetResourceName($nResourceID)
{
	global $tbl_room;
	
	$sQuery = sprintf(
		'SELECT room_name FROM %s WHERE id = %d',
		$tbl_room, $nResourceID
	);
	
	return sql_query1($sQuery);
}

/** 
 * Check to see if the time period specified is free
 * 
 * @param int $room_id Which room are we checking
 * @param int $starttime The start of period
 * @param int $endtime The end of the period
 * @param int $ignore An entry ID to ignore, 0 to ignore no entries
 * @param int $repignore A repeat ID to ignore everything in the series, 0 to ignore no series
 * @return string If != null an error occured 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 */
function schoorbsCheckFree($room_id, $starttime, $endtime, $ignore, $repignore)
{
	global $tbl_entry, $enable_periods, $periods;

    # Select any meetings which overlap ($starttime,$endtime) for this room:
    $sQuery = sprintf(
    	'SELECT id, name, start_time FROM %s WHERE start_time < %d AND end_time'
    	.' > %d AND room_id = %d',
    	$tbl_entry, $endtime, $starttime, $room_id
    );
    
	if ($ignore > 0)
		$sQuery .= " AND id <> ".sql_escape_arg($ignore);
	if ($repignore > 0)
		$sQuery .= " AND repeat_id <> ".sql_escape_arg($repignore);
	$sQuery .= " ORDER BY start_time";

	$res = sql_query($sQuery);
	if(!$res) fatal_error(true, sql_error());	
	if (sql_count($res) == 0) {
		sql_free($res);
		return null;
	}
	# Get the room's area ID for linking to day, week, and month views:
	$area = mrbsGetRoomArea($room_id);
	
	# Build a string listing all the conflicts:
	$err = "";
	for ($i = 0; ($row = sql_row($res, $i)); $i++)
	{
		$starts = getdate($row[2]);
		$param_ym = "area=$area&amp;year=$starts[year]&amp;month=$starts[mon]";
		$param_ymd = $param_ym . "&amp;day=$starts[mday]";


		if( $enable_periods ) {
        	$p_num =$starts['minutes'];
        	$startstr = utf8_strftime('%A %d %B %Y, ', $row[2]) . $periods[$p_num];
        }
		else
        	$startstr = utf8_strftime('%A %d %B %Y %H:%M:%S', $row[2]);

        $err .= "<li><a HREF=\"view_entry.php?id=$row[0]\">$row[1]</a>"
		. " ( " . $startstr . ") "
		. "(<a href=\"day.php?$param_ymd\">".get_vocab("viewday")."</a>"
		. " | <a href=\"week.php?room=$room_id&$param_ymd\">".get_vocab("viewweek")."</a>"
		. " | <a href=\"month.php?room=$room_id&$param_ym\">".get_vocab("viewmonth")."</a>)</li>";
	}
	
	return $err;
}

/** 
 * Delete an entry, or optionally all entrys.
 * 
 * @param string $user   - Who's making the request
 * @param int $id     - The entry to delete
 * @param bool $series - If set, delete the series, except user modified entrys
 * @param bool $all    - If set, include user modified entrys in the series delete
 * @return int non-zero - The entry was deleted 
 */
function schoorbsDelEntry($user, $id, $series, $all)
{
	global $tbl_entry, $tbl_repeat;

	$repeat_id = sql_query1("SELECT repeat_id FROM $tbl_entry WHERE id = ".sql_escape_arg($id));
	if ($repeat_id < 0)
		return 0;
	
	$sql = "SELECT create_by, id, entry_type FROM $tbl_entry WHERE ";
	
	if($series)
		$sql .= "repeat_id=".sql_escape_arg($repeat_id);
	else
		$sql .= "id=".sql_escape_arg($id);
	
	$res = sql_query($sql);
	
	$removed = 0;
	
	for ($i = 0; ($row = sql_row($res, $i)); $i++)
	{
		if(!getWritable($row[0], $user))
			continue;
		
		if($series && $row[2] == 2 && !$all)
			continue;
		
		if (sql_command("DELETE FROM $tbl_entry WHERE id=" . sql_escape_arg($row[1])) > 0)
			$removed++;
	}
	
	if ($repeat_id > 0 &&
            sql_query1("SELECT count(*) FROM $tbl_entry WHERE repeat_id = ".sql_escape_arg($repeat_id)) == 0)
		sql_command("DELETE FROM $tbl_repeat WHERE id = ".sql_escape_arg($repeat_id));
	
	return $removed > 0;
}

/**
 * Create a single (non-repeating) entry in the database
 * 
 * @param int $starttime Start time of entry
 * @param int $endtime End time of entry
 * @param string $entry_type Entry type
 * @param int $repeat_id   - Repeat ID
 * @param int $room_id     - Room ID
 * @param string $owner       - Owner
 * @param string $name        - Name
 * @param string $type        - Type (Internal/External)
 * @param string $description - Description
 * @return int The entry's ID
 */
function schoorbsCreateSingleEntry($starttime, $endtime, $entry_type, $repeat_id, $room_id,
                               $owner, $name, $type, $description)
{
	global $tbl_entry;
	
	/**
	 * make sure that any entry is of a positive duration
	 * this is to trap potential negative duration created when DST comes
	 * into effect
	 */ 
	if($endtime > $starttime) {
		$sQuery = sprintf(
			'INSERT INTO %s (start_time, end_time, entry_type, repeat_id, '
			.'room_id, create_by, name, type, description) VALUES ( %d, %d, %s,'
			.' %d, %d, \'%s\', \'%s\', \'%s\', \'%s\')', 
			$tbl_entry, $starttime, $endtime, sql_escape_arg($entry_type), 
			$repeat_id, $room_id, sql_escape_arg($owner), sql_escape_arg($name), 
			sql_escape_arg($type), sql_escape_arg($description)
		);
	}
	if (sql_command($sQuery) < 0) return 0;
	
	return sql_insert_id($tbl_entry, 'id');
}

/** mrbsCreateRepeatEntry()
 * 
 * Creates a repeat entry in the data base
 * 
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $rep_type    - The repeat type
 * $rep_enddate - When the repeating ends
 * $rep_opt     - Any options associated with the entry
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 * 
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function schoorbsCreateRepeatEntry($starttime, $endtime, $rep_type, $rep_enddate, $rep_opt,
                               $room_id, $owner, $name, $type, $description, $rep_num_weeks)
{
	global $tbl_repeat;

	$name        = sql_escape_arg($name);
	$description = sql_escape_arg($description);
        $owner       = sql_escape_arg($owner);
        $type        = sql_escape_arg($type);
	
	// Let's construct the sql statement:
	$sql_coln = array(); $sql_val = array();

        // Mandatory things:
	$sql_coln[] = 'start_time'; 	$sql_val[] = $starttime;
	$sql_coln[] = 'end_time'; 	$sql_val[] = $endtime;
	$sql_coln[] = 'rep_type'; 	$sql_val[] = $rep_type;
	$sql_coln[] = 'end_date';	$sql_val[] = $rep_enddate;
	$sql_coln[] = 'room_id';	$sql_val[] = $room_id;
	$sql_coln[] = 'create_by';	$sql_val[] = '\''.$owner.'\'';
	$sql_coln[] = 'type';		$sql_val[] = '\''.$type.'\'';
	$sql_coln[] = 'name';		$sql_val[] = '\''.$name.'\'';

	// Optional things, pgsql doesn't like empty strings!
	if (!empty($rep_opt))
		{$sql_coln[] = 'rep_opt';	$sql_val[] = '\''.$rep_opt.'\'';}
	else
		{$sql_coln[] = 'rep_opt';	$sql_val[] = '\'0\'';}
	if (!empty($description))
		{$sql_coln[] = 'description';	$sql_val[] = '\''.$description.'\'';}
	if (!empty($rep_num_weeks))
		{$sql_coln[] = 'rep_num_weeks';	$sql_val[] = $rep_num_weeks;}

	$sql = 'INSERT INTO ' . $tbl_repeat .
	       ' (' . implode(', ',$sql_coln) . ') '.
	       'VALUES (' . implode(', ',$sql_val) . ')';

	if (sql_command($sql) < 0) return 0;
	
	return sql_insert_id("$tbl_repeat", "id");
}

/** 
 * Find the same day of the week in next month, same week number.
 * 
 * Return the number of days to step forward for a "monthly repeat,
 * corresponding day" serie - same week number and day of week next month.
 * This function always returns either 28 or 35.
 * For dates in the 5th week of a month, the resulting day will be in the 4th
 * week of the next month if no 5th week corresponding day exist.
 * :TODO: thierry_bo 030510: repeat 5th week entries only if 5th week exist.
 * If we want a 5th week repeat type, only 5th weeks have to be booked. We need
 * also a new "monthly repeat, corresponding day, last week of the month" type.
 *
 * @param    integer     $time           timestamp of the day from which we want to find
 *                                       the same day of the week in next month, same
 *                                       week number
 * @return   integer     $days_jump      number of days to step forward to find the next occurence (28 or 35)
 * @param      integer     $days_in_month  number of days in month
 * @param      integer     $day            day of the month (01 to 31)
 * @param      integer     $weeknumber     week number for each occurence ($time)
 * @param      boolean     $temp1          first step to compute $days_jump
 * @param      integer     $next_month     intermediate next month number (1 to 12)
 */
function same_day_next_month($time)
{
    global $_initial_weeknumber;

    $days_in_month = date("t", $time);
    $day = date("d", $time);
    $weeknumber = (int)(($day - 1) / 7) + 1;
    $temp1 = ($day + 7 * (5 - $weeknumber) <= $days_in_month);

    // keep month number > 12 for the test purpose in line beginning with "days_jump = 28 +..."
    $next_month = date("n", mktime(11, 0 ,0, date("n", $time), $day +35, date("Y", $time))) + (date("n", mktime(11, 0 ,0, date("n", $time), $day +35, date("Y", $time))) < date("n", $time)) * 12;

    // prevent 2 months jumps if $time is in 5th week
    $days_jump = 28 + (($temp1 && !($next_month - date("n", $time) - 1)) * 7);

    /* if initial week number is 5 and the new occurence month number ($time + $days_jump)
     * is not changed if we add 7 days, then we can add 7 days to $days_jump to come
     * back to the 5th week (yuh!) */
    $days_jump += 7 * (($_initial_weeknumber == 5) && (date("n", mktime(11, 0 ,0, date("n", $time), $day + $days_jump, date("Y", $time))) == date("n", mktime(11, 0 ,0, date("n", $time), $day + $days_jump + 7, date("Y", $time)))));

    return $days_jump;
}

/** mrbsGetRepeatEntryList
 * 
 * Returns a list of the repeating entrys
 * 
 * $time     - The start time
 * $enddate  - When the repeat ends
 * $rep_type - What type of repeat is it
 * $rep_opt  - The repeat entrys
 * $max_ittr - After going through this many entrys assume an error has occured
 * $_initial_weeknumber - Save initial week number for use in 'monthly repeat same week number' case
 * 
 * Returns:
 *   empty     - The entry does not repeat
 *   an array  - This is a list of start times of each of the repeat entrys
 */
function mrbsGetRepeatEntryList($time, $enddate, $rep_type, $rep_opt, $max_ittr, $rep_num_weeks)
{
	$sec   = date("s", $time);
	$min   = date("i", $time);
	$hour  = date("G", $time);
	$day   = date("d", $time);
	$month = date("m", $time);
	$year  = date("Y", $time);

	global $_initial_weeknumber;
	$_initial_weeknumber = (int)(($day - 1) / 7) + 1;
	$week_num = 0;
	$start_day = date('w', mktime($hour, $min, $sec, $month, $day, $year));
	$cur_day = $start_day;

	$entrys = "";
	for($i = 0; $i < $max_ittr; $i++)
	{
		$time = mktime($hour, $min, $sec, $month, $day, $year);
		if ($time > $enddate)
			break;

		$entrys[$i] = $time;
		
		switch($rep_type)
		{
			// Daily repeat
			case 1:
				$day += 1;
				break;
			
			// Weekly repeat
			case 2:
				$j = $cur_day = date("w", $entrys[$i]);
				// Skip over days of the week which are not enabled:
				while (($j = ($j + 1) % 7) != $cur_day && !$rep_opt[$j])
					$day += 1;

				$day += 1;
				break;
			
			// Monthly repeat
			case 3:
				$month += 1;
				break;
			
			// Yearly repeat
			case 4:
				$year += 1;
				break;
			
			// Monthly repeat on same week number and day of week
			case 5:
				$day += same_day_next_month($time);
				break;

			// n Weekly repeat
			case 6:

				while (1)
				{
					$day++;
					$cur_day = ($cur_day + 1) % 7;

					if (($cur_day % 7) == $start_day)
					{
						$week_num++;
					}

					if (($week_num % $rep_num_weeks == 0) &&
					    ($rep_opt[$cur_day] == 1))
					{
						break;
					}
				}

				break;	
				
			// Unknown repeat option
			default:
				return;
		}
	}

	return $entrys;
}

/** mrbsCreateRepeatingEntrys()
 * 
 * Creates a repeat entry in the data base + all the repeating entrys
 * 
 * $starttime   - Start time of entry
 * $endtime     - End time of entry
 * $rep_type    - The repeat type
 * $rep_enddate - When the repeating ends
 * $rep_opt     - Any options associated with the entry
 * $room_id     - Room ID
 * $owner       - Owner
 * $name        - Name
 * $type        - Type (Internal/External)
 * $description - Description
 * 
 * Returns:
 *   0        - An error occured while inserting the entry
 *   non-zero - The entry's ID
 */
function mrbsCreateRepeatingEntrys($starttime, $endtime, $rep_type, $rep_enddate, $rep_opt,
                                   $room_id, $owner, $name, $type, $description, $rep_num_weeks)
{
	global $max_rep_entrys;
	
	$reps = mrbsGetRepeatEntryList($starttime, $rep_enddate, $rep_type, $rep_opt, $max_rep_entrys, $rep_num_weeks);

	if(count($reps) > $max_rep_entrys)
		return 0;
	
	if(empty($reps))
	{
		$ent = schoorbsCreateSingleEntry($starttime, $endtime, 0, 0, $room_id, $owner, $name, $type, $description);
		return $ent;
	}
	
	$ent = schoorbsCreateRepeatEntry($starttime, $endtime, $rep_type, $rep_enddate, $rep_opt, $room_id, $owner, $name, $type, $description, $rep_num_weeks);
	if($ent)
	{
	
		for($i = 0; $i < count($reps); $i++)
		{
			# calculate diff each time and correct where events
			# cross DST
			$diff = $endtime - $starttime;
			$diff += cross_dst($reps[$i], $reps[$i] + $diff);
	    
			schoorbsCreateSingleEntry($reps[$i], $reps[$i] + $diff, 1, $ent,
				 $room_id, $owner, $name, $type, $description);
		}
	}
	return $ent;
}

/**
 * Get the booking's entrys
 * 
 * $id = The ID for which to get the info for.
 * 
 * Returns:
 *    nothing = The ID does not exist
 *    array   = The bookings info
 */
function mrbsGetEntryInfo($id)
{
	global $tbl_entry;

	$sql = "SELECT start_time, end_time, entry_type, repeat_id, room_id,
	               timestamp, create_by, name, type, description
                FROM $tbl_entry WHERE (ID = $id)";
	
	$res = sql_query($sql);
	if (! $res) return;
	
	$ret = array();
	if(sql_count($res) > 0)
	{
		$row = sql_row($res, 0);
		
		$ret["start_time"]  = $row[0];
		$ret["end_time"]    = $row[1];
		$ret["entry_type"]  = $row[2];
		$ret["repeat_id"]   = $row[3];
		$ret["room_id"]     = $row[4];
		$ret["timestamp"]   = $row[5];
		$ret['create_by']   = $row[6];
		$ret['name']        = $row[7];
		$ret["type"]        = $row[8];
		$ret["description"] = $row[9];
	}
	sql_free($res);
	
	return $ret;
}

function mrbsGetRoomArea($id)
{
	global $tbl_room;

	$id = sql_query1(sprintf(
		'SELECT area_id FROM %s WHERE id = %d',
		$tbl_room, $id
	));
	if ($id <= 0) return 0;
	return $id;
}
