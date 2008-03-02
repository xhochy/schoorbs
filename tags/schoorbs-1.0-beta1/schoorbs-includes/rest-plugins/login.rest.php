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
	if(!getAuthorised(1)){
		return SchoorbsREST::sendError('Access Denied', 4);
	}		
	
	SchoorbsREST::sendHeaders();
	SchoorbsREST::$oTPL->assign('username', getUserName());
	SchoorbsREST::$oTPL->display('login.tpl');
}
