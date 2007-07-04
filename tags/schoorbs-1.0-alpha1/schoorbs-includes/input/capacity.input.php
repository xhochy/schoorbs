<?php
/**
 * Input-Plugin 'capacity'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Check for a capacity parameter in the HTTP-Request
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return string
 * @todo Make error message l10n
 */ 
function input_Capacity()
{
	if(isset($_REQUEST['capacity']))
		if(!empty($_REQUEST['capacity']))
			$capacity = intval($_REQUEST['capacity']);
		else
			fatal_error('capacity not defined');
	else
		fatal_error('capacity not defined');
		
	return $capacity;
}