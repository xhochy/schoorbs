<?php
/**
 * Add a room.
 *
 * This just wants a name, a description and the capacity of the new room. An
 * area to which this room should be added needs to submitted too.
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

// Require a valid area for this room
if (!isset($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) was provided!'));
	exit();
// An empty name for a room ist not allowed.
//
// An empty name could indicate a false submit too, just show this warning,
// the user could easily restore its values with the browsers back button.	
} else if (empty($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) was provided!'));
	exit();
}

// Get the supplied area id as an integer
$nArea = intval($_REQUEST['area']);

// Check if the suppilied area id was a valid number
if ($nArea != $_REQUEST['area']) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) was provided!'));
	exit();
}

/** @todo report if an empty string was submitted **/
if (isset($_REQUEST['room-name']) && !empty($_REQUEST['room-name'])) {
	// Get the selected area, we may get the area object already while we
	// read in the area id but if we just display the creation form, we do 
	// not need this object.
	$oArea = Area::getById($nArea);
	// Create the room
	Room::create($oArea, unslashes($_REQUEST['room-name']), 
		unslashes($_REQUEST['description']), 
		intval($_REQUEST['capacity'])
	);
	// Update! and redirect
	header(sprintf('Location: administration.php?area=%d', $nArea));
} else {
	// No values supplied, so:
	// Edit!
	SchoorbsTPL::populateVar('area', Area::getById($nArea));
	SchoorbsTPL::renderPage('add-room');
}
