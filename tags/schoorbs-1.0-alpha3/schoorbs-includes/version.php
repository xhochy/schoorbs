<?php
/**
 * This site only has the version of Schoorbs saved
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * Returns a string representing the version of Schoorbs 
 */
function get_schoorbs_version()
{
  # Schoorbs developers, make sure to update this string before each release
  $schoorbs_version = "Schoorbs ".get_schoorbs_version_number();

  return $schoorbs_version;
}

/**
 * Returns a string representing the versionnumber of Schoorbs
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function get_schoorbs_version_number()
{
	return file_get_contents(dirname(__FILE__).'/version.txt');
}