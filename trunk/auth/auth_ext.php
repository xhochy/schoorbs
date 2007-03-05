<?php
/**
 * Authentication scheme that uses an external script as the source
 * for user authentication.
 * 
 * To use this authentication scheme set the following
 * things in config.inc.php:
 * $auth["realm"]  = "Schoorbs";    # Or any other string
 * $auth["type"]   = "ext";
 * $auth["prog"]   = "authenticationprogram";  # The full path to the external script
 * $auth["params"] = "#USERNAME# #PASSWORD# other-params" # Parameters to pass
 *     to the script, #USERNAME# and #PASSWORD# will be expanded to the values typed by the user.
 * 
 * Then, you may configure admin users:
 * $auth["admin"][] = "username1";
 * $auth["admin"][] = "username2";
 * 
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs/Auth/Ext
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
	if(!isset($user) || !isset($pass))
		return 0;
	
	// ATTENTION: Sending passwords by commandline arg is not secrue
	// try 'ps -A -F' while running the script may show the password 
	// to any loged in system user
	
	// Generate the command line
	$cmd = $auth["prog"] . ' ' . $auth["params"];
	$cmd = preg_replace('/#USERNAME#/',escapeshellarg($user),$cmd);
	$cmd = preg_replace('/#PASSWORD#/',escapeshellarg($pass),$cmd);
	
	// Run the program
	exec($cmd, $output, $ret);
	
	// If it succeeded, return success
	if($ret == 0)
		return 1;
	
	// return failure
	return 0;
}

/**
 * Determines the users access level
 * 
 * @param string $user
 * @param array $lev1_admin
 * @return int The user's access level
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek, JFL 
 */
function authGetUserLevel($user, $lev1_admin)
{
	// User not logged in, user level '0'
	if(!isset($user))
		return 0;
	
	// Check if the user is can modify
	for($i = 0; $lev1_admin[$i]; $i++)
	{
		if(strcasecmp($user, $lev1_admin[$i]) == 0)
			return 2;
	}
	
	// Everybody else is access level '1'
	return 1;
}

?>
