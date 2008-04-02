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

/** The Configuration file */
require_once dirname(__FILE__).'/../config.inc.php';
/** The database wrapper */
require_once dirname(__FILE__)."/../schoorbs-includes/database/$dbsys.php";
/** The input checking/validation functions */
require_once dirname(__FILE__).'/../schoorbs-includes/input.functions.php';
/** The time related functions */
require_once dirname(__FILE__).'/../schoorbs-includes/time.functions.php';
/** The global functions */
require_once dirname(__FILE__).'/../schoorbs-includes/global.functions.php';

// Session/Remote_User could be used for REST calls too, but else use 
// Session/HTTP
if ($auth['session'] != 'remote_user') { 
	$bSessionIncluded = 'true';
	/** Only use HTTP session for REST requests, so that no ID or equal have to be stored */ 
	require_once dirname(__FILE__).'/session-plugins/session_http.php';
}
/** The Schoorbs Authentication Backend */
require_once dirname(__FILE__).'/authentication/schoorbs_auth.php';

/**
 * The static class representing the REST-interface of Schoorbs
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
class SchoorbsREST
{
	/**
	 * Checks if the REST-functions exists
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param string
	 * @return bool
	 */
	public static function isValidFunction($sFunctionName)
	{
		if (empty($sFunctionName)) {
			return self::sendError('not a valid Schoorbs-REST-URL',1);
		}
		if (!ctype_alnum($sFunctionName)) {
			return self::sendError('not a valid Schoorbs-REST-URL',1);
		}		
		if (function_exists('rest_function_'.$sFunctionName)) {
			return true;
		}
		
		$sFile = dirname(__FILE__).'/rest-plugins/'.strtolower($sFunctionName).'.rest.php';
		if (file_exists($sFile)) {
			require_once $sFile;
			return function_exists('rest_function_'.$sFunctionName);
		} 
		return false;
	}
	
	/**
	 * Calls a REST function
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param string $sFunctionName
	 */
	public static function call($sFunctionName)
	{
		call_user_func('rest_function_'.$sFunctionName);
	}
	
	/**
	 * Handle an incoming REST/HTTP-Request
	 *
	 * The functionname is extracted out of $_SERVER['REDIRECT_URL']
	 *
	 * @author <uwelk@xhochy.org>
	 */
	public static function handleRequest() 
	{
		self::sendHeaders();
		$sFunctionName = unslashes($_REQUEST['call']);
		if(!self::isValidFunction($sFunctionName)) {
			return self::sendError('Function does not exist', 2);
		}
		self::call($sFunctionName);
	}
	
	/**
	 * Sends the HTTP-headers specifing an REST-Document
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function sendHeaders()
	{
		// Send no headers while we are in unit tests
		if (defined('REST_TESTING')) return;
		header('Content-type: text/xml; charset=utf-8');
	
		// REST-Answers should never be chached!
		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	}
	
	/**
	 * Return an REST-XML answer for an error and stops the script
	 * 
	 * @author Uwe L. Korn
	 * @param string $sMessage
	 * @param int $nCode
	 */
	public static function sendError($sMessage, $nCode)
	{
		$oXML = new SimpleXMLElement('<rsp stat="fail" />');
		$oError = $oXML->addChild('err');
		$oError->addAttribute('code', $nCode);
		$oError->addAttribute('message', $sMessage);
		echo $oXML->asXML();
	
		if (defined('REST_TESTING')) {
			throw new Exception('REST Exception' + $sMessage);
		} else {
			exit($nCode);
		}
	}
}
