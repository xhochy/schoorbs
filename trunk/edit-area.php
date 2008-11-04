<?php
/**
 * Edit an area. Mainly the only thing which could be edited is the name of this
 * area.
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

// Check if an area id was supplied, if there is none, we won't know which area
// we want to edit, a default area selecton might be nice, but if the user 
// doesn't directly knows that he is editing a random area, he might change the
// false area.
if (!isset($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) was provided!'));
	exit();
// An emty area id might indicated a false call, if we would use intval() on 
// this, it will return 0, so area 0 would be edited which might be confusing.
} else if (empty($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) was provided!'));
	exit();
}

// Get the area id as an integer.
$nArea = intval($_REQUEST['area']);

/** @todo report if an empty string was submitted **/
if (isset($_REQUEST['area-name']) && !empty($_REQUEST['area-name'])) {
	// Get the area out of the database
	$oArea = Area::getById($nArea);
	// Update its name.
	$oArea->setName(unslashes($_REQUEST['area-name']));
	// Destruct to save it
	$oArea = null;
	// redirect to the administration view of this area.
	header(sprintf('Location: administration.php?area=%d', $nArea));
} else {
	// Edit!
	SchoorbsTPL::populateVar('area', Area::getById($nArea));
	SchoorbsTPL::renderPage('edit-area');
}
