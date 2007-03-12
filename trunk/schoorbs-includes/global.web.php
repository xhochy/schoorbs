<?php
/**
 * General things that need be done, when browsing Schoorbs on Web
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

require_once 'input.functions.php';
 
## Var Inits ##

$pview = input_PView();
// ensure that $morningstarts_minutes defaults to zero if not set
if(empty($morningstarts_minutes))
	$morningstarts_minutes = 0;