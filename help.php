<?php
/**
 * Display the help page. 
 *
 * The help textes reside in the translation file, so that they are translate-
 * able through Launchpad too. In contrast to Schoorbs 1.x/MRBS 1.x the user is
 * presented the FAQ's which fit best to his selected language in the browser
 * and not the language which was choosen in the configuration file.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
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

// Obfuscate Admin-E-Mail
$sAdminMail = '';
// Go through each character
for ($i = 0; $i < strlen($mrbs_admin_email); $i++) {
	// Use hex character representation since its shorter than decimal
	$sAdminMail .= '&#x'. sprintf("%x",ord($mrbs_admin_email[$i])).';';
}

// Populate all data to the theming engine and display the help template
SchoorbsTPL::populateVar('admin', $mrbs_admin);
SchoorbsTPL::populateVar('adminMail', $sAdminMail);
SchoorbsTPL::populateVar('version', get_schoorbs_version());
SchoorbsTPL::renderPage('help');
