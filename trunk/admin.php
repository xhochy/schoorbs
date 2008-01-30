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

## Var Init ##

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();

/** area **/
$area = input_Area();
$area_name = areaGetName($area);

## Main ##

if(!getAuthorised(2)) {
	showAccessDenied();
}

print_header();

# This cell has the areas
$res = sql_query("SELECT id, area_name FROM $tbl_area ORDER BY area_name");
if(!$res) {
	fatal_error(0, sql_error());
}

$aAreas = array();
if (sql_count($res) == 0) {
	$noareas = 'true';
} else {
	for ($i = 0; ($row = sql_row($res, $i)); $i++) {
		$aAreas[] = array('name' => $row[1], 'id' => $row[0]);
	}

	$noareas = 'false';
}

# This one has the rooms
$aRooms = array();
if(isset($area)) {
	$res = sql_query("SELECT id, room_name, description, capacity FROM $tbl_room where area_id = "
		.sql_escape_arg($area)." ORDER BY room_name");
	if (!$res) fatal_error(0, sql_error());
	if (sql_count($res) == 0)
		$norooms = 'true';
	else {
		for ($i = 0; ($row = sql_row($res, $i)); $i++) {
			$aRooms[] = array('id' => $row[0], 'name' => $row[1], 'description' => $row[2], 'capacity' => $row[3]);
        }
		$norooms = 'false';
	}
}

$smarty->assign(array(
    'norooms' => $norooms,
    'rooms' => $aRooms,
    'areas' => $aAreas,
    'noareas' => $noareas,
    'area' =>  $area,
    'area_name' => $area_name
));
$smarty->display('admin.tpl');

/** The common Schoorbs footer **/
require_once 'schoorbs-includes/trailer.php';