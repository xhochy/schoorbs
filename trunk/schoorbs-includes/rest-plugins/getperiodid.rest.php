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
 * The name of the period must be supplied via the GET/POST-parameter 'name'
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_getPeriodID()
{
	global $periods;
	
	// Get the parameter 'name' from the HTTP-Request
	$sName = unslashes($_REQUEST['name']);
	// Go through all periods to find the fitting one
	for ($i = 0; $i < count($periods); $i++) {
		if ($periods[$i] == $sName) {
			$nPeriodID = $i;
		}
	}
	
	// An unset $nPeriodID variable means that we haven't found a fitting
	// period.
	if (!isset($nPeriodID)) {
		return SchoorbsREST::sendError('Couldn\'t find a fitting period.', 6);
	}

	$oXML = new SimpleXMLElement('<rsp stat="ok" />');
	$oXML->addChild('period_id', $nPeriodID);
	echo $oXML->asXML();
}
