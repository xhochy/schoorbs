<?php
/**
 * Functions to handle REST-requests
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 */
 
## Includes ##

require_once dirname(__FILE__).'/../Smarty/libs/Smarty.class.php';

## Plugins ##

require_once dirname(__FILE__).'/rest-plugins/getentriesofday.rest.php';

## Functions ##
 
/**
 * Searches for the last occurrence of 'REST' 
 * and cuts the function name out of the url
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param string $sURL the URL which was called(normally $_SERVER['REDIRECT_URL'])
 * @return string the name of the called function 
 */
function getRESTFunctionName($sURL)
{
	if(($nPos = strrpos($sURL,'REST')) === false) 
		sendRESTError('not a valid Schoorbs-REST-URL',1);
	$sResult = substr($sURL, $nPos + 4);
	$sResult = trim($sResult,'/');
	if(empty($sResult))
		sendRESTError('not a valid Schoorbs-REST-URL',1);
	if(!ctype_alnum($sResult))
		sendRESTError('not a valid Schoorbs-REST-URL',1);
	return $sResult;
}

/**
 * Return an REST-XML answer for an error and stops the script
 * 
 * @author Uwe L. Korn
 * @param string $sMessage
 * @param int $nCode
 */
function sendRESTError($sMessage, $nCode)
{
	global $_TPL;
	
	sendRESTHeaders();
	$_TPL->assign('message',$sMessage);
	$_TPL->assign('code',$nCode);
	$_TPL->display('error.tpl');
	exit($nCode);
}

/**
 * Sends the HTTP-headers specifing an REST-Document
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function sendRESTHeaders()
{
	header('Content-type: text/xml; charset=utf-8');
	
	// REST-Answers should never be chached!
	header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
}

/**
 * Inits the Smarty Template System for REST-Rendering
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function InitRESTSmarty()
{
	global $_TPL;	

	$_TPL = new Smarty();

	$_TPL->template_dir = dirname(__FILE__).'/../Smarty/templates/REST';
	$_TPL->compile_dir = dirname(__FILE__).'/../Smarty/templates_c';
	$_TPL->cache_dir = dirname(__FILE__).'/../Smarty/cache';
	$_TPL->config_dir = dirname(__FILE__).'/../Smarty/configs';
	
}

/**
 * Calls a REST function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param string $sFunctionName
 */
function callRESTFunction($sFunctionName)
{
	call_user_func('rest_function_'.$sFunctionName);
}

/**
 * Checks if the REST-functions exists
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param string
 * @return bool
 */
function isValidRESTFunction($sFunctionName)
{
	return function_exists('rest_function_'.$sFunctionName);
}
?>