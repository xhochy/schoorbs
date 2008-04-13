<?php
/**
 * The administration interface
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The Schoorbs configuration **/
require_once 'config.inc.php';
/** The common functions used only on the web */
require_once 'schoorbs-includes/global.web.php';
/** The common functions used everywhere */
require_once 'schoorbs-includes/global.functions.php';
/** The database layer */
require_once "schoorbs-includes/database/$dbsys.php";
/** The authentication layer */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';

## Var Init ##

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();

/** area **/
$area = input_Area();
// Get the name of the area we are working on out of the database
$area_name = areaGetName($area);

## Main ##

// Only administrators should be able to administrate Schoorbs
if(!getAuthorised(2)) showAccessDenied();

// Print out the (X)HTML-header
print_header();

// Collect all areas
//
// $bNoAreas will determinate in the template if we should show a list of areas
// or a remark that there is no area available at the moment.
$aAreas = Area::getAreas();
if (count($aAreas) === 0) {
	$bNoAreas = 'true';
} else {
	$bNoAreas = 'false';
}

// Collect all rooms in the choosen area
$aRooms = array();
if(isset($area)) {
	$res = sql_query("SELECT id, room_name, description, capacity FROM $tbl_room where area_id = "
		.sql_escape_arg($area)." ORDER BY room_name");
	if (!$res) fatal_error(0, sql_error());
	if (sql_count($res) == 0) {
		$norooms = 'true';
	} else {
		for ($i = 0; ($row = sql_row($res, $i)); $i++) {
			$aRooms[] = array('id' => $row[0], 'name' => $row[1], 'description' => $row[2], 'capacity' => $row[3]);
        }
		$norooms = 'false';
	}
}

// Assign the variables for use in the template system
$smarty->assign(array(
    'norooms' => $norooms,
    'rooms' => $aRooms,
    'areas' => $aAreas,
    'noareas' => $bNoAreas,
    'area' =>  $area,
    'area_name' => $area_name
));
// Display the administration template
$smarty->display('admin.tpl');

/** The common Schoorbs footer */
require_once 'schoorbs-includes/trailer.php';
