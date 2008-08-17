<?php
/**
 * Input-Plugin 'Area'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * Return a default area; used if no area is already known. This returns the
 * lowest area ID in the database (no guaranty there is an area 1).
 * 
 * @todo This could be changed to implement something like per-user defaults.
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return int the ID of the default Area
 */ 
function get_default_area()
{
	global $tbl_area;
	
	$area = sql_query1("SELECT id FROM $tbl_area ORDER BY area_name LIMIT 1");
	
	return ($area < 0 ? 0 : $area);
}
 
/**
 * Catches the Area out of the REQUEST-Array and the defaults
 * 
 * @todo Check if there area exists
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return int
 */ 
function input_Area()
{
	if(isset($_REQUEST['area'])) {
	    if(empty($_REQUEST['area'])) {
	        $nArea = get_default_area();
	    } else {
	        $nArea = intval($_REQUEST['area']);
	        // Maybe the submitted area-id wasn't an int
	        if (strval($nArea) !== $_REQUEST['area']) {
	        	$nArea = get_default_area();
	        }
	    }
	} else {
	    $nArea = get_default_area();
	}
	return $nArea;
}
