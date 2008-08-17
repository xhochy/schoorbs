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

// Print the header
print_header();

// Assign all need variables for the template
$smarty->assign(array(
	'schoorbs_version' => get_schoorbs_version(),
	'schoorbs_admin_email' => $mrbs_admin_email,
	'schoorbs_admin' => $mrbs_admin
));
// Display the Help template
$smarty->display('help.tpl'); 

/** Include the translated Helpfile */
require_once "schoorbs-includes/faq/site_faq${faqfilelang}.html";
/** The common Schoorbs footer */
require_once 'schoorbs-includes/trailer.php';
