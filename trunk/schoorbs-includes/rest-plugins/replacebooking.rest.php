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
	global $_TPL, $enable_periods;
	
	if(!getAuthorised(2)){
        return sendRESTError('Access Denied', 4);         
    }

	if ($enable_periods) {
		if (is_array($_REQUEST['day'])) {
			$aDays = array();
			
			for($i = 0; $i < count($_REQUEST['day']); $i++) {
				$nMonth = intval($_REQUEST['month'][$i]);
				$nDay = intval($_REQUEST['day'][$i]);
				$nYear = intval($_REQUEST['year'][$i]);
				if (!checkdate($nMonth, $nDay, $nYear)) {
					return sendRESTError('Given date is invalid!', -1);
				}
				$aDays[] = array('day' => $nDay, 'month' => $nMonth, 
					'year' => $nYear);
			}
		} else {
			return sendRESTError('Only date arrays are supported at the moment!', -1);
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
		return sendRESTError('Room not set!', -1);
	}
	if (isset($_REQUEST['period'])) {
		$nPeriodID = intval($_REQUEST['period']);
	} else {
		return sendRESTError('Period not set!', -1);
	}
	if (isset($_REQUEST['name'])) {
		$sName = unslashes($_REQUEST['name']);
		
		if (empty($sName)) {
			return sendRESTError('Name is empty!', -1);
		}
	} else {
		return sendRESTError('Name not set!', -1);
	}
	// Description could be empty, but the client should specify that explicitly
	// Given a not-set description could be a failure by the client, there still
	// might be one.
	if (isset($_REQUEST['description'])) {
		$sDescription = unslashes($_REQUEST['description']);
	} else {
		return sendRESTError('Description not set!', -1);
	}
	if (isset($_REQUEST['type'])) {
		$sType = unslashes($_REQUEST['type']);
		
		if (empty($sType)) {
			return sendRESTError('Type is empty!', -1);
		}
	} else {
		return sendRESTError('Type not set!', -1);
	}	

	foreach ($aDays as $aDay) {
		// make time - only periods supported at the moment
		$nStartTime = mktime(12, $nPeriodID, 0, $aDay['month'],
			$aDay['day'], $aDay['year']
		);
		$nEndTime = $nStartTime + 60;
	
		if (($sError = schoorbsCheckFree($nRoomID, $nStartTime, $nEndTime, -1, -1)) != null) {
			if (!schoorbsDeleteConflicts($nRoomID, $nStartTime, $nEndTime, getUserName())) {
				return sendRESTError('Couldn\'t delete conflicting bookings!', -1);
			}
		}
		
		//make booking
		schoorbsCreateSingleEntry($nStartTime, $nEndTime, 0, 0, $nRoomID, getUserName(), $sName, $sType, $sDescription);
	}

	sendRESTHeaders();
	// always true, if something went wrong there will be an error
	$_TPL->assign('made_booking', 'true');
	$_TPL->display('makebooking.tpl');
}
