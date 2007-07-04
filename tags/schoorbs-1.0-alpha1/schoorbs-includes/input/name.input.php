<?php
/**
 * Input-Plugin 'name'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Check for a name parameter in the HTTP-Request
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return string
 * @todo Make error message l10n
 */ 
function input_Name()
{
	if(isset($_REQUEST['name']))
		if(!empty($_REQUEST['name']))
			$name = unslashes($_REQUEST['name']);
		else
			fatal_error('name not defined');
	else
		fatal_error('name not defined');
		
	return $name;
}