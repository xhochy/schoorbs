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
 * The login data must be submitted via HTTP-Authentication since we are using
 * either the Session/HTTP or the Session/Remote_User module during 
 * REST-requests.
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_login()
{
	// try to authenticate as a normal user
	if(!getAuthorised(1)){
		return SchoorbsREST::sendError('Access Denied', 4);
	}		
	
	// send the username as a verification reply
	SchoorbsREST::$oTPL->assign('username', getUserName());
	SchoorbsREST::$oTPL->display('login.tpl');
}
