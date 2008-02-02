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


require_once 'config.inc.php';
require_once 'schoorbs-includes/rest.functions.php';
require_once "schoorbs-includes/database/$dbsys.php";
require_once 'schoorbs-includes/input.functions.php';
require_once 'schoorbs-includes/time.functions.php';

## Init ##

InitRESTSmarty();

## Main ##

$sURL = $_SERVER['REDIRECT_URL'];
$sFunctionName = getRESTFunctionName($sURL);
if(!isValidRESTFunction($sFunctionName))
	sendRESTError('Function does not exist', 2);
	
callRESTFunction($sFunctionName);
