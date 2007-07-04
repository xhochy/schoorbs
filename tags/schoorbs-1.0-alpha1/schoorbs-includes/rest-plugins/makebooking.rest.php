<?php
/**
 * REST-Plugin 'checkFree'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
require_once dirname(__FILE__).'/../database/schoorbs_sql.php';
 
/**
 * Check if the room is free at a given time
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_makeBooking()
{
	global $_TPL, $enable_periods;
	
	if(!getAuthorised(1)){
        sendRESTError('Access Denied',4);         
    }
	            

	if ($enable_periods) {
		if (is_array($_REQUEST['day'])) {
			$aDays = array();
			
			for($i = 0; $i < count($_REQUEST['day']); $i++) {
				$aDays[] = array('day' => intval($_REQUEST['day'][$i]),
					'month' => intval($_REQUEST['month'][$i]),
					'year' => intval($_REQUEST['year'][$i])
				);
			}
		} else {
			die('Only date arrays are supported at the moment!');
		}
	} else {
		die('Only periods are supported at the moment!');
	}
	
	$nRoomID = input_Room();
	$nPeriodID = intval($_REQUEST['period']);
	$sName = unslashes($_REQUEST['name']);
	$sDescription = unslashes($_REQUEST['description']);
	$sType = unslashes($_REQUEST['type']);

	$bFree = true;
	
	foreach ($aDays as $aDay) {
		// make time - only periods supported at the moment
		$nStartTime = mktime(12, $nPeriodID, 0, $aDay['month'],
			$aDay['day'], $aDay['year']
		);
		$nEndTime = $nStartTime + 60;
	
		if (($sError = schoorbsCheckFree($nRoomID, $nStartTime, $nEndTime, -1, -1)) != null) {
			$bMade = false;
			break;
		}
		
		//make booking
		schoorbsCreateSingleEntry($nStartTime, $nEndTime, 0, 0, $nRoomID, getUserName(), $sName, $sType, $sDescription);
		$bMade = true;
	}

	sendRESTHeaders();
	if ($bMade) {
		$_TPL->assign('made_booking', 'true');
	} else {
		$_TPL->assign('made_booking', 'false');
	}
	
	$_TPL->display('makebooking.tpl');
}