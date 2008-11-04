<?php
/**
 * Edit a room, the name, the capacity and its description can be edited.
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

/// Main ///

// Only administrators should be able to create rooms and areas
if (!getAuthorised(2)) {
	showAccessDenied();
}

// Check if an room id was supplied, if there is none, we won't know which room
// we want to edit, a default room selecton might be nice, but if the user 
// doesn't directly knows that he is editing a random room, he might change the
// false room.
if (!isset($_REQUEST['room'])) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) was provided!'));
	exit();
// An emty room id might indicate a false call, if we would use intval() on 
// this, it will return 0, so room 0 would be edited which might be confusing.
} else if (empty($_REQUEST['room'])) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) was provided!'));
	exit();
}

// Get the room id as an integer.
$nRoom = intval($_REQUEST['room']);

/** @todo report if an empty string was submitted **/
if (isset($_REQUEST['room-name']) && !empty($_REQUEST['room-name'])) {
	// Get the room out of the database
	$oRoom = Room::getById($nRoom);
	// Set the new values
	$oRoom->setName(unslashes($_REQUEST['room-name']));
	$oRoom->setCapacity(intval($_REQUEST['capacity']));
	$oRoom->setDescription(unslashes($_REQUEST['description']));
	// Get the id of the area of this room to know where we will redirect
	// the user after he finished editing the room.
	$nArea = $oRoom->getArea()->getId();
	// destruct to save it
	$oRoom = null;
	header(sprintf('Location: administration.php?area=%d', $nArea));
} else {
	// Edit!
	SchoorbsTPL::populateVar('room', Room::getById($nRoom));
	SchoorbsTPL::renderPage('edit-room');
}
