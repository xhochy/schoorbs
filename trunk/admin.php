<?php
/**
 * The administration interface
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 */

require_once 'config.inc.php';
require_once 'functions.php';
require_once "db/$dbsys.php";
require_once 'auth/schoorbs_auth.php';

## Var Init ##

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();

/** area **/
if(isset($_REQUEST['area']))
    if(!empty($_REQUEST['area']))
    {
        $area = intval($_REQUEST['area']);
        $smarty->assign('area', $area);
        $area_name = areaGetName($area);
        $smarty->assign('area_name',$area_name);
    }	

## Main ##

if(!getAuthorised(2))
	showAccessDenied();

print_header($day, $month, $year, isset($area) ? $area : "");


# This cell has the areas
$res = sql_query("SELECT id, area_name FROM $tbl_area ORDER BY area_name");
if(!$res)
	fatal_error(0, sql_error());
$aAreas = array();
if (sql_count($res) == 0)
	$noareas = 'true';
else {
	for ($i = 0; ($row = sql_row($res, $i)); $i++)
		$aAreas[] = array('name' => $row[1], 'id' => $row[0]);		
	$noareas = 'false';
}

# This one has the rooms
$aRooms = array();
if(isset($area)) {
	$res = sql_query("SELECT id, room_name, description, capacity FROM $tbl_room where area_id = "
		.sql_escape_arg($area)." ORDER BY room_name");
	if (! $res) fatal_error(0, sql_error());
	if (sql_count($res) == 0)
		$norooms = 'true';
	else {
		for ($i = 0; ($row = sql_row($res, $i)); $i++) 
			$aRooms[] = array('id' => $row[0], 'name' => $row[1], 'description' => $row[2], 'capacity' => $row[3]);
		$norooms = 'false';
	}
}
$smarty->assign('norooms',$norooms);
$smarty->assign('rooms',$aRooms);
$smarty->assign('areas',$aAreas);
$smarty->assign('noareas',$noareas);
$smarty->display('admin.tpl');

require_once "trailer.php";