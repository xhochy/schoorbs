<?php
/**
 * Delete a room out of the database. This page does not provide a yes/no-check
 * by itself, yes-no.php must be called as proxy in between to do this.
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

// Only administrators should be able to create rooms and areas
if (!getAuthorised(2)) {
	showAccessDenied();
}

// Check if a room id was supplied, if there is none, we won't know which area
// we should delete, a default room selection is very dangerous.
if (!isset($_REQUEST['room'])) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) for deletion was provided!'));
	exit();
// An emty room id might indicated a false call, if we would use intval() on 
// this, it will return 0, so area 0 would be deleted what the user mostly does
// not want.	
} else if (empty($_REQUEST['room'])) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) for deletion was provided!'));
	exit();
}

// Get the room id is as an integer
$nRoom = intval($_REQUEST['room']);

// Check that the integer value of room equals the string value. This checks 
// that we won't accept alphanumerical values, only clean numerical values
// should pass.
if ($nRoom != $_REQUEST['room']) {
	SchoorbsTPL::error(Lang::_('No valid room(-id) for deletion was provided!'));
	exit();
}

// Get the area of the room which should be deleted, we do not store the room 
// in a variable, so that it will be destructed as early as possible by the
// garbage collector.
$nArea = Room::getById($nRoom)->getArea()->getId();
// delete the room in the database
Room::delete($nRoom);
// redirect to adminisrat..php?area=<id>
header(sprintf('Location: administration.php?area=%d', $nArea));
