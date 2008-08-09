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

if (!isset($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) for deletion was provided!'));
	exit();
}

if (empty($_REQUEST['area'])) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) for deletion was provided!'));
	exit();
}

$nArea = intval($_REQUEST['area']);

if ($nArea != $_REQUEST['area']) {
	SchoorbsTPL::error(Lang::_('No valid area(-id) for deletion was provided!'));
	exit();
}

/** @todo report if an empty string was submitted **/
if (isset($_REQUEST['room-name']) && !empty($_REQUEST['room-name'])) {
	// Update! and redirect
	$oArea = Area::getById($nArea);
	Room::create($oArea, unslashes($_REQUEST['room-name']), 
		unslashes($_REQUEST['description']), 
		intval($_REQUEST['capacity'])
	);
	header(sprintf('Location: administration.php?area=%d', $nArea));
} else {
	// Edit!
	SchoorbsTPL::populateVar('area', Area::getById($nArea));
	SchoorbsTPL::renderPage('add-room');
}
