<?php
/**
 * Authenticate users from a table in the MRBS database.
 * 
 * To use this authentication scheme, set in config.inc.php:
 * $auth["type"]  = "db";
 * 
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs/Auth/DB
 */

/* session_php.inc and session_cookie.inc will add a link to the user list
    in the logon box, if the value $user_list_link is set. */
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
   return sql_query1("select count(*) from $tbl_users where name='$user' and password='$pass';");
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

?>