<?php
/**
 * Get user identity using the HTTP basic authentication.
 * 
 * To use this session scheme, set in config.inc.php:
 * $auth["session"]  = "http";
 * 
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs/Session/HTTP
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */ 

/** 
 * Request the user name/password
 * 
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 */
function authGet()
{
    global $auth;
    
    if(!defined('SCHOORBS_NOGUI')) {
	    header("WWW-Authenticate: Basic realm=\"$auth[realm]\"");
    	header("HTTP/1.0 401 Unauthorized");
    }
}

function getAuthPassword()
{
	if (isset($_SERVER['PHP_AUTH_PW'])) {
        $pw = $_SERVER['PHP_AUTH_PW'];
        if (get_magic_quotes_gpc()) {
            $pw = stripslashes($pw);
        }
        return $pw;
    } else {
        return null;
    }
}

/**
 * return the Username, if pw is valid
 * 
 * @return string
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 */
function getUserName()
{
    if (isset($_SERVER['PHP_AUTH_USER']))
    {
        $user = $_SERVER['PHP_AUTH_USER'];
        if (get_magic_quotes_gpc()) $user = stripslashes($user);
        
        if (authValidateUser($user,getAuthPassword()))
            return $user;
        else
            return null;
    } else return null;
}

// Print the logon entry on the top banner.
function PrintLogonBox()
{
	global $user_list_link;
  
	$user = getUserName();

	if (isset($user))
	{ 
		?>
		<div id="schoorbs-loginbox-username"><?php echo get_vocab('you_are').' '.$user ?></div>
		<?php if (isset($user_list_link)) { ?>
			<div id="schoorbs-loginbox-button"><a href="<?php echo $user_list_link; ?>"><?php echo get_vocab('user_list'); ?></a>
		<?php }; ?>
	<?php } else { ?>
	<div id="schoorbs-loginbox-username"><?php echo get_vocab('unknown_user'); ?></div>
	        <form method="post" action="administration.php">
	        	<div id="schoorbs-loginbox-button">
			    <input type="hidden" name="TargetURL" value="<?php echo $TargetURL ?>" />
			    <input type="hidden" name="Action" value="QueryName" />
			    <input type="submit" value=" <?php echo get_vocab('login') ?> " />
			</div>
		</form>
		<?php if (isset($user_list_link)) { ?>
			<a href="<?php echo $user_list_link; ?>"><?php echo get_vocab('user_list'); ?></a>
		<?php } ?>
	<?php
	}
}
