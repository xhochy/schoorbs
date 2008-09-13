<?php
/**
 * Configuration of the logging system
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Logging
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
## Configuration ##

/** Check that we have a suitable Configuration array */
if (!isset($_SCHOORBS)) $_SCHOORBS = array();

/** Configuration of the logging system */
$_SCHOORBS['logging'] = array(
    'active' => false,
	'backend' => 'mail',
	'syslog-priority' => LOG_INFO,
	'syslog-facility' => LOG_USER,
	'mail-host' => 'mail.example.org',
	'mail-username' => 'mailuser',
	'mail-password' => 'mailpass',
	'mail-from' => 'schoorbs@example.org',
	'mail-to' => array('recipent1@example.org'),
	'mail-subject' => 'Schoorbs entry change'
);

