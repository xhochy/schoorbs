<?php
/**
 * Session management scheme that relies on OmniHttpd security for user 
 * authentication. THIS is suitable for few users because we have to create all
 * users connecting to Schoorbs, since they will have to login.
 *
 * To use this authentication scheme set the following things :
 * - Edit your virtual server hosting Schoorbs.
 * - Select security tab.
 * - IF not yet set, choose "User and Directory" security type. 
 * - Select "Users and groups" tab. 
 * - Here, select "New User" and create as many users (Username/passwords) as 
 *   you have users using Schoorbs. 
 * - Select "New Group".
 * - Type "Schoorbs" as group name and add all users you just created to this group.
 * - Now select "Access Control list" tab. 
 * - Select New. ENTER the relative path to Schoorbs. FOR example, if you created 
 *   the Schoorbs folder on the root web folder, you should type /Schoorbs/. 
 * - Now go to "user permission" tab, select " * ",
 * - select Properties", and type Schoorbs (remove the star) and select "Is group".
 *
 * That's all ! Confirm all windows. Now it is the web server that authenticate
 * each user. 
 *
 * 
 * in config.inc.php:
 *
 * $auth["type"]    = "none";
 * $auth["session"] = "omni";
 *
 * Then, you may configure admin users:
 *
 * $auth["admin"][] = "user1";
 * $auth["admin"][] = "user2";
 * 
 * @author jberanek
 * @package Schoorbs/Session/Omni
 */
 
/* getAuth()
 * 
 *  No need to prompt for a name - this is done by the server.
 */
function authGet() { }

function getUserName()
{
	global $REMOTE_USER;
	return $REMOTE_USER;
}

?>
