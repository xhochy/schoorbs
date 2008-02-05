<?php
/**
 * Functions to log bookings of rooms/resources
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Logging
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @todo Support mutiple loggers
 */
 
## Includes ##

/** The configuration of the logging system */
require_once dirname(__FILE__).'/logging.configuration.php';

// only load configured backend, if logging is active, else choose null-backend
if ($_SCHOORBS['logging']['active']) {
	/** The choosen logging backend */
	require_once dirname(__FILE__).'/logging/'.$_SCHOORBS['logging']['backend'].'.php';
} else {
    /** The null-logging backend */
	require_once dirname(__FILE__).'/logging/null.php';
}

## Functions ##

/**
 * Log that someone has removed an entry
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param $aEntryInfo array
 * @todo Translate message
 */
function schoorbsLogDeletedEntry($aEntryInfo)
{
	$sLine = sprintf(
		'Entry "%s" in resource "%s" (%s -> %s) created by "%s" was deleted by '
		.'"%s"',
		$aEntryInfo['name'], schoorbsGetResourceName($aEntryInfo['room_id']), 
		date('d M Y H:i:s', $aEntryInfo['start_time']),
		date('d M Y H:i:s', $aEntryInfo['end_time']),
		$aEntryInfo['create_by'], getUserName()
	);
	schoorbsLogWriteLine_Backend($sLine);
}

## Main ##

// startup the Backend
schoobsLogStart_Backend();

