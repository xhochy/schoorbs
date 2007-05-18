<?php
/**
 * REST-Plugin 'getRoomID'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Returns the ID of a room by a given name
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_getRoomID()
{
	global $_TPL, $tbl_room;
	
	$sName = unslashes($_REQUEST['name']);
	$sQuery = "SELECT id FROM $tbl_room WHERE room_name = '"
		.sql_escape_arg($sName).'\'';
	$nRoomID = sql_query1($sQuery);

	sendRESTHeaders();
	$_TPL->assign('roomid', $nRoomID);
	$_TPL->display('getroomid.tpl');
}