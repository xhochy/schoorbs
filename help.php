<?php
/**
 * The help-page
 * 
 * @author thierry_bo, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The versio information of Schoorbs */
require_once 'schoorbs-includes/version.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';
/** The template system */
require_once 'schoorbs-includes/schoorbstpl.class.php';

// Obfuscate Admin-E-Mail
$sAdminMail = '';
for ($i = 0; $i < strlen($mrbs_admin_email); $i++) {
	$sAdminMail .= '&#x'. sprintf("%x",ord($mrbs_admin_email[$i])).';';
}

SchoorbsTPL::populateVar('admin', $mrbs_admin);
SchoorbsTPL::populateVar('adminMail', $sAdminMail);
SchoorbsTPL::populateVar('version', get_schoorbs_version());
SchoorbsTPL::renderPage('help');
