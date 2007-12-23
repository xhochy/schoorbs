<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {genDateSelector} function plugin
 *
 * Type:     function<br>
 * Name:     genDateSelector<br>
 * Purpose:  Generates a form for selecting a specific date
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @param array
 * @param Smarty
 */
function smarty_function_genDateSelector($params, &$smarty)
{

    if (!isset($params['prefix'])) {
        $smarty->trigger_error("get_vocab: missing 'prefix' parameter");
        return;
    }
    
    $prefix = $params['prefix'];
    if(!isset($params['day']))
    	$day = date('d');
    else
    	$day = intval($params['day']);
    if(!isset($params['month']))
    	$month = date('m');
    else
    	$month = intval($params['month']);
    if(!isset($params['year']))
    	$year = date("Y");
    else
    	$year = intval($params['year']);
    	
    $sOut = "<select name=\"${prefix}day\" id=\"${prefix}day\">\n";
	
	for($i = 1; $i <= 31; $i++)
		$sOut.= "<option" . ($i == $day ? " selected=\"selected\"" : "") . ">$i</option>\n";

	$sOut.= "</select>";
	$sOut.= "<select name=\"${prefix}month\" id=\"${prefix}month\" onchange=\"ChangeOptionDays('$prefix')\">\n";

	for($i = 1; $i <= 12; $i++)
	{
		$m = utf8_strftime("%b", mktime(0, 0, 0, $i, 1, $year));
		
		$sOut.= "<option value=\"$i\"" . ($i == $month ? " selected=\"selected\"" : "") . ">$m</option>\n";
	}

	$sOut.= "</select>";
	$sOut.= "<select name=\"${prefix}year\" id=\"${prefix}year\" onchange=\"ChangeOptionDays('$prefix')\">\n";

	$min = min($year, date("Y")) - 5;
	$max = max($year, date("Y")) + 5;

	for($i = $min; $i <= $max; $i++)
		$sOut.= "<option value=\"$i\"" . ($i == $year ? " selected=\"selected\"" : "") . ">$i</option>\n";

	$sOut.= "</select>";
	return $sOut;
}