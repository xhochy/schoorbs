<?php
/**
 * Input-Plugin 'PView'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 */
 
/**
 * Check if the page should be shown for printing
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return int
 */ 
function input_PView()
{
	if (!isset($_REQUEST['pview'])) 
		return 0;
	else
	    return intval(unslashes($_REQUEST['pview']));
}