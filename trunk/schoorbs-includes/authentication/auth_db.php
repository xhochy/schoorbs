<?php
/**
 * Authenticate users from a table in the MRBS database.
 * 
 * To use this authentication scheme, set in config.inc.php:
 * $auth["type"]  = "db";
 * 
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs/Auth/DB
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * Include the Database wrapper (again).
 * This Authenticationmodule needs the database for all pages.
 */
require_once dirname(__FILE__).'/../database/'.$dbsys.'.php';

/**
 * session_php.inc and session_cookie.inc will add a link to the user list
 * in the logon box, if the value $user_list_link is set.
 */
$user_list_link = "edit_users.php";

/**
 * Checks if the specified username/password pair are valid
 * 
 * @param string $user
 * @param string $pass
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @return int 0 => The pair are invalid or do not exist | non-zero => The pair are valid 
 */
function authValidateUser($user, $pass)
{
   global $tbl_users;

   $user = strtolower($user);
   $user = sql_escape_arg($user);
   $pass = md5($pass);
   return sql_query1("SELECT COUNT(*) FROM $tbl_users WHERE name='"
       .sql_escape_arg($user)."' AND password='".sql_escape_arg($user)."';");
}

/**
 * Determines the users access level
 *
 * @author JFL, jberanek 
 * @param string $user The user name
 * @param array $lev1_admin
 * @return int The user acces level
 */
function authGetUserLevel($user, $lev1_admin)
{
   // User not logged in, user level '0'
   if(!isset($user))
      return 0;

   // Check if the user is can modify
   for($i = 0; isset($lev1_admin[$i]); $i++)
   {
		if(strcasecmp($user, $lev1_admin[$i]) == 0)
			return 2;
   }

   // Everybody else is access level '1'
   return 1;
}