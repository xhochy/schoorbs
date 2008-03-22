<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {get_vocab} function plugin
 *
 * Type:     function<br />
 * Name:     get_vocab<br />
 * Purpose:  Translate a text in an other language
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param array
 * @param Smarty
 */
function smarty_function_get_vocab($params, &$smarty)
{

    if (!isset($params['text'])) {
        $smarty->trigger_error("get_vocab: missing 'text' parameter");
        return;
    }

    if($params['text'] == '') {
        return;
    }
    
    return get_vocab($params['text']);
}

