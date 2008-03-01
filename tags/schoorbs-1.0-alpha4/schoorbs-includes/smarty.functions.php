<?php
/**
 * Uses the fast and simply template system Smarty
 *
 * @author Uwe L. Korn
 * @package Schoorbs 
 * @subpackage Smarty
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */


/** The genDateSelector-Smarty Pluign */
require_once dirname(__FILE__).'/smarty-plugins/function.genDateSelector.php';
/** The get_vocab-Smarty Plugin */
require_once dirname(__FILE__).'/smarty-plugins/function.get_vocab.php';
/** put full path to Smarty.class.php */
require_once dirname(__FILE__).'/Smarty/libs/Smarty.class.php';

/** Init Smarty */
$smarty = new Smarty();

$smarty->template_dir = realpath(dirname(__FILE__).'/../schoorbs-misc/templates');
$smarty->compile_dir = dirname(__FILE__).'/Smarty/templates_c';
$smarty->cache_dir = dirname(__FILE__).'/Smarty/cache';
$smarty->config_dir = dirname(__FILE__).'/Smarty/configs';
$smarty->register_function('get_vocab', smarty_function_get_vocab);
$smarty->register_function('genDateSelector', smarty_function_genDateSelector);