<?php
/**
 * This page will either add or modify a booking
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
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';
/** The template system */
require_once 'schoorbs-includes/schoorbstpl.class.php';

/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** Database helper functions */
require_once 'schoorbs-includes/database/schoorbs_sql.php';


/// Var Init ///

/** day, month, year */
list($nDay, $nMonth, $nYear) = input_DayMonthYear();

/** id **/
if(isset($_REQUEST['id'])) {
	$nId = intval($_REQUEST['id']);
}

/// Main ///

if (!getAuthorised(1)) showAccessDenied();

if (isset($nId)) {
	$oEntry = Entry::getById($nId);
} else {
	$oEntry = null;
	
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
	SchoorbsTPL::populateVar('referenceTime', mktime($nHour, $nMinute, 0, $nMonth, $nDay, $nYear));
}

// Get all booking types 
$aTypes = array();
for ($c = 'A'; $c <= 'Z'; $c++) {
	if (isset($typel[$c]) && (!empty($typel[$c]))) {
		$aTypes[] = array('c' => $c, 'text' => $typel[$c]);
	}
}

SchoorbsTPL::populateVar('types', $aTypes);
SchoorbsTPL::populateVar('entry', $oEntry);
SchoorbsTPL::renderPage('edit-entry');
