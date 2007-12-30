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
 */ 
function rest_function_checkFree()
{
	global $_TPL, $enable_periods;

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

	$bFree = true;
	
	foreach ($aDays as $aDay) {
		// make time - only periods supported at the moment
		$nStartTime = mktime(12, $nPeriodID, 0, $aDay['month'],
			$aDay['day'], $aDay['year']
		);
		$nEndTime = $nStartTime + 60;
	
		if (schoorbsCheckFree($nRoomID, $nStartTime, $nEndTime, -1, -1) != null) {
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