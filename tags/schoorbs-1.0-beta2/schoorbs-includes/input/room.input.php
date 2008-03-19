<?php
/**
 * Input-Plugin 'Room'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Return a default room given a valid area; used if no room is already known.
 * This returns the first room in alphbetic order in the database.
 * This could be changed to implement something like per-user defaults.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @param int $nArea
 * @return int the ID of the default room
 */ 
function get_default_room($nArea)
{
	global $tbl_room;
	
	$room = sql_query1('SELECT id FROM '.$tbl_room.' WHERE area_id = \''
		.sql_escape_arg($nArea).'\' ORDER BY room_name LIMIT 1');

	return ($room < 0 ? 0 : $room);
} 
 
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
	        $room = unslashes($_REQUEST['room']);
	else
	    $room = get_default_room(input_Area());
	return $room;
}