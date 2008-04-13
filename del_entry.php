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

/// Var Init ///

if (isset($_REQUEST['id'])) $id = intval($_REQUEST['id']);
if (isset($_REQUEST['series'])) $series = intval($_REQUEST['series']);

/// Main ///

// Check only if the user is logged in, if he has enough rights to delete
// will be checked later on in schoorbsDelEntry()
if (getAuthorised(1) && ($info = mrbsGetEntryInfo($id))) {
	// Get day, month and year of the booking which should be deleted
	$day   = date('d', $info['start_time']);
	$month = date('m', $info['start_time']);
	$year  = date('Y', $info['start_time']);
	$area  = mrbsGetRoomArea($info['room_id']);

	// Do not delete entries which are already in progress or have happened in
	// the past. This should prohibit abuse when using the room and resource
	// booking system.
    if ($info['start_time'] <= time()) {
        /** @todo Translate this */
        fatal_error(true, 'Start time in Past, could not delete entry!');
    }

    sql_begin();
	$result = schoorbsDelEntry(getUserName(), $id, $series, 1);
	sql_commit();
	if ($result) {
        // Log deletion of an entry
        schoorbsLogDeletedEntry($info);
        // Return to the day where we deleted that entry
        header("Location: day.php?day=$day&month=$month&year=$year&area=$area");
		exit();
	}
}

// If you got this far then we got an access denied.
showAccessDenied();
