<?php 
/**
 * Get user identity/password using the REMOTE_USER environment variable.
 * Both identity and password equal the value of REMOTE_USER.
 * 
 * To use this session scheme, set in config.inc.php:
 * $auth['session']  = 'remote_user';
 * $auth['type'] = 'none';
 * 
 * If you want to display a logout link, set in config.inc.php:
 * $auth['remote_user']['logout_link'] = '/logout/link.html';
 * 
 * @author Bjorn.Wiberg@its.uu.se, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs/Session/Remote-User
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * Request the user name/password
 */
function authGet()
{
  // User is expected to already be authenticated by the web server, 
  // so do nothing
}

/**
 * Returns the username, if defined
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, Bjorn.Wiberg@its.uu.se
 * @return string
 */
function getUserName()
{
	if ((!isset($_SERVER['REMOTE_USER'])) || (!is_string($_SERVER['REMOTE_USER'])) || (empty($_SERVER['REMOTE_USER']))) {
		return null;
	} else {
		return $_SERVER['REMOTE_USER'];
	}
}

/**
 * Print the logon entry on the top banner.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function PrintLogonBox()
{
	global $user_list_link, $auth, $smarty;
  
	$user = getUserName();

	if (isset($user)) { 
		$smarty->assign(array(
			'user' =>  $user,
			'user_list_link' => $user_list_link,
			'logout_link' => $auth['remote_user']['logout_link']
		));
		$smarty->display('session_remote_user_loginbox.tpl');
    } else {		fatal_error(true, '<h1>Error, REMOTE_USER was not set when it should have been</h1>');
    }
}
