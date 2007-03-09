<?php
/**
 * Uses the fast and simply template system Smarty
 *
 * @author Uwe L. Korn
 * @package Schoorbs 
 * @subpackage Smarty
 */


// put full path to Smarty.class.php
require_once dirname(__FILE__).'/Smarty/libs/Smarty.class.php';

// Init Smarty
$smarty = new Smarty();

$smarty->template_dir = dirname(__FILE__).'/Smarty/templates';
$smarty->compile_dir = dirname(__FILE__).'/Smarty/templates_c';
$smarty->cache_dir = dirname(__FILE__).'/Smarty/cache';
$smarty->config_dir = dirname(__FILE__).'/Smarty/configs';
?>
