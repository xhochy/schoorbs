<?php
/**
 * The administration interface
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/// Includes ///

/** The Schoorbs configuration **/
require_once 'config.inc.php';
/** The common functions used only on the web */
require_once 'schoorbs-includes/global.web.php';
/** The common functions used everywhere */
require_once 'schoorbs-includes/global.functions.php';
/** The authentication layer */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';

/// Var Init ///

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();

/** area **/
$area = input_Area();

/// Main ///

// Only administrators should be able to administrate Schoorbs
if(!getAuthorised(2)) showAccessDenied();

// Get the area as an ORM instance
$oArea = Area::getById($area);
// Get the name of the area we are working on out of the database
$area_name = $oArea->getName();

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
//
// $bNoRooms will determinate in the template if we should show a list of rooms
// or a remark that there are no rooms available at the moment.
$aRooms = Room::getRooms($oArea);
if (count($aRooms) === 0) {
	$bNoRooms = 'true';
} else {
	$bNoRooms = 'false';
}

// Assign the variables for use in the template system
/** 
 * @todo In future the variables norooms and noareas should be removed, the
 *       templates should get intelligent, so that could determinate on their 
 *       own, if there have rooms/areas, which they could display. 
 */
$smarty->assign(array(
    'norooms' => $bNoRooms,
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
