<?php
/**
 * Functions which deal with time
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Time
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * This will return the appropriate value for isdst for mktime().
 * The order of the arguments was chosen to match those of mktime.
 * hour is added so that this function can when necessary only be
 * run if the time is between midnight and 3am (all DST changes
 * occur in this period.
 * 
 * @author jberanek
 * @param int $month
 * @param int $day
 * @param int $year
 * @param int $hour
 * @return int
 */
function is_dst ( $month, $day, $year, $hour="-1" )
{

	if( $hour != -1  && $hour > 3)
		return -1;
	# entering DST
	if( !date( "I", mktime(12, 0, 0, $month, $day-1, $year)) && 
	    date( "I", mktime(12, 0, 0, $month, $day, $year)))
		return 0; 
	# leaving DST
	elseif( date( "I", mktime(12, 0, 0, $month, $day-1, $year)) && 
	    !date( "I", mktime(12, 0, 0, $month, $day, $year)))
		return 1;
	else
		return -1;
}

/**
 * Make up the time of morning
 * 
 * @param int $month
 * @param int $day
 * @param int $year
 * @return int
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function am7($day, $month, $year)
{
	global $morningstarts, $morningstarts_minutes;

	return mktime($morningstarts,$morningstarts_minutes,0,$month,$day,$year,
		is_dst($month,$day,$year,$morningstarts));
}

/**
 * Make up the time of evening
 * 
 * @param int $month
 * @param int $day
 * @param int $year
 * @return int
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function pm7($day, $month, $year)
{
	global $eveningends, $eveningends_minutes;

	return mktime($eveningends,$eveningends_minutes,0,$month,$day,$year,
		is_dst($month,$day,$year,$eveningends));
}

/**
 * Returns the Day+Month+Year of yesterday
 * 
 * @param int $day
 * @param int $month
 * @param int $year
 * @return array (day,month,year)
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function getYesterday($day, $month, $year)
{
	$i = mktime(12,0,0,$month,$day-1,$year);
	$aRet = array();
	$aRet[] = date("d",$i);
	$aRet[] = date("m",$i);
	$aRet[] = date("Y",$i);
	return $aRet;
}

/**
 * Returns the Day+Month+Year of Tomorrow
 * 
 * @param int $day
 * @param int $month
 * @param int $year
 * @return array (day,month,year)
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function getTomorrow($day, $month, $year)
{
	$i = mktime(12,0,0,$month,$day+1,$year);
	$aRet = array();
	$aRet[] = date("d",$i);
	$aRet[] = date("m",$i);
	$aRet[] = date("Y",$i);
	return $aRet;
}

/**
 * Returns the Day+Month+Year of next Week
 *
 * @param int $day
 * @param int $month
 * @param int $year
 * @return array (day,month,year)
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function getNextWeek($day, $month, $year)
{
        $i = mktime(12,0,0,$month,$day+7,$year);
        $aRet = array();
        $aRet[] = date("d",$i);
        $aRet[] = date("m",$i);
        $aRet[] = date("Y",$i);
        return $aRet;
}

/**
 * Returns the Day+Month+Year of last Week
 *
 * @param int $day
 * @param int $month
 * @param int $year
 * @return array (day,month,year)
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function getLastWeek($day, $month, $year)
{
        $i = mktime(12,0,0,$month,$day-7,$year);
        $aRet = array();
        $aRet[] = date("d",$i);
        $aRet[] = date("m",$i);
        $aRet[] = date("Y",$i);
        return $aRet;
}

/**
 * Format the duration as nice time-string
 * 
 * @param $dur int
 * @param $units string
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function toTimeString(&$dur, &$units)
{
	if ($dur >= 60) {
		$dur /= 60;
		if ($dur >= 60) {
			$dur /= 60;
			if (($dur >= 24) && ($dur % 24 == 0)) {
				$dur /= 24;
				if (($dur >= 7) && ($dur % 7 == 0)) {
					$dur /= 7;
					if (($dur >= 52) && ($dur % 52 == 0)) {
						$dur  /= 52;
						$units = get_vocab('years');
					} else $units = get_vocab('weeks');
				} else $units = get_vocab('days');
			} else $units = get_vocab('hours');
		} else $units = get_vocab('minutes');
	} else $units = get_vocab('seconds');
}


/**
 * Format the duration as a nice period-string
 *
 * @param $start_period int
 * @param $dur int
 * @param $units string
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function toPeriodString($start_period, &$dur, &$units)
{
	global $periods;

	$max_periods = count($periods);
	$dur /= 60;

    if (($dur >= $max_periods) || ($start_period == 0)) {
		if (($start_period == 0) && ($dur == $max_periods)) {
			$units = get_vocab('days');
			$dur = 1;
			return;
		}

        $dur /= 60;
        if (($dur >= 24) && is_int($dur)) {
			$dur /= 24;
			$units = get_vocab('days');
			return;
		} else {
			$dur *= 60;
			$dur = ($dur % $max_periods) + floor( $dur/(24*60) ) * $max_periods;
			$units = get_vocab('periods');
			return;
		}
	} else $units = get_vocab('periods');
}
