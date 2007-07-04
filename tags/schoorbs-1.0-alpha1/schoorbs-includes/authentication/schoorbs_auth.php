<?php
/**
 * The main file for authentication issues.
 * 
 * @author jberanek, JFL, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Auth
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/* ~~JFL 2003/11/12 By default, use the http session mechanism */
if (!isset($auth['session'])) $auth['session'] = 'http';

// include the authentification wrappers
require_once "auth_{$auth['type']}.php";

if(isset($bSessionIncluded)) {
	if(!$bSessionIncluded)
		if (isset($auth['session']))
			require_once dirname(__FILE__)."/../session-plugins/session_{$auth['session']}.php";
}			
else
	if (isset($auth['session'])) 
		require_once dirname(__FILE__)."/../session-plugins/session_{$auth['session']}.php";

/**
 * Check to see if the user name/password is valid
 * 
 * @param string $user The user name
 * @param string $pass The users password
 * @param int The access level required
 * @return int If != 0 The user has the required access
 * @author jberanek, JFL, Uwe L. Korn <uwelk@xhochy.org>
 */
function getAuthorised($level)
{
    global $auth;

    $user = getUserName();
    if(isset($user) == FALSE) {
        authGet();
        return 0;
    }

    return authGetUserLevel($user, $auth["admin"]) >= $level;
}

/**
 * Determines if a user is able to modify an entry
 * @author jberanek, JFL, Uwe L. Korn <uwelk@xhochy.org>
 * @param string $creator
 * @param string $user
 * @return int If != 0 The user has the required access 
 */
function getWritable($creator, $user)
{
    global $auth;

    // Always allowed to modify your own stuff
    if(strcasecmp($creator, $user) == 0)
        return 1;

    if(authGetUserLevel($user, $auth["admin"]) >= 2)
        return 1;

    // Unathorised access
    return 0;
}

/**
 * Displays an appropate message when access has been denied
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 */
function showAccessDenied()
{
	global $smarty;

    list($day, $month, $year) = input_DayMonthYear();
    print_header($day, $month, $year, input_Area());
    $smarty->display('accessdenied.tpl');
	exit();
}