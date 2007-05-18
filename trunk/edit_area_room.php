<?php
/**
 * Edit a room in a certain area
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @todo add Mail functions which had been available in MRBS, but were removed due to license Issues
 */

require_once 'config.inc.php';
require_once 'schoorbs-includes/global.web.php';
require_once 'schoorbs-includes/global.functions.php';
require_once "schoorbs-includes/database/$dbsys.php";
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';

## Input ##

#If we dont know the right date then make it up
list($day, $month, $year) = input_DayMonthYear();

if (isset($_REQUEST['room'])) {
	$room = input_Room();
}
elseif (isset($_REQUEST['area'])) {
	$area = input_Area();
}

## Main ##

if (!getAuthorised(2)) {
	showAccessDenied();
}

// Done changing area or room information?
if (isset($_REQUEST['change_done'])) {
	if (!empty($room)) { // Get the area the room is in
		$area = sql_query1("SELECT area_id from $tbl_room where id = ".sql_escape_arg($room));
	}
	header("Location: admin.php?day=$day&month=$month&year=$year&area=$area");
	exit();
} elseif (isset($_REQUEST['change_room'])) {
	$sRoomName = unslashes($_REQUEST['room_name']);
        $sDescription = unslashes($_REQUEST['description']);
	$nCapacity = intval($_REQUEST['capacity']);
 
	$sQuery = "UPDATE $tbl_room SET room_name = '".sql_escape_arg($sRoomName).'\', '
		.'description = \''.sql_escape_arg($sDescription).'\', '
		."capacity = $nCapacity WHERE id = ".sql_escape_arg($room);
	sql_query($sQuery);
} elseif (isset($_REQUEST['change_area'])) {
	$sAreaName = unslashes($_REQUEST['area_name']);

	$sQuery = "UPDATE $tbl_area SET area_name = '".sql_escape_arg($sAreaName)
		."' WHERE id = ".sql_escape_arg($area);
	sql_query($sQuery);
}

print_header($day, $month, $year, isset($area) ? $area : "");

if(!empty($room)) {
	
	$res = sql_query("SELECT * FROM $tbl_room WHERE id = ".sql_escape_arg($room));
	if (!$res) {
		$sMessage = get_vocab("error_room").$room.get_vocab("not_found");
		fatal_error(0, $sMessage);
	}
	$row = sql_row_keyed($res, 0);
	sql_free($res);

	$smarty->assign('row', $row);
	$smarty->assign('room', $room);
} elseif(!empty($area)) {

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
