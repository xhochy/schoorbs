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
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";

## Main ##

// print the page header
print_header();

// Get all booking types 
$aTypes = array();
for ($c = 'A'; $c <= 'Z'; $c++) {
	if (isset($typel[$c]) && (!empty($typel[$c]))) {
		$aTypes[] = array('c' => $c, 'text' => $typel[$c]);
	}
}

// Get all areas
$aAreas = getAreas();
// Get all rooms for each area
$aRooms = array();
$hResult = sql_query(sprintf('SELECT id, area_id, room_name FROM %s', $tbl_room));
if ($hResult) for ($i = 0; ($row = sql_row($hResult, $i)); $i++) {
	$aRooms[$row[1]][] = array('id' => $row[0], 'name' => $row[2]);
} else {
	fatal_error(false, sql_error());
}

// Assign variables used by the template
$smarty->assign(array(
	'types' => $aTypes,
	'areas' => $aAreas,
	'rooms' => $aRooms
));

// Display the Search template
$smarty->display('search.tpl');

if (isset($_REQUEST['searchtype'])) {
	unset($hResult);
	
	if ($_REQUEST['searchtype'] == 'simple') {
		$sText = sql_escape_arg(unslashes($_REQUEST['search-for']));
		$hResult = sql_query(sprintf(
			'SELECT id, start_time, end_time, name, description FROM %s WHERE '
			.'name LIKE \'%%%s%%\' OR description LIKE \'%%%s%%\' OR create_by '
			.'LIKE \'%%%s%%\'',
			$tbl_entry, $sText, $sText, $sText
		));
	} elseif ($_REQUEST['searchtype'] == 'advanced') {
		$sText = sql_escape_arg(unslashes($_REQUEST['description']));
		$sCreateBy = sql_escape_arg(unslashes($_REQUEST['create_by']));
		$sQuery = sprintf(
			'SELECT id, start_time, end_time, name, description FROM %s WHERE '
			.'(name LIKE \'%%%s%%\' OR description LIKE \'%%%s%%\') AND create_by '
			.'LIKE \'%%%s%%\'',
			$tbl_entry, $sText, $sText, $sCreateBy
		);
		$sType = sql_escape_arg(unslashes($_REQUEST['type']));
		if ($sType != '-ignore-') {
			$sQuery.= ' AND type = \''.$sType.'\'';
		}
		$nRoom = intval(input_Room());
		if ($nRoom != -1) {
			$sQuery.= ' AND room_id = '.$nRoom;
		}
		$hResult = sql_query($sQuery);
	}
	
	if (isset($hResult)) {
		$aBookings = array();
		for ($i = 0; ($row = sql_row($hResult, $i)); $i++) {
			if ($enable_periods) {
				list( , $start_date) =  period_date_string($row[1]);
				list( , $end_date) =  period_date_string($row[2], -1);
			} else {
				$start_date = time_date_string($row[1]);
				$end_date = time_date_string($row[2]);
			}

			$aBookings[] = array(
				'id' => $row[0],
				'start_time' => $start_date,
				'end_time' => $end_date,
				'name' => $row[3],
				'description' => $row[4]
			);
		}
		
		$smarty->assign('bookings', $aBookings);
		$smarty->display('search-results.tpl');
	}
}

/** The footer of the HTML Page */
require_once 'schoorbs-includes/trailer.php';
