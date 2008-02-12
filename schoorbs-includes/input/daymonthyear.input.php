<?php
/**
 * Input-Plugin 'DayMonthYear'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Catches the Day,Month and Year out of the REQUEST-Array and the defaults
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param $sVarPrefix string Be able to handle e.g. rep_end_month too
 * @return array (day,month,year)
 */ 
function input_DayMonthYear($sVarPrefix = '')
{
	/** day **/	
	if (!isset($_REQUEST[$sVarPrefix.'day'])) {
		$day   = date("d");
	} else {
	    $day = intval(unslashes($_REQUEST[$sVarPrefix.'day']));
	    if($day < 1) $day = 1;
	    if($day > 31) $day = 31;
	}	

	/** month **/
	if (!isset($_REQUEST[$sVarPrefix.'month'])) {
		$month = date('m');
	} else {
		$month = intval(unslashes($_REQUEST[$sVarPrefix.'month']));
	    if($month < 1) $month = 1;
	    if($month > 12) $month = 12;
	}
	
	/** year **/
	if (!isset($_REQUEST[$sVarPrefix.'year'])) {
		$year = date("Y");
	} else {
		$year = intval(unslashes($_REQUEST[$sVarPrefix.'year']));
	    if($year < 1970) $year = 1970; //there should't be a installation of Schoorbs that goes back to 1970
	    if($year > 2100) $year = 2100; //Will somebody use Schoorbs in 2100?
	}

    # Make the date valid if day is more then number of days in month
	while (!checkdate($month, $day, $year)) {
		$day--;
	}
	
	return array($day, $month, $year);
}
