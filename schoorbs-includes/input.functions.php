<?php
/**
 * Functions to handle GET/POST-Inputs
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
## Includes ##

require_once dirname(__FILE__).'/../config.inc.php';
require_once 'global.functions.php';
require_once "database/$dbsys.php";

## Input Plugins ##

require_once 'input/daymonthyear.input.php';
require_once 'input/area.input.php';
require_once 'input/room.input.php';
require_once 'input/pview.input.php';
require_once 'input/type.input.php';
require_once 'input/name.input.php';
require_once 'input/description.input.php';