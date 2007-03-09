<?php
/**
 * The help-page
 * 
 * @author thierry_bo, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

require_once "grab_globals.php";
require_once "config.inc.php";
require_once "schoorbs-includes/database/$dbsys.php";
require_once 'schoorbs-includes/global.functions.php';
require_once 'schoorbs-includes/version.php';

print_header(date("d"), date("m"), date("Y"), get_default_area());

$smarty->assign('mrbs_version', get_mrbs_version());
$smarty->assign('sql_version',sql_version());
$smarty->assign('php_uname',php_uname());
$smarty->assign('phpversion',phpversion());
$smarty->assign('schoorbs_admin_email',$mrbs_admin_email);
$smarty->assign('schoorbs_admin',$mrbs_admin);

$smarty->display('help.tpl'); 

include "faq/site_faq" . $faqfilelang . ".html";

require_once 'schoorbs-includes/trailer.php';