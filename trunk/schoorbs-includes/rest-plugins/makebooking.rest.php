<?php
/**
 * REST-Plugin 'makeBooking'
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
 * not be booked, all other will.
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_makeBooking()
{
	global $enable_periods;
	
	// Allow all users which can be authenticated
	if (!getAuthorised(1)) {
        return SchoorbsREST::sendError('Access Denied', 4);         
    }

	if ($enable_periods) {
		// The parameters 'day', 'year' and 'month' need to be arrays.
	    // They need to have the same length.
		if (isset($_REQUEST['day']) && is_array($_REQUEST['day'])) {
			$aDays = array();
			
			for($i = 0; $i < count($_REQUEST['day']); $i++) {
				// Convert all input parameters to integer values
				$nMonth = intval($_REQUEST['month'][$i]);
				$nDay = intval($_REQUEST['day'][$i]);
				$nYear = intval($_REQUEST['year'][$i]);
				// Check if the given date is a valid date
				if (!checkdate($nMonth, $nDay, $nYear)) {
					return SchoorbsREST::sendError('Given date is invalid!', 9);
				}
				$aDays[] = array('day' => $nDay, 'month' => $nMonth, 
					'year' => $nYear);
			}
		} else {
			return SchoorbsREST::sendError('Only date arrays are supported at the moment!', 8);
		}
	} else {
	    /** @todo Only periods are supported at the moment */
		return SchoorbsREST::sendError('Only periods are supported at the moment!', 7);
	}
	
	// We don't use input_Room(); since if there is none defined, we want an error
	// In the GUI we use default values to redirect the user, who verifies that
	// he is in the room he wants by reading the heading, here we expect an
	// application which might have forgotten to set a room, but is unable to
	// verify if the request is sent to the correct room
	if (isset($_REQUEST['room'])) {
		$nRoomID = intval($_REQUEST['room']);
	} else {
		return SchoorbsREST::sendError('Room not set!', 11);
	}
	// Always check if period is set, if not there will be an error at the 
	// moment. When we add support for non-period calls on replaceBooking, we
	// should only call this HTTP-paramter -> PHP-variable conversion if periods
	// are enabled.
	if (isset($_REQUEST['period'])) {
		$nPeriodID = intval($_REQUEST['period']);
	} else {
		return SchoorbsREST::sendError('Period not set!', 10);
	}
	if (isset($_REQUEST['name'])) {
		$sName = unslashes($_REQUEST['name']);
		
		if (empty($sName)) {
			return SchoorbsREST::sendError('Name is empty!', 12);
		}
	} else {
		return SchoorbsREST::sendError('Name not set!', 12);
	}
	// Description could be empty, but the client should specify that explicitly
	// Given a not-set description could be a failure by the client, there still
	// might be one.
	if (isset($_REQUEST['description'])) {
		$sDescription = unslashes($_REQUEST['description']);
	} else {
		return SchoorbsREST::sendError('Description not set!', 13);
	}
	// The type of the booking. Should be one of ['A'..'Z'].
	if (isset($_REQUEST['type'])) {
		$sType = unslashes($_REQUEST['type']);
		
		// Empty types are not accepted
		if (empty($sType)) {
			return SchoorbsREST::sendError('Type is empty!', 14);
		}
	} else {
		// Type must be set!
		return SchoorbsREST::sendError('Type not set!', 14);
	}	

	$bMade = true;
	foreach ($aDays as $aDay) {
		// make time - only periods supported at the moment
		$nStartTime = mktime(12, $nPeriodID, 0, $aDay['month'],
			$aDay['day'], $aDay['year']
		);
		// At the moment we are only using periods and we only support a booking
		// length of 1 period, so the time between EndTime and StartTime is 
		// always 60 seconds.
		$nEndTime = $nStartTime + 60;
	
		if (($sError = schoorbsCheckFree($nRoomID, $nStartTime, $nEndTime, -1, -1)) != null) {
			$bMade = false;
		}
		
		//make booking
		schoorbsCreateSingleEntry($nStartTime, $nEndTime, 0, 0, $nRoomID, getUserName(), $sName, $sType, $sDescription);
	}

	$oXML = new SimpleXMLElement('<rsp stat="ok" />');
	if ($bMade) {
		$oXML->addChild('made_booking', 'true');
	} else {
		$oXML->addChild('made_booking', 'false');
	}
	echo $oXML->asXML();
}
