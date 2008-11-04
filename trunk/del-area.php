<?php
/**
 * Delete an area out of the database. This page does not provide a yes/no-check
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

// Check if an area id was supplied, if there is none, we won't know which area
// we should delete, a default area selection is very dangerous.
if (!isset($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) for deletion was provided!'));
	exit();
// An emty area id might indicated a false call, if we would use intval() on 
// this, it will return 0, so area 0 would be deleted what the user mostly does
// not want.
} else if (empty($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) for deletion was provided!'));
	exit();
}

// Get the area id is as an integer.
$nArea = intval($_REQUEST['area']);

// Check that the integer value of area equals the string value. This checks 
// that we won't accept alphanumerical values, only clean numerical values
// should pass.
if ($nArea != $_REQUEST['area']) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) for deletion was provided!'));
	exit();
}

// delete the area in the database
Area::delete($nArea);
// redirect to adminisrat..php?area=<id>
header('Location: administration.php');
