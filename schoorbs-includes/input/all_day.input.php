<?php
/**
 * Input-Plugin 'all_day'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Check if we want to last the entry the whole day
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return int
 */ 
function input_All_Day()
{
	if (isset($_REQUEST['all_day'])) {
		$all_day = strtolower(trim($_REQUEST['all_day']));
		if ($all_day != 'yes') $all_day = 'no';
	} else {
		$all_day = 'no';
	}
	
	return $all_day;
}
