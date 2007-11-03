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

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();
$type = input_Type();
$name = input_Name();
	
if($type == 'room')
{
	$area = input_Area();
	$description = input_Description();
	$capacity = input_Capacity();	
}

## Main ##

if(!getAuthorised(2))
{
	showAccessDenied();
}

/** we need to do different things depending on if its a room or an area */
if ($type == "area")
{
	$area_name_q = sql_escape_arg($name);
	$sQuery = "INSERT INTO $tbl_area (area_name) VALUES ('$area_name_q')";
	if(sql_command($sQuery) < 0) 
      fatal_error(1, sql_error());
    $area = sql_insert_id("$tbl_area", "id");
}

if ($type == "room")
{
	$room_name_q = sql_escape_arg($name);
	$description_q = sql_escape_arg($description);
	if (empty($capacity)) $capacity = 0;
	$sQuery = "INSERT INTO $tbl_room (room_name, area_id, description, capacity)"
	   ." VALUES ('$room_name_q',$area, '$description_q',$capacity)";
	if (sql_command($sQuery) < 0) 
      fatal_error(1, sql_error());
}

header("Location: admin.php?area=$area");