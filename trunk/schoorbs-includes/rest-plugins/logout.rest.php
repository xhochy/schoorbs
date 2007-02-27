<?php
/**
 * REST-Plugin 'logout'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 */
 
/**
 * Log out of the system
 * does not need to be called at the moment, but maybe later
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_logout()
{
	global $_TPL;
	
	sendRESTHeaders();
	$_TPL->display('logout.tpl');
}