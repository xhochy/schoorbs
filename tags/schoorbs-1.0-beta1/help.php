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
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** The versio information of Schoorbs */
require_once 'schoorbs-includes/version.php';

print_header();

$smarty->assign('mrbs_version', get_schoorbs_version());
$smarty->assign('sql_version', sql_version());
$smarty->assign('php_uname', php_uname());
$smarty->assign('phpversion', phpversion());
$smarty->assign('schoorbs_admin_email', $mrbs_admin_email);
$smarty->assign('schoorbs_admin', $mrbs_admin);

$smarty->display('help.tpl'); 

require_once "schoorbs-includes/faq/site_faq${faqfilelang}.html";

require_once 'schoorbs-includes/trailer.php';
