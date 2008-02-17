<?php
/**
 * Input-Plugin 'description'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/**
 * Check the description-input
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return string
 */ 
function input_Description()
{
	if(isset($_REQUEST['description']))
		return unslashes($_REQUEST['description']);
	else
		return '';
}