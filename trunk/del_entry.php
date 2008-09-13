<?php
/**
 * Deletes an entry
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
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
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** The authetication wrappers */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** Database helper functions */
require_once 'schoorbs-includes/database/schoorbs_sql.php';
/** The logging wrapper */
require_once 'schoorbs-includes/logging.functions.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';

/// Var Init ///

if (isset($_REQUEST['id'])) $id = intval($_REQUEST['id']);
if (isset($_REQUEST['series'])) $series = intval($_REQUEST['series']);

/// Main ///

// Check only if the user is logged in, if he has enough rights to delete
// will be checked later on in schoorbsDelEntry()
if (getAuthorised(1) && ($oEntry = Entry::getById($id))) {
	// Get day, month and year of the booking which should be deleted
	$day   = date('d', $oEntry->getStartTime());
	$month = date('m', $oEntry->getStartTime());
	$year  = date('Y', $oEntry->getStartTime());
	$area  = $oEntry->getRoom()->getArea()->getId();

	// Do not delete entries which are already in progress or have happened in
	// the past. This should prohibit abuse when using the room and resource
	// booking system.
    	if ($oEntry->getStartTime() <= time()) {
        	/** @todo Translate this */
        	fatal_error(true, 'Start time in Past, could not delete entry!');
    	}

	$aEntryInfo = array();
	$aEntryInfo['name'] = $oEntry->getName();
	$aEntryInfo['room_id'] = $oEntry->getRoom()->getId();
	$aEntryInfo['start_time'] = $oEntry->getStartTime();
	$aEntryInfo['end_time'] = $oEntry->getEndTime();
	$aEntryInfo['create_by'] = $oEntry->getCreateBy();

	$oEntry = null;
    	sql_begin();
	$result = schoorbsDelEntry(getUserName(), $id, $series, 1);
	sql_commit();
	if ($result) {
		// Log deletion of an entry
        	schoorbsLogDeletedEntry($aEntryInfo);
        	// Return to the day where we deleted that entry
        	header("Location: day-view.php?day=$day&month=$month&year=$year&area=$area");
		exit();
	}
}

// If you got this far then we got an access denied.
showAccessDenied();
