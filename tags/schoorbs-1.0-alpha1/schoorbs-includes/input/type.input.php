<?php
/**
 * Input-Plugin 'type'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Check for a type parameter in the HTTP-Request
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return string
 * @todo Make error message l10n
 */ 
function input_Type()
{
	if(isset($_REQUEST['type']))
		if(!empty($_REQUEST['type']))
			$type = unslashes($_REQUEST['type']);
		else
			fatal_error('type not defined');
	else
		fatal_error('type not defined');
		
	if($type != 'area' && $type != 'room')
		fatal_error('type must be one of area,room');
		
		
	return $type;
}