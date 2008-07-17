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

/// Main ///

// Set the date back to the previous $wTODOeekstarts day (Sunday, if 0):
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
	

SchoorbsTPL::populateVar('nStartTime', $nStartTime);
SchoorbsTPL::populateVar('nEndTime', $nEndTime);	
SchoorbsTPL::renderPage('week-view');

