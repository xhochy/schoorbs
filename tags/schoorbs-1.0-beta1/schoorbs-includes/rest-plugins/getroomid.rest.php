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
	global $tbl_room;
	
	$sName = unslashes($_REQUEST['name']);
	$nRoomID = sql_query1(sprintf(
		'SELECT id FROM %s WHERE room_name = \'%s\'',
		$tbl_room, sql_escape_arg($sName)
	));
	
	if (!isset($nRoomID) || ($nRoomID == -1)) {
		return SchoorbsREST::sendError('Couldn\'t find a fitting room.', -1);
	}

	SchoorbsREST::sendHeaders();
	SchoorbsREST::$oTPL->assign('room_id', $nRoomID);
	SchoorbsREST::$oTPL->display('getroomid.tpl');
}