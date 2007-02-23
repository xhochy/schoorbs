<?php
/**
 * Input-Plugin 'Room'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 */
 
/**
 * Catches the Room out of the REQUEST-Array and the defaults
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return array (day,month,year)
 */ 
function input_Room()
{
	if(isset($_REQUEST['room']))
	    if(empty($_REQUEST['room']))
	        $room = get_default_room(input_Area());
	    else
	        $room = $_REQUEST['room'];
	else
	    $room = get_default_room(input_Area());
	return $room;
}