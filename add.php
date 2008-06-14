<?php
/**
 * Add a room or area 
 *
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Admin
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
/** The authetication wrappers */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';

## Var Init ##

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

## Main ##

// Only administrators should be able to create rooms and areas
if (!getAuthorised(2)) showAccessDenied();

/** we need to do different things depending on if it's a room or an area */
if ($type == "area") {
	$sQuery = sprintf(
		'INSERT INTO %s (area_name) VALUES (\'%s\')', 
		$tbl_area, sql_escape_arg($name)
	);
	if(sql_command($sQuery) < 0) fatal_error(true, sql_error());
	// The id of the newly created area is the last id inserted into the database
    $area = sql_insert_id($tbl_area, "id");
} else if ($type == "room") {
	$sQuery = sprintf(
		'INSERT INTO %s (room_name, area_id, description, capacity)'
		.' VALUES (\'%s\', %d, \'%s\', %d)', 
        $tbl_room, sql_escape_arg($name), $area, sql_escape_arg($description), 
        $capacity
    );
	if (sql_command($sQuery) < 0) fatal_error(true, sql_error());
}

// After adding a room or an area return to the administration page of the 
// new area or the area in which the new room is.
header("Location: admin.php?area=$area");
