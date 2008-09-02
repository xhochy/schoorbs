<?php
/**
 * Edit a room
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
	SchoorbsTPL::error(Lang::_('No valid room(-id) was provided!'));
	exit();
}

if (empty($_REQUEST['room'])) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) was provided!'));
	exit();
}

$nRoom = intval($_REQUEST['room']);

if ($nRoom != $_REQUEST['room']) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) was provided!'));
	exit();
}

/** @todo report if an empty string was submitted **/
if (isset($_REQUEST['room-name']) && !empty($_REQUEST['room-name'])) {
	// Update! and redirect
	$oRoom = Room::getById($nRoom);
	$oRoom->setName(unslashes($_REQUEST['room-name']));
	$oRoom->setCapacity(intval($_REQUEST['capacity']));
	$oRoom->setDescription(unslashes($_REQUEST['description']));
	$nArea = $oRoom->getArea()->getId();
	// destruct to save it
	$oRoom = null;
	header(sprintf('Location: administration.php?area=%d', $nArea));
} else {
	// Edit!
	SchoorbsTPL::populateVar('room', Room::getById($nRoom));
	SchoorbsTPL::renderPage('edit-room');
}