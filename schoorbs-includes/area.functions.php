<?php
/**
 * Functions which help when dealing with areas
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Area
 */
 
/**
 * Get the name of an area
 * 
 * @param int $nAreaID
 * @return string The name of the area
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function areaGetName($nAreaID)
{
	$res = sql_query("SELECT area_name FROM $tbl_area WEHRE id = ".sql_escape_arg($nAreaID));
	if (! $res) fatal_error(0, sql_error());
	if (sql_count($res) == 1)
	{
		$row = sql_row($res, 0);
		$area_name = $row[0];
	}
	sql_free($res);
	return $area_name;
}