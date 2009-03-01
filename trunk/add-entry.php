<?php
/**
 * Add a new booking.
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/// Includes ///

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';

/// Var Init ///

/** day, month, year */
list($nDay, $nMonth, $nYear) = input_DayMonthYear();

/// Main ///

// Only allow loged-in users to create a new entry.
if (!getAuthorised(1)) {
	showAccessDenied();
}

// Get the hour which the user selected to book
if (isset($_REQUEST['hour'])) {
	$nHour = intval($_REQUEST['hour']);
} else {
	$nHour = 12;
}

// Get the minute which the user selected to book
if (isset($_REQUEST['minute'])) {
	$nMinute = intval($_REQUEST['minute']);
} else {
	$nMinute = 0;
}

// Get the period which the user selected to book
if (isset($_REQUEST['period']) && $enable_periods) {
	SchoorbsTPL::populateVar('referencePeriod', intval($_REQUEST['period']));
}

// Get all booking types 
$aTypes = array();
for ($c = 'A'; $c <= 'Z'; $c++) {
	if (isset($typel[$c]) && (!empty($typel[$c]))) {
		$aTypes[] = array('c' => $c, 'text' => $typel[$c]);
	}
}

SchoorbsTPL::populateVar('referenceTime', mktime($nHour, $nMinute, 0, $nMonth, $nDay, $nYear));
SchoorbsTPL::populateVar('types', $aTypes);
SchoorbsTPL::renderPage('add-entry');
