<?php
/**
 * The main file for authentication issues.
 * 
 * @author jberanek, JFL, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Auth
 */

/* ~~JFL 2003/11/12 By default, use the http session mechanism */
if (!isset($auth['session'])) $auth['session'] = 'http';

// include the authentification wrappers
require_once "auth/auth_{$auth['type']}.php";

if(isset($bSessionIncluded)) {
	if(!$bSessionIncluded)
		if (isset($auth['session']))
			require_once "session/session_{$auth['session']}.php";
}			
else
	if (isset($auth['session'])) 
		require_once "session/session_{$auth['session']}.php";

/* getAuthorised($user, $pass, $level)
 * 
 * Check to see if the user name/password is valid
 * 
 * $user  - The user name
 * $pass  - The users password
 * $level - The access level required
 * 
 * Returns:
 *   0        - The user does not have the required access
 *   non-zero - The user has the required access
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

/* getWritable($creator, $user)
 * 
 * Determines if a user is able to modify an entry
 *
 * $creator - The creator of the entry
 * $user    - Who wants to modify it
 *
 * Returns:
 *   0        - The user does not have the required access
 *   non-zero - The user has the required access
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
    list($day, $month, $year) = input_DayMonthYear();
    print_header($day, $month, $year, input_Area());
?>
  <h1><?php echo get_vocab("accessdenied")?></h1>
  <p>
   <?php echo get_vocab("norights")?>
  </p>
  <p>
   <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><?php echo get_vocab("returnprev"); ?></a>
  </p>
 </body>
</html>
<?php
	exit();
}