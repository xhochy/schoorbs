<?php
/**
 * REST-Plugin 'checkFree'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/** Include the database backend */
require_once dirname(__FILE__).'/../database/schoorbs_sql.php';
 
/**
 * Check if the room is free at a given time
 * 
 * @author Uwe L. Korn
 * @todo implement for non-period-based systems
 */ 
function rest_function_checkFree()
{
	global $enable_periods;

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
			return SchoorbsREST::sendError('The dates must be passed as an array!', 8);
		}
	} else {
		/** @todo Only periods are supported at the moment */
		return SchoorbsREST::sendError('Only periods are supported at the moment!', 7);
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
	

	$bFree = true;
	
	foreach ($aDays as $aDay) {
		// make time - only periods supported at the moment
		$nStartTime = mktime(12, $nPeriodID, 0, $aDay['month'],
			$aDay['day'], $aDay['year']
		);
		// At the moment we are only using periods and we only support a booking
		// length of 1 period, so the time between EndTime and StartTime is 
		// always 60 seconds.
		$nEndTime = $nStartTime + 60;
	
		if (schoorbsCheckFree($nRoomID, $nStartTime, $nEndTime, 0, 0) != null) {
			$bFree = false;
		}
	}
	
	$oXML = new SimpleXMLElement('<rsp stat="ok" />');
	if ($bFree) {
		$oXML->addChild('free', 'true');
	} else {
		$oXML->addChild('free', 'false');
	}
	echo $oXML->asXML();
}
