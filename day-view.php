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

/// Var Init ///

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();
/** day, month, year of the yesterday */
list($nLastDayDay, $nLastDayMonth, $nLastDayYear) = getYesterday($day, $month, $year);
/** day, month, year of the twomorrow */
list($nNextDayDay, $nNextDayMonth, $nNextDayYear) = getTomorrow($day, $month, $year);

/** Get the room we should display */
$oRoom = Room::getById(input_Room());

// Check if we have a room for this area, if there's none, we will inform the
// user the selected are (or the default, if no area was selected) has no rooms.
if ($oRoom === null) {
	$oArea = Area::getById(input_Area());
	SchoorbsTPL::error(Lang::_(sprintf('The area \'%s\' has no rooms.', $oArea->getName())));
	exit(1);
}

// Main //

// The time where the first unit of the day starts.
$nStartTime = mktime($morningstarts, $morningstarts_minutes, 0, $month, $day, 
	$year, is_dst($month, $day, $year, $morningstarts));
// The time where the last unit of the day *ends*.
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
		
	// If periods is enabled, so display the label of that period otherwise
	// display the hour and minute when the certain unit starts. We always
	// use here the twentyfour-hour-format to keep code small. The usage
	// of PM/AM would lead to more code (we want to keep Schoorbs as small
	// as possible) and wider columns in timetables. Since this is used as
	// a standard index in the data provided to the theme, we need a 
	// strict convention how the indexes of the array over the units of one
	// day is named.
	//
	// If you still want to have AM/PM times displayed in Schoorbs, you 
	// should take care of this in your themes. This internal code shouldn't
	// be getting fatter through this optical thing.
	if ($enable_periods) {
		$sTime = $periods[$nUnit];
	} else {
		$sTime = date('H:i', $nTime);
	}

	// If there is a booking for this unit, so add it to the array of 
	// today's units and add it to the array of unique entries of this day.
	if (count($aEntries) > 0) {
		$aEntry[$sTime] = $aEntries[0];
		$aUniqueEntry[$aEntries[0]->getStartTime()] = $aEntries[0];
	} else {
		// If there is no entry, set this cell to -1
		// This indicates that this unit is free and bookable. The theme
		// should provide a link to add an entry for this unit.
		$aEntry[$sTime] = -1;
	}
	
	// Provide the starting time of this unit, so that the template may 
	// construct a link to add a new entry starting exactly in this unit.
	// (This is just a helping information during the theming process, it
	// may not be needed).
	$aEntryTime[$sTime] = $nTime;
}

// We want an array sorted chronologically
ksort($aUniqueEntry);

// Pass the variables to the theming engine and display the day-view template.
SchoorbsTPL::populateVar('uniqueEntries', $aUniqueEntry);
SchoorbsTPL::populateVar('entries', $aEntry);
SchoorbsTPL::populateVar('entryTime', $aEntryTime);
SchoorbsTPL::populateVar('nextDay', array($nNextDayDay, $nNextDayMonth, $nNextDayYear));
SchoorbsTPL::populateVar('lastDay', array($nLastDayDay, $nLastDayMonth, $nLastDayYear));
SchoorbsTPL::populateVar('nStartTime', $nStartTime);
SchoorbsTPL::populateVar('room', $oRoom);
SchoorbsTPL::renderPage('day-view');

