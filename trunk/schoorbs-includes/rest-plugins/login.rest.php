<?php
/**
 * REST-Plugin 'login'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Just to check if your login Data is correct
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_login()
{
	global $_TPL;
	
	if(!getAuthorised(1)){
		sendRESTError('Access Denied', 4);
	}		
	sendRESTHeaders();
	$_TPL->assign('username',getUserName());
	$_TPL->display('login.tpl');
}
