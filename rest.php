<?php
/**
 * Handles incoming REST-requests
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The Configuration file */
require_once 'config.inc.php';
/** The REST functions/plugins */
require_once 'schoorbs-includes/rest.functions.php';
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** The input checking/validation functions */
require_once 'schoorbs-includes/input.functions.php';
/** The time related functions */
require_once 'schoorbs-includes/time.functions.php';

## Init ##

InitRESTSmarty();

## Main ##

$sURL = $_SERVER['REDIRECT_URL'];
$sFunctionName = getRESTFunctionName($sURL);
if(!isValidRESTFunction($sFunctionName)) {
	sendRESTError('Function does not exist', 2);
}
	
callRESTFunction($sFunctionName);
