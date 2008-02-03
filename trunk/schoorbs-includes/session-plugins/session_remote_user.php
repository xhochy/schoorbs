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
  // User is expected to already be authenticated by the web server, so do nothing
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
	global $user_list_link, $auth;
  
	$user = getUserName();

	if (isset($user)) {
		// words 'you are xxxx' becomes a link to the
        // report page with only entries created by xxx. Past entries are not
        // displayed but this can be changed
       	$search_string = "report.php?From_day=$day&amp;From_month=$month&amp;".
          "From_year=$year&amp;To_day=1&amp;To_month=12&amp;To_year=2030&amp;areamatch=&amp;".
          "roommatch=&amp;namematch=&amp;descrmatch=&amp;summarize=1&amp;sortby=r&amp;display=d&amp;".
          "sumby=d&amp;creatormatch=$user"; ?>

    <td class="banner" style="background-color:#c0e0ff; text-align:center;">
      <a name="logonBox" href="<?php echo "$search_string\" title=\""
         . get_vocab('show_my_entries') . "\">" . get_vocab('you_are')." "
         .$user ?></a><br />
<?php if (isset($user_list_link)) print "	  <br />\n	  " .
	    "<a href='$user_list_link'>" . get_vocab('user_list') . "</a><br />\n" ;
?>

<?php
// Retrieve logout link from configuration, if specified
if (isset($auth['remote_user']['logout_link']) && is_string($auth['remote_user']['logout_link']) && (!empty($auth['remote_user']['logout_link']))) {
  print '<a href="' . $auth['remote_user']['logout_link'] .'">' . get_vocab('logoff') . "</a><br />\n";
}
?>

    </td>
<?php
    }
    else
    {
?>
    </table>
    <h1>Error, REMOTE_USER was not set when it should have been</h1>
<?php
    exit;
    }
}
