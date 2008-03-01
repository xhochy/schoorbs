<?php
/**
 * REST-Plugin 'getPeriodID'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Returns the ID of a period by a given name
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_getPeriodID()
{
	global $periods;
	
	$sName = unslashes($_REQUEST['name']);
	for ($i = 0; $i < count($periods); $i++) {
		if ($periods[$i] == $sName) {
			$nPeriodID = $i;
		}
	}
	
	if (!isset($nPeriodID)) {
		return SchoorbsREST::sendError('Couldn\'t find a fitting period.', -1);
	}

	SchoorbsREST::sendHeaders();
	SchoorbsREST::$oTPL->assign('period_id', $nPeriodID);
	SchoorbsREST::$oTPL->display('getperiodid.tpl');
}
