<?php
/**
 * Logging backend for using syslog as a log destination
 *
 * This may only work on Unix systems
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Logging/Syslog
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
## Functions ##

/**
 * Connect to Syslog
 *
 * We will only connect to the syslog service if we have somethin to log
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function schoobsLogStart_Backend()
{
	global $_SCHOORBS;
	
	openlog('Schoorbs', LOG_ODELAY | LOG_PID, 
		$_SCHOORBS['logging']['syslog-facility']);
}

/**
 * Write a line to the syslog
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param $sLine string
 */
function schoorbsLogWriteLine_Backend($sLine)
{
	global $_SCHOORBS;
	
	syslog($_SCHOORBS['logging']['syslog-priority'], $sLine);
}
 
