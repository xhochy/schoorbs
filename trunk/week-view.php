<?php
/**
 * The view of one week.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
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
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';
/** The template system */
require_once 'schoorbs-includes/schoorbstpl.class.php';

/// Var Init ///

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();

/** Get the room we should display */
$oRoom = Room::getById(input_Room());

/// Main ///

// Set the date back to the previous $weekstarts day (Sunday, if 0):
$time = mktime(12, 0, 0, $month, $day, $year);
if (($weekday = (date('w', $time) - $weekstarts + 7) % 7) > 0) {
	$time -= $weekday * 86400;
	$day   = date('d', $time);
	$month = date('m', $time);
	$year  = date('Y', $time);
}

$nStartTime = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day, 
	$year, is_dst($month, $day, $year, $morningstarts));
$nEndTime = mktime($eveningends, $eveningends_minutes, 0, $month,$day + 6, 
	$year, is_dst($month, $day + 6, $year, $eveningends));
	
// Build up Week Matrix
// --> Index: Time/Period name
// --> contains an 7-sized array of the appointments of this slot

// How many entry units does a day offer?
// (morning - evening) / resolution  // all in minutes
$nUnitsPerDay  = ($eveningends * 60) + $eveningends_minutes;
// To get the timespan, remove the morning
$nUnitsPerDay -= ($morningstarts * 60) + $morningstarts_minutes;
// Divide through the resolution to get the number of units
$nUnitsPerDay /= $resolution / 60;
// we do not want any floats here, so keep it as an int
$nUnitsPerDay = intval($nUnitsPerDay);

// save all entries in the following array structure:
// $aEntry[0..6][Morning..Evening(in seconds))] = Entry
// If there is no entry in a unit, we will set that array entry to -1, not to
// null since null is equal to unset($aEntry[..][..]), but we want a full 
// timetable matrix.
$aEntry = array();
$aUniqueEntry = array();

// iterate through the days
for ($nDay = 0; $nDay < 7; $nDay++) {
	$aEntry[$nDay] = array();
	// Iterate through the single uints
	for ($nUnit = 0; $nUnit < $nUnitsPerDay; $nUnit++) {
		$nTime = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day + $nDay, $year);
		$nTime+= $nUnit * $resolution;
		
		
		/** @todo Only query once for the whole week **/
		$aEntries = Entry::getBetween($oRoom, $nTime, $nTime + $resolution);
		/**----error---todo: go on
		entry suchen, id finden**/
		
		if ($enable_periods) {
			$sTime = $periods[$nUnit];
		} else {
			$sTime = date('H:i', $nTime);
		}

		if (count($aEntries) > 0) {
			//var_dump($aEntries);die();
			//entry per id laden
			$oEntry = $aEntries[0];
			$aEntry[$nDay][$sTime] = $oEntry;
			$aUniqueEntry[$oEntry->getStartTime()] = $oEntry;
		} else {
			$aEntry[$nDay][$sTime] = -1;
		}
	}
}

SchoorbsTPL::populateVar('nStartTime', $nStartTime);
SchoorbsTPL::populateVar('nEndTime', $nEndTime);	
SchoorbsTPL::populateVar('uniqueEntries', $aUniqueEntry);
SchoorbsTPL::populateVar('entries', $aEntry);
SchoorbsTPL::renderPage('week-view');

