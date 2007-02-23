<?php
/**
 * The administration interface
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 */

require_once "config.inc.php";
require_once "functions.php";
require_once "db/$dbsys.php";
require_once "auth/mrbs_auth.php";

## Var Init ##

/** day, month, year **/
list($day, $month, $year) = input_DayMonthYear();

/** area **/
if(isset($_REQUEST['area']))
    if(!empty($_REQUEST['area']))
        $area = $_REQUEST['area'];
        
if(isset($area))
{
	$res = sql_query("select area_name from $tbl_area where id=$area");
	if (! $res) fatal_error(0, sql_error());
	if (sql_count($res) == 1)
	{
		$row = sql_row($res, 0);
		$area_name = $row[0];
	}
	sql_free($res);
}

## Main ##

if(!getAuthorised(2))
{
	showAccessDenied($day, $month, $year, $area);
	exit();
}

print_header($day, $month, $year, isset($area) ? $area : "");


# This cell has the areas
$res = sql_query("select id, area_name from $tbl_area order by area_name");
if (!$res) fatal_error(0, sql_error());
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
	$res = sql_query("select id, room_name, description, capacity from $tbl_room where area_id=$area order by room_name");
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
$smarty->assign('area',intval($area));
$smarty->assign('areas',$aAreas);
$smarty->assign('area_name',$area_name);
$smarty->assign('noareas',$noareas);
$smarty->display('admin.tpl');

require_once "trailer.php";
?>