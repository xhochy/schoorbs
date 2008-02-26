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
	global $_TPL, $enable_periods;

	if ($enable_periods) {
		if (is_array($_REQUEST['day'])) {
			$aDays = array();
			
			for($i = 0; $i < count($_REQUEST['day']); $i++) {
				$nMonth = intval($_REQUEST['month'][$i]);
				$nDay = intval($_REQUEST['day'][$i]);
				$nYear = intval($_REQUEST['year'][$i]);
				if (!checkdate($nMonth, $nDay, $nYear)) {
					sendRESTError('Only periods are supported at the moment!', -1);
				}
				$aDays[] = array('day' => $nDay, 'month' => $nMonth, 
					'year' => $nYear);
			}
		} else {
			sendRESTError('The dates must be passed as an array!', -1);
		}
	} else {
		sendRESTError('Only periods are supported at the moment!', -1);
	}
	
	if (isset($_REQUEST['period'])) {
		$nPeriodID = intval($_REQUEST['period']);
	} else {
		sendRESTError('Period not set!', -1);
	}
	
	// We don't use input_Room(); since if there is none defined, we want an error
	// In the GUI we use default values to redirect the user, who verifies that
	// he is in the room he wants by reading the heading, here we expect an
	// application which might have forgotten to set a room, but is unable to
	// verify if the request is sent to the correct room
	if (isset($_REQUEST['room'])) {
		$nRoomID = intval($_REQUEST['room']);
	} else {
		sendRESTError('Period not set!', -1);
	}
	

	$bFree = true;
	
	foreach ($aDays as $aDay) {
		// make time - only periods supported at the moment
		$nStartTime = mktime(12, $nPeriodID, 0, $aDay['month'],
			$aDay['day'], $aDay['year']
		);
		$nEndTime = $nStartTime + 60;
	
		if (schoorbsCheckFree($nRoomID, $nStartTime, $nEndTime, 0, 0) != null) {
			$bFree = false;
			break;
		}
	}

	sendRESTHeaders();
	if ($bFree) {
		$_TPL->assign('free', 'true');
	} else {
		$_TPL->assign('free', 'false');
	}
	
	$_TPL->display('checkfree.tpl');
}
