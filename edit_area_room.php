<?php
/**
 * Edit a room in a certain area
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

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

if (isset($_REQUEST['room'])) $room = input_Room();
elseif (isset($_REQUEST['area'])) $area = input_Area();

## Main ##

if (!getAuthorised(2)) showAccessDenied();

// Done changing area or room information?
if (isset($_REQUEST['change_done'])) {
	if (isset($room)) { // Get the area the room is in
		$area = sql_query1("SELECT area_id from $tbl_room where id = ".sql_escape_arg($room));
	}
	header("Location: admin.php?day=$day&month=$month&year=$year&area=$area");
	exit();
} elseif (isset($_REQUEST['change_room'])) {
	$sRoomName = unslashes($_REQUEST['room_name']);
    $sDescription = input_Description();
	$nCapacity = input_Capacity();
 
	$sQuery = sprintf(
		'UPDATE %s SET room_name = \'%s\', description = \'%s\', capacity = %d '
		.'WHERE id = %d', 
		$tbl_room, sql_escape_arg($sRoomName), sql_escape_arg($sDescription), 
		$nCapacity, $room
	);
	sql_query($sQuery);
} elseif (isset($_REQUEST['change_area'])) {
	$sAreaName = unslashes($_REQUEST['area_name']);

	$sQuery = sprintf(
		'UPDATE %S SET area_name = \'%s\' WHERE id = %d', 
		$tbl_area, sql_escape_arg($sAreaName), sql_escape_arg($area)
	);
	sql_query($sQuery);
}

print_header();

if(isset($room)) {
	
	$res = sql_query("SELECT * FROM $tbl_room WHERE id = ".sql_escape_arg($room));
	if (!$res) {
		$sMessage = get_vocab("error_room").$room.get_vocab("not_found");
		fatal_error(0, $sMessage);
	}
	$row = sql_row_keyed($res, 0);
	sql_free($res);

	$smarty->assign('row', $row);
	$smarty->assign('room', $room);
} elseif(isset($area)) {

    $res = sql_query("SELECT * FROM $tbl_area WHERE id = ".sql_escape_arg($area));
	if (!$res) {
		$sMessage = get_vocab("error_area").$area.get_vocab("not_found");
		fatal_error(0, $sMessage);
	}
	$row = sql_row_keyed($res, 0);
	sql_free($res);

	$smarty->assign('row', $row);
	$smarty->assign('area', $area);
}

$smarty->display('edit_area_room.tpl');

require_once 'schoorbs-includes/trailer.php';
