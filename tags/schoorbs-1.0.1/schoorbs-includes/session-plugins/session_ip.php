<?php
/**
 * Session management scheme that uses IP addresses to identify users.
 * Anyone who can access the server can make bookings.
 * Administrators are also identified by their IP address.
 *
 * To use this authentication scheme set the following
 * things in config.inc.php:
 *
 * $auth["type"]    = "none";
 * $auth["session"] = "ip";
 *
 * Then, you may configure admin users:
 *
 * $auth["admin"][] = "127.0.0.1"; // Local host = the server you're running on
 * $auth["admin"][] = "192.168.0.1";
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs/Session/IP
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * No need to prompt for a name - ip address always there
 */
function authGet() { }

/**
 * UserName == IP
 */
function getUserName()
{
	return $_SERVER['REMOTE_ADDR'];
}
