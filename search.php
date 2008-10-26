<?php
/**
 * Search for bookings
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';
/** The template system */
require_once 'schoorbs-includes/schoorbstpl.class.php';

## Main ##

// Get all booking types 
$aTypes = array();
for ($c = 'A'; $c <= 'Z'; $c++) {
	if (isset($typel[$c]) && (!empty($typel[$c]))) {
		$aTypes[] = array('c' => $c, 'text' => $typel[$c]);
	}
}

$aResult = array();
if (isset($_REQUEST['searchtype'])) {

	if ($_REQUEST['searchtype'] == 'simple') {
		$sText = unslashes($_REQUEST['search-for']);
		$aResult = Entry::simpleSearch($sText);
	} elseif ($_REQUEST['searchtype'] == 'advanced') {
		$sText = unslashes($_REQUEST['description']);
		$sCreateBy = unslashes($_REQUEST['create_by']);
		$oRoom = Room::getById(input_Room());
		$sType = unslashes($_REQUEST['type']);
		if ($sType == '-ignore-') {
			$sType = '';
		}
		$aResult = Entry::advancedSearch($sText, $sCreateBy, $oRoom,
			$sType);
	}
}

SchoorbsTPL::populateVar('result', $aResult);
SchoorbsTPL::populateVar('types', $aTypes);
SchoorbsTPL::renderPage('search');
