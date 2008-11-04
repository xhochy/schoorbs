<?php
/**
 * Add an area.
 *
 * This just requests a suitable name for the area that shall be created.
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

// Require a name for this area to be set!
if (!isset($_REQUEST['area-name'])) {
	SchoorbsTPL::error(Lang::_('No name for the area was provided!'));
	exit();
// An empty name for an area ist not allowed.
//
// An empty name could indicate a false submit too, just show this warning,
// the user could easily restore its values with the browsers back button.
} else if (empty($_REQUEST['area-name'])) {
	SchoorbsTPL::error(Lang::_('No name for the area was provided!'));
	exit();
}

// add the new area to the database
$nArea = Area::create($_REQUEST['area-name'])->getId();
// redirect to adminisrat..php?area=<id>
header(sprintf('Location: administration.php?area=%d', $nArea));
