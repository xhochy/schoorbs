<?php
/**
 * REST-Plugin 'checkFree'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/** Database helper functions */
require_once dirname(__FILE__).'/../database/schoorbs_sql.php';
 
/**
 * Make several bookings at a given time on several days
 * If there is a conflicting booking already only the one which conflicts will 
 * not be booked, all other will
 *
 * Equal to makeBooking, but if there is a conflicting booking, it will be 
 * deleted.
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
function rest_function_replaceBooking()
{
	global $enable_periods;
	
	if(!getAuthorised(2)){
        return SchoorbsREST::sendError('Access Denied', 4);         
    }

	if ($enable_periods) {
		if (is_array($_REQUEST['day'])) {
			$aDays = array();
			
			for($i = 0; $i < count($_REQUEST['day']); $i++) {
				$nMonth = intval($_REQUEST['month'][$i]);
				$nDay = intval($_REQUEST['day'][$i]);
				$nYear = intval($_REQUEST['year'][$i]);
				if (!checkdate($nMonth, $nDay, $nYear)) {
					return SchoorbsREST::sendError('Given date is invalid!', -1);
				}
				$aDays[] = array('day' => $nDay, 'month' => $nMonth, 
					'year' => $nYear);
			}
		} else {
			return SchoorbsREST::sendError('Only date arrays are supported at the moment!', -1);
		}
	} else {
		die('Only periods are supported at the moment!');
	}
	
	// We don't use input_Room(); since if there is none defined, we want an error
	// In the GUI we use default values to redirect the user, who verifies that
	// he is in the room he wants by reading the heading, here we expect an
	// application which might have forgotten to set a room, but is unable to
	// verify if the request is sent to the correct room
	if (isset($_REQUEST['room'])) {
		$nRoomID = intval($_REQUEST['room']);
	} else {
		return SchoorbsREST::sendError('Room not set!', -1);
	}
	if (isset($_REQUEST['period'])) {
		$nPeriodID = intval($_REQUEST['period']);
	} else {
		return SchoorbsREST::sendError('Period not set!', -1);
	}
	if (isset($_REQUEST['name'])) {
		$sName = unslashes($_REQUEST['name']);
		
		if (empty($sName)) {
			return SchoorbsREST::sendError('Name is empty!', -1);
		}
	} else {
		return SchoorbsREST::sendError('Name not set!', -1);
	}
	// Description could be empty, but the client should specify that explicitly
	// Given a not-set description could be a failure by the client, there still
	// might be one.
	if (isset($_REQUEST['description'])) {
		$sDescription = unslashes($_REQUEST['description']);
	} else {
		return SchoorbsREST::sendError('Description not set!', -1);
	}
	if (isset($_REQUEST['type'])) {
		$sType = unslashes($_REQUEST['type']);
		
		if (empty($sType)) {
			return SchoorbsREST::sendError('Type is empty!', -1);
		}
	} else {
		return SchoorbsREST::sendError('Type not set!', -1);
	}	
	
	// Support Bookings for different usernames since the user doing this 
	// request must be an admin
	if (isset($_REQUEST['user'])) {
		$sUsername = unslashes($_REQUEST['user']);
		
		if (empty($sUsername)) {
			$sUsername = getUserName();
		}
	} else {
		$sUsername = getUserName();
	}

	foreach ($aDays as $aDay) {
		// make time - only periods supported at the moment
		$nStartTime = mktime(12, $nPeriodID, 0, $aDay['month'],
			$aDay['day'], $aDay['year']
		);
		$nEndTime = $nStartTime + 60;
	
		if (($sError = schoorbsCheckFree($nRoomID, $nStartTime, $nEndTime, -1, -1)) != null) {
			if (!schoorbsDeleteConflicts($nRoomID, $nStartTime, $nEndTime, getUserName())) {
				return SchoorbsREST::sendError('Couldn\'t delete conflicting bookings!', -1);
			}
		}
		
		//make booking
		schoorbsCreateSingleEntry($nStartTime, $nEndTime, 0, 0, $nRoomID, $sUsername, $sName, $sType, $sDescription);
	}

	SchoorbsREST::sendHeaders();
	// always true, if something went wrong there will be an error
	SchoorbsREST::$oTPL->assign('made_booking', 'true');
	SchoorbsREST::$oTPL->display('makebooking.tpl');
}
