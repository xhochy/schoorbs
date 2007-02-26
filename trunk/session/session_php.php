<?php
/**
 * Use PHP built-in sessions handling
 * 
 * To use this authentication scheme, set in config.inc.php:               
 * 		$auth["session"]  = "php";
 * 
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs/Session/PHP
 */
/**
 * The main function of the PHP-Sessions, is started by including this file
 * 
 * @author JFL, jberanek, Uwe L. Korn <uwelk@xhochy.org>
 */
function session_php_main()
{
	$cookie_path = $_SERVER['PHP_SELF'];
	$cookie_path = ereg_replace('[^/]*$', '', $cookie_path);
	session_set_cookie_params(0, $cookie_path);
	session_start();
	
	/*
	  Target of the form with sets the URL argument "Action=SetName".
	  Will eventually return to URL argument "TargetURL=whatever".
	*/
	if (isset($_REQUEST['Action']) && ($_REQUEST['Action'] == "SetName"))
	{
		/* First make sure the password is valid */
	    if (empty($_REQUEST['NewUserName']))	
	        // Unset the session variables
	        $_SESSION = array();
	    else {
	        $NewUserName = unslashes($_REQUEST['NewUserName']);
	        $NewUserPassword = unslashes($_REQUEST['NewUserPassword']);
	        if (!authValidateUser($NewUserName, $NewUserPassword)) {
	            print_header(0, 0, 0, 0);
	            echo "<p>".get_vocab('unknown_user')."</p>\n";
	            printLoginForm($_REQUEST['TargetURL']);
	            exit();
	        }
	
            $_SESSION["UserName"] = $NewUserName;
	    }
	    
	    
	    header ('Location: '.$_REQUEST['TargetURL']); /* Redirect browser to initial page */
	    /* Note HTTP 1.1 mandates an absolute URL. Most modern browsers support relative URLs,
	        which allows to work around problems with DNS inconsistencies in the server name.
	        Anyway, if the browser cannot redirect automatically, the manual link below will work. */
	    print_header(0, 0, 0, 0);
	    echo "<br />\n";
	    echo "<p>Please click <a href=\"$TargetURL\">here</a> if you're not redirected automatically to the page you requested.</p>\n";
	    echo "</body>\n";
	    echo "</html>\n";
	    exit();
	}
	
	/*
	  Target of the form with sets the URL argument "Action=QueryName".
	  Will eventually return to URL argument "TargetURL=whatever".
	*/
	if (isset($_REQUEST['Action']) && ($_REQUEST['Action'] == "QueryName"))
	{
	    print_header(0, 0, 0, 0);
	    printLoginForm($_REQUEST['TargetURL']);
	    exit();
	}
} 



/**
 * Display the login form. Used by two routines below. Will eventually return to $TargetURL.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param string $TargetURL
 */
function printLoginForm($TargetURL)
{
		Global $smarty;
		$smarty->assign('action_url',basename($_SERVER['PHP_SELF']));
		$smarty->assign('TargetURL',$TargetURL);
		$smarty->display('session_php_loginform.tpl');
}



/* authGet()
 * 
 * Request the user name/password
 * 
 * Returns: Nothing
 */
function authGet()
{
    print_header(0, 0, 0, 0);

    echo "<p>".get_vocab("norights")."</p>\n";

    $TargetURL = basename($_SERVER['PHP_SELF']);
    if (isset($_SERVER['QUERY_STRING'])) $TargetURL = $TargetURL . "?" . $_SERVER['QUERY_STRING'];
    printLoginForm($TargetURL);

    exit();
}

/**
 * Returns the username, if defined
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek, JFL
 * @return string
 */
function getUserName()
{
    if (isset($_SESSION) && isset($_SESSION["UserName"]) && ($_SESSION["UserName"] != ""))
    {
        return $_SESSION["UserName"];
    }
}

/**
 * Print the logon entry on the top banner.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function PrintLogonBox()
{
	global $user_list_link, $user_link, $day, $month;
    global $year, $auth;
    Global $smarty;

    $TargetURL = basename($_SERVER['PHP_SELF']);
    if (isset($url_base) && ($url_base != "")) 
        $TargetURL = $url_base . '/' . $TargetURL;
    if (isset($_SERVER['QUERY_STRING'])) 
        $TargetURL = $TargetURL . "?" . $_SERVER['QUERY_STRING'];
    
    $user = getUserName();
    
    $smarty->assign('TargetURL',$TargetURL);
    $smarty->assign('user_list_link',$user_list_link);
    
    if(isset($user) && !empty($user))
    {
        // words 'you are xxxx' becomes a link to the
        // report page with only entries created by xxx. Past entries are not
        // displayed but this can be changed
       	$search_string = "report.php?From_day=$day&From_month=$month&".
          "From_year=$year&To_day=1&To_month=12&To_year=2030&areamatch=&".
          "roommatch=&namematch=&descrmatch=&summarize=1&sortby=r&display=d&".
          "sumby=d&creatormatch=$user";
        $smarty->assign('search_string',$search_string);
    }
    
    $smarty->assign('user',$user);
    $smarty->display('session_php_loginbox.tpl');
}

## Main ##
session_php_main();
?>
