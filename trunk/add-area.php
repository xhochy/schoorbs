<?php
/**
 * Add a room
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

if (!isset($_REQUEST['area-name'])) {
	SchoorbsTPL::error(Lang::_('No name for the area was provided!'));
	exit();
}

if (empty($_REQUEST['area-name'])) {
	SchoorbsTPL::error(Lang::_('No name for the area was provided!'));
	exit();
}

// add the new area to the database
$nArea = Area::create($_REQUEST['area-name'])->getId();
// redirect to adminisrat..php?area=<id>
header(sprintf('Location: administration.php?area=%d', $nArea));
