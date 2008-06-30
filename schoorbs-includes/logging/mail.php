<?php
/**
 * Logging backend for using E-Mail as a log destination
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Logging/Mail
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

require_once dirname(__FILE__).'/phpmailer/class.phpmailer.php';
require_once dirname(__FILE__).'/phpmailer/language/phpmailer.lang-en.php';
 
## Functions ##

/**
 * We don't do any initialization in general, every mail is created on his own
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function schoobsLogStart_Backend()
{
	// nothing
}

/**
 * Send a line via mail
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param $sLine string
 */
function schoorbsLogWriteLine_Backend($sLine)
{
	global $_SCHOORBS;
	
	$oMail = new phpmailer();
	$oMail->IsSMTP();
	$oMail->SetLanguage('en', dirname(__FILE__.'/phpmailer/'));  
     	$oMail->Host = $_SCHOORBS['logging']['mail-host'];
	$oMail->SMTPAuth = true;
	$oMail->Username = $_SCHOORBS['logging']['mail-username'];
	$oMail->Password = $_SCHOORBS['logging']['mail-password'];
	$oMail->Sender = $_SCHOORBS['logging']['mail-from'];
	$oMail->From = $_SCHOORBS['logging']['mail-from'];
	$oMail->FromName = 'Schoorbs';
	foreach ($_SCHOORBS['logging']['mail-to'] as $sRecipent) {
		$oMail->AddAddress($sRecipent);
	}
	$oMail->Subject = $_SCHOORBS['logging']['mail-subject'];
	$oMail->Body = $sLine;
	$oMail->Send();
}
