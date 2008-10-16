<?php
/**
 * The view of one month
 * 
 * @author gwalker, Uwe L. Korn <uwelk@xhochy.org>
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


/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** The 3 minicalendars */
require_once 'schoorbs-includes/minicals.php';

/// Var Init ///

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();
$day = 1;

/** area **/
$area = input_Area();
    
/** room **/
$room = input_Room();

/// Main ///

/** Get the room we should display */
$oRoom = Room::getById(input_Room());

// Check if we have a room for this area
if ($oRoom === null) {
	$oArea = Area::getById(input_Area());
	SchoorbsTPL::error(Lang::_(sprintf('The area \'%s\' has no rooms.', $oArea->getName())));
	exit(1);
}

// Just get the first day of month using only the variables for month and year
$nStartTime = mktime(0, 0, 0, $month, 1, $year);
// Let's use the first day of next month as the end of this month and substract
// 1 second, so that we are in the current month if we would display that 
// information with functions like date(), if we are in october, we would 
// make up 11/1/20xx 00:00:00 and going one second back gives us 10/31/20xx 
// 23:59:59
$nEndTime = mktime(0, 0, 0, $month+1, 1, $year) - 1;
// Get the month in front and afterwards, just add/substract one, mktime will do
// the remaining.
$nLastMonth = mktime(0, 0, 0, $month-1, 1, $year);
$nNextMonth = mktime(0, 0, 0, $month+1, 1, $year);

// How many entry units does a day offer?
// (morning - evening) / resolution  // all in minutes
$nUnitsPerDay  = ($eveningends * 60) + $eveningends_minutes;
// To get the timespan, remove the morning
$nUnitsPerDay -= ($morningstarts * 60) + $morningstarts_minutes;
// Divide through the resolution to get the number of units
$nUnitsPerDay /= $resolution / 60;
// we do not want any floats here, so keep it as an int
$nUnitsPerDay = intval($nUnitsPerDay);
// Get the number of days this month has
$nDaysInMonth = intval(date('j', $nEndTime));

// Store for each day how many bookings are there
$aNumberOfBookings = array();
for ($i = 0; $i < $nDaysInMonth; $i++) {
	$aNumberOfBookings[$i] = 0;
	for ($nUnit = 0; $nUnit < $nUnitsPerDay; $nUnit++) {
		$nTime = mktime($morningstarts, $morningstarts_minutes, 0, $month, $i + 1, $year);
		$nTime+= $nUnit * $resolution;
				
		/** @todo Only query once for the whole month **/
		$aEntries = Entry::getBetween($oRoom, $nTime, $nTime + $resolution - 1);
		
		// If we have found an entry, increase the counter
		if (count($aEntries) > 0) {
			$aNumberOfBookings[$i]++;
		}
	}
}

SchoorbsTPL::populateVar('numberOfBookings', $aNumberOfBookings);
SchoorbsTPL::populateVar('daysInMonth', $nDaysInMonth);
SchoorbsTPL::populateVar('nextMonth', $nNextMonth);
SchoorbsTPL::populateVar('lastMonth', $nLastMonth);
SchoorbsTPL::populateVar('unitsPerDay', $nUnitsPerDay);
SchoorbsTPL::populateVar('nStartTime', $nStartTime);
SchoorbsTPL::populateVar('room', $oRoom);
SchoorbsTPL::renderPage('month-view');
