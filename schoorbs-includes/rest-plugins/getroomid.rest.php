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
 * Returns the ID of a room by a given name.
 *
 * The name of the room must be supplied via the GET/POST-parameter 'name'
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_getRoomID()
{
	global $tbl_room;
	
	// Get the parameter 'name' from the HTTP-Request
	$sName = unslashes($_REQUEST['name']);
	// Search for a fitting room in the database
	$oRoom = Room::getByName($sName);
	
	// If we haven't found a fitting room, return an error. This case is either
	// identified by an unset $nRoomID or an $nRoomID with the value of int(-1).
	if ($oRoom == null) {
		return SchoorbsREST::sendError('Couldn\'t find a fitting room.', 5);
	}

	// Return the room id
	$oXML = new SimpleXMLElement('<rsp stat="ok" />');
	$oXML->addChild('room_id', $oRoom->getId());
	echo $oXML->asXML();
}
