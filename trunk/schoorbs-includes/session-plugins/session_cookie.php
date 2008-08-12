<?php
/**
 * Manage sessions via cookies stored in the client browser.
 * 
 * URL arguments   
 * UserName		The user name
 * UserPassword	His password
 * TargetURL		Where we were going before login.
 * 
 * To use this session mechanism, set in config.inc.php:
 * $auth["session"]  = "cookie";
 * 
 * @author JFL, jberanek
 * @package Schoorbs/Session/Cookie
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

if (isset($cookie_path_override)) {
    $cookie_path = $cookie_path_override;
} else {
    $cookie_path = $_SERVER['PHP_SELF'];
    $cookie_path = ereg_replace('[^/]*$', '', $cookie_path);
}

/*
  Target of the form with sets the URL argument "Action=SetName".
  Will eventually return to URL argument "TargetURL=whatever".
*/
if (isset($Action) && ($Action == "SetName"))
{
    // Avoid errors with error level ALL
    if ( !isset( $NewUserName ) )
    {
    	$NewUserName = "";
    }
    /* First make sure the password is valid */
    if ($NewUserName == "") {
        // Delete cookie

        setcookie("UserName", '', time()-42000, $cookie_path);
    } else {
        $NewUserName = unslashes($NewUserName);
        $NewPassword = unslashes($NewPassword);

        if (!authValidateUser($NewUserName, $NewUserPassword))
        {
            print_header();
            echo "<p>".get_vocab('unknown_user')."</p>\n";
            printLoginForm($TargetURL);
            exit();
        }
        else
        {
            $UserName     = $NewUserName;
            $UserPassword = $NewUserPassword;
        }

        setcookie("UserName", $UserName, time()+(60*60*24*30), $cookie_path);
    }
    header ("Location: $TargetURL"); /* Redirect browser to initial page */
    /* Note HTTP 1.1 mandates an absolute URL. Most modern browsers support relative URLs,
        which allows to work around problems with DNS inconsistencies in the server name.
        Anyway, if the browser cannot redirect automatically, the manual link below will work. */
    print_header();
    echo "<br />\n";
    echo "<p>Please click <a href=\"$TargetURL\">here</a> if you're not redirected automatically to the page you requested.</p>\n";
    echo "</body>\n";
    echo "</html>\n";
    exit();
}

/**
 * Display the login form. Used by two routines below.
 * Will eventually return to $TargetURL.
 */
function printLoginForm($TargetURL)
{
?>
<p>
  <?php echo get_vocab("please_login") ?>
</p>
<form method="post" action="<?php echo basename($_SERVER['PHP_SELF']) ?>">
  <table>
    <tr>
      <td align="right"><?php echo get_vocab("user_name") ?></td>
      <td><input type="text" name="NewUserName" /></td>
    </tr>
    <tr>
      <td align="right"><?php echo get_vocab("user_password") ?></td>
      <td><input type="password" name="NewUserPassword" /></td>
    </tr>
  </table>
  <input type="hidden" name="TargetURL" value="<?php echo $TargetURL ?>" /> <br />
  <input type="hidden" name="Action" value="SetName" /> <br />
  <input type="submit" value="<?php echo get_vocab('login') ?> " /> <br />
</form>
</body>
</html>
<?php
}

/*
  Target of the form with sets the URL argument "Action=QueryName".
  Will eventually return to URL argument "TargetURL=whatever".
*/
if (isset($Action) && ($Action == "QueryName"))
{
    print_header();
    printLoginForm($TargetURL);
    exit();
}

/* authGet()
 * 
 * Request the user name/password
 * 
 * Returns: Nothing
 */
function authGet()
{
    global $PHP_SELF, $QUERY_STRING;

    print_header();

    echo "<p>".get_vocab("norights")."</p>\n";

    $TargetURL = basename($PHP_SELF);
    if (isset($QUERY_STRING)) $TargetURL = $TargetURL . "?" . $QUERY_STRING;
    printLoginForm($TargetURL);

    exit();
}

function getUserName()
{
    if (!empty($_COOKIE) && isset($_COOKIE["UserName"]))
    {
	return $_COOKIE["UserName"];
    }
    else
    {
        global $HTTP_COOKIE_VARS;
	if (!empty($HTTP_COOKIE_VARS) && isset($HTTP_COOKIE_VARS["UserName"]))
	    return $HTTP_COOKIE_VARS["UserName"];
    }
}

// Print the logon entry on the top banner.
function PrintLogonBox()
{
    global $PHP_SELF, $QUERY_STRING, $user_list_link, $user_link, $day, $month;
    global $year, $auth;

    $TargetURL = basename($PHP_SELF);
    if (isset($url_base) && ($url_base != "")) $TargetURL = $url_base . '/' . $TargetURL;
    if (isset($QUERY_STRING)) $TargetURL = $TargetURL . "?" . $QUERY_STRING;
    $user=getUserName();
    if (isset($user))
    { 
    	?>
		<div id="schoorbs-loginbox-username"><?php echo get_vocab('you_are').' '.$user ?></div>
		<form method="post" action="administration.php">
			<div id="schoorbs-loginbox-button">
		    		<input type="hidden" name="TargetURL" value="<?php echo $TargetURL ?>" />
				<input type="hidden" name="Action" value="SetName" />
				<input type="hidden" name="UserName" value="" />
		    		<input type="hidden" name="UserPassword" value="" />
				<input type="submit" value=" <?php echo get_vocab('logoff') ?> " />
			</div>
		</form>
		<?php if (isset($user_list_link)) { ?>
			<a href="<?php echo $user_list_link"><?php echo get_vocab('user_list'); ?></a>
		<?php } ?>
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
			<a href="<?php echo $user_list_link ?>"><?php echo get_vocab('user_list'); ?></a>
		<?php } ?>
	<?php
	}
}
