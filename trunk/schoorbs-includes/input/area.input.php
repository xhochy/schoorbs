<?php
/**
 * Input-Plugin 'Area'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 */
 
/**
 * Catches the Area out of the REQUEST-Array and the defaults
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return array (day,month,year)
 */ 
function input_Area()
{
	if(isset($_REQUEST['area']))
	    if(empty($_REQUEST['area']))
	        $area = get_default_area();
	    else
	        $area = $_REQUEST['area'];
	else
	    $area = get_default_area();
	return $area;
}