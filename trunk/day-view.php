<?php
/**
 * The view of one day.
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
/** day, month, year of the yesterday */
list($nLastDayDay, $nLastDayMonth, $nLastDayYear) = getYesterday($day, $month, $year);
/** day, month, year of the twomorrow */
list($nNextDayDay, $nNextDayMonth, $nNextDayYear) = getTomorrow($day, $month, $year);

/** Get the room we should display */
$oRoom = Room::getById(input_Room());

// Check if we have a room for this area
if ($oRoom === null) {
	$oArea = Area::getById(input_Area());
	SchoorbsTPL::error(Lang::_(sprintf('The area \'%s\' has no rooms.', $oArea->getName())));
	exit(1);
}

// Main //

$nStartTime = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day, 
	$year, is_dst($month, $day, $year, $morningstarts));
$nEndTime = mktime($eveningends, $eveningends_minutes, 0, $month, $day, 
	$year, is_dst($month, $day, $year, $eveningends));

// Build up Day table
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
// $aEntry[Morning..Evening(in seconds))] = Entry
// If there is no entry in a unit, we will set that array entry to -1, not to
// null since null is equal to unset($aEntry[..][..]), but we want a full 
// timetable matrix.
$aEntry = array();
// $aEntryTime carries the timestamp for beginning of each booking unit. It is 
// accessd via $aEntry[$sTime]
$aEntryTime = array();
// $aUniqueEntry carries all entries sorted by time. In contrast to $aEntry its
// index is the time when the bookings start and no entry will appear twice.
$aUniqueEntry = array();

for ($nUnit = 0; $nUnit < $nUnitsPerDay; $nUnit++) {
	$nTime = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day, $year);
	$nTime+= $nUnit * $resolution;
	
	$aEntries = Entry::getBetween($oRoom, $nTime, $nTime + $resolution - 1);
		
	if ($enable_periods) {
		$sTime = $periods[$nUnit];
	} else {
		$sTime = date('H:i', $nTime);
	}

	if (count($aEntries) > 0) {
		//var_dump($aEntries);die();
		//entry per id laden
		$oEntry = $aEntries[0];
		$aEntry[$sTime] = $oEntry;
		$aUniqueEntry[$oEntry->getStartTime()] = $oEntry;
	} else {
		$aEntry[$sTime] = -1;
	}
	$aEntryTime[$sTime] = $nTime;
}

// We want an array sorted chronologically
ksort($aUniqueEntry);

SchoorbsTPL::populateVar('uniqueEntries', $aUniqueEntry);
SchoorbsTPL::populateVar('entries', $aEntry);
SchoorbsTPL::populateVar('entryTime', $aEntryTime);
SchoorbsTPL::populateVar('nextDay', array($nNextDayDay, $nNextDayMonth, $nNextDayYear));
SchoorbsTPL::populateVar('lastDay', array($nLastDayDay, $nLastDayMonth, $nLastDayYear));
SchoorbsTPL::populateVar('nStartTime', $nStartTime);
SchoorbsTPL::populateVar('room', $oRoom);
SchoorbsTPL::renderPage('day-view');

