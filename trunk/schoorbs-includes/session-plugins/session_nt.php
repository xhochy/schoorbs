<?php
/**
 * Session management scheme that uses Windows NT domain users and Internet 
 * Information Server as the source for user authentication.
 *
 * To use this authentication scheme set the following
 * things in config.inc.php:
 *
 *      $auth["type"]    = "none";
 *      $auth["session"] = "nt";
 *
 * Then, you may configure admin users:
 *
 * $auth["admin"][] = "nt_username1";
 * $auth["admin"][] = "nt_username2";
 * 
 * @author jberanek
 * @package Schoorbs/Session/NT
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/** 
 * Request that the username/password be given.
 *
 * For this scheme no need to prompt for a name - NT User always there.
 */
function authGet() { }

function getUserName()
{
	global $AUTH_USER;
	$AUTH_USER = get_current_user();  
	return $AUTH_USER;

}

?>