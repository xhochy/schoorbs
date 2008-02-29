<?php
/**
 * General things that need be done, when browsing Schoorbs on Web
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The input getting & validating functions */
require_once 'input.functions.php';
 
## Var Inits ##

$pview = input_PView();

// ensure that $morningstarts_minutes defaults to zero if not set
if(empty($morningstarts_minutes)) {
	$morningstarts_minutes = 0;
}

$format = "Gi";
if ($enable_periods) {
	$format = "i";
	$resolution = 60;
	$morningstarts = 12;
	$morningstarts_minutes = 0;
	$eveningends = 12;
	$eveningends_minutes = count($periods)-1;
}
