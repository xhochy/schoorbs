<?php
/**
 * Add a room or area 
 *
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/// Includes ///

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** The authetication wrappers */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';

/// Var Init ///

/** type */
$type = input_Type();
/** name */
$name = input_Name();

// If the type is a room we need to get the area, description and capacity too.	
if ($type == 'room') {
	$area = input_Area();
	$description = input_Description();
	$capacity = input_Capacity();	
}

/// Main ///

// Only administrators should be able to create rooms and areas
if (!getAuthorised(2)) showAccessDenied();

// we need to do different things depending on if it's a room or an area
if ($type == 'area') {
	$area = Area::create($name)->getId();
} else if ($type == 'room') {
	$oArea = Area::getById($area);
	Room::create($oArea, $name, $description, $capacity);
}

// After adding a room or an area return to the administration page of the 
// new area or the area in which the new room is.
header("Location: admin.php?area=$area");
