<?php
/**
 * Functions to handle REST-requests
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
## Includes ##

/** Use Smarty for REST Output */
if (file_exists(dirname(__FILE__).'/Smarty/libs/libs/Smarty.class.php')) {
	// On Debian systems
	require_once dirname(__FILE__).'/Smarty/libs/libs/Smarty.class.php';
} else {
    // On other systems (including Ubuntu)
	require_once dirname(__FILE__).'/Smarty/libs/Smarty.class.php';
}
$bSessionIncluded = 'true';
/** Only use HTTP session for REST requests, so that no ID or equal have to be stored */ 
require_once dirname(__FILE__).'/session-plugins/session_http.php';
/** The Schoorbs Authentication Backend */
require_once dirname(__FILE__).'/authentication/schoorbs_auth.php';

## Plugins ##

/** The getEntriesOfDay-REST-Function */
require_once dirname(__FILE__).'/rest-plugins/getentriesofday.rest.php';
/** The getRoomID-REST-Function */
require_once dirname(__FILE__).'/rest-plugins/getroomid.rest.php';
/** The getPeriodID-REST-Function */
require_once dirname(__FILE__).'/rest-plugins/getperiodid.rest.php';
/** The login-REST-Function */
require_once dirname(__FILE__).'/rest-plugins/login.rest.php';
/** The checkFree-REST-Function */
require_once dirname(__FILE__).'/rest-plugins/checkfree.rest.php';
/** The makeBooking-REST-Function */
require_once dirname(__FILE__).'/rest-plugins/makebooking.rest.php';

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
	$_TPL->assign('message', $sMessage);
	$_TPL->assign('code', $nCode);
	$_TPL->display('error.tpl');
	
	if (defined('REST_TESTING')) throw new Exception('REST Exception' + $sMessage);
	else exit($nCode);
}

/**
 * Sends the HTTP-headers specifing an REST-Document
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function sendRESTHeaders()
{
	if (defined('REST_TESTING')) return;
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

	$_TPL->template_dir = realpath(dirname(__FILE__).'/../schoorbs-misc/templates/REST');
	$_TPL->compile_dir = dirname(__FILE__).'/Smarty/templates_c';
	$_TPL->cache_dir = dirname(__FILE__).'/Smarty/cache';
	$_TPL->config_dir = dirname(__FILE__).'/Smarty/configs';
	
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
