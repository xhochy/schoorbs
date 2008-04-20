<?php
/**
 * Functions which help when dealing with areas
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Area
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
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
	global $tbl_area;

	$sQuery = "SELECT area_name FROM $tbl_area WHERE id = ".intval($nAreaID);
	$res = sql_query($sQuery);
	if (! $res) fatal_error(true, sql_error());

	if (sql_count($res) == 1) {
		$row = sql_row($res, 0);
		$area_name = $row[0];
	}

	sql_free($res);
	return $area_name;
}

/**
 * Get an array of all known areas
 * 
 * @return array[][] array(array(id, area_name))
 */
function getAreas()
{
	global $tbl_area;
	
   	$res = sql_query(sprintf('SELECT id, area_name FROM %s', $tbl_area));
   	$rows = array();
   	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++) {
   		$rows[] = array('id' => $row[0], 'area_name' => $row[1]);
    } else {
    	fatal_error(false, sql_error());
    }
    return $rows;
}
