<?php
/**
 * Delete a room
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
/** The authetication wrappers */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** The template system */
require_once 'schoorbs-includes/schoorbstpl.class.php';

// Only administrators should be able to create rooms and areas
if (!getAuthorised(2)) {
	showAccessDenied();
}

if (!isset($_REQUEST['room'])) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) for deletion was provided!'));
	exit();
}

if (empty($_REQUEST['room'])) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) for deletion was provided!'));
	exit();
}

$nRoom = intval($_REQUEST['room']);

if ($nRoom != $_REQUEST['room']) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) for deletion was provided!'));
	exit();
}

$oRoom = Room::getById($nRoom);
$nArea = $oRoom->getArea()->getId();
// There should be any instance of the room before its deletion
$oRoom = null;
// delete the room in the database
Room::delete($nRoom);
// redirect to adminisrat..php?area=<id>
header(sprintf('Location: administration.php?area=%d', $nArea));
