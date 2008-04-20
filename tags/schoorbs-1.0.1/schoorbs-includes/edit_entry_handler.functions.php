<?php
/**
 * Helper functions for edit_entry_handler.php
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Time
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Calculate Start- and Endtime for a booking lasting one whole day
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return array (starttime, endtime)
 */
function allDayStartEndTime()
{
	global $enable_periods, $periods, $morningstarts, $morningstarts_minutes,
		$eveningends_minutes, $eveningends;
	
	list($day, $month, $year) = input_DayMonthYear('edit_');
	$max_periods = count($periods);
	
    if ($enable_periods) {
	    $starttime = mktime(12, 0, 0, $month, $day, $year);
	    $endtime   = mktime(12, $max_periods, 0, $month, $day, $year);
	} else {
	    $starttime = mktime($morningstarts, 0, 0, $month, $day, $year, is_dst($month, $day, $year));
	    $end_minutes = $eveningends_minutes + $morningstarts_minutes;
	    ($eveningends_minutes > 59) ? $end_minutes += 60 : '';
	    $endtime   = mktime($eveningends, $end_minutes, 0, $month, $day, $year, is_dst($month, $day, $year));
	}
	
	return array($starttime, $endtime);
}

/**
 * Calculate Start- and Endtime for a common situation
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return array (starttime, endtime)
 */
function commonStartEndTime($hour, $minute, $units, $duration)
{
	global $resolution;

	list($day, $month, $year) = input_DayMonthYear('edit_');
	
	$starttime = mktime($hour, $minute, 0, $month, $day, $year, is_dst($month, $day, $year, $hour));
    $endtime   = mktime($hour, $minute, 0, $month, $day, $year, is_dst($month, $day, $year, $hour)) + ($units * $duration);

    // Round up the duration to the next whole resolution unit.
    // If they asked for 0 minutes, push that up to 1 resolution unit.
    $diff = $endtime - $starttime;
    if (($tmp = $diff % $resolution) != 0 || $diff == 0)
        $endtime += $resolution - $tmp;

    $endtime += cross_dst($starttime, $endtime);
    
    return array($starttime, $endtime);
}
