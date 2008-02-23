<?php
/**
 * Authenticate users from a list in config.inc.php
 * 
 * To use this authentication scheme, set in config.inc.php:
 * $auth["type"]  = "config";
 * Then for each user, add an entry formatted as:
 * $auth["user"]["username"] == "userpassword";
 * 
 * @todo Make passwords md5/sha1 crypted
 * @author jberanek, JFL
 * @package Schoorbs/Auth/Config
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */ 

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
	global $auth;

	// Check if we do not have a username/password
	if(!isset($user) || !isset($pass) || strlen($pass) == 0) return false;
	// Check if	the user exists
	if(isset($auth['user'][$user]) && ($auth['user'][$user] == $pass)) return true;
	// Check if the user exists in lowercase type
	$user = strtolower($user);
	if(isset($auth['user'][$user]) && ($auth['user'][$user] == $pass)) return true;
	// User unknown or password invalid
	return false;		
}

/* authGetUserLevel($user)
 * 
 * Determines the users access level
 * 
 * $user - The user name
 *
 * Returns:
 *   The users access level
 */
function authGetUserLevel($user, $lev1_admin)
{
   // User not logged in, user level '0'
   if(!isset($user)) return 0;

   // Check if the user is can modify
   for ($i = 0; isset($lev1_admin[$i]); $i++) {
      if(strcasecmp($user, $lev1_admin[$i]) == 0) return 2;
   }

   // Everybody else is access level '1'
   return 1;
}
