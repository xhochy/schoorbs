<?php
/**
 * View a single entry
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The main Schoorbs configuration */
require_once "config.inc.php";
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';
/** The template system */
require_once 'schoorbs-includes/schoorbstpl.class.php';

## Var Init ##

if (isset($_REQUEST['series'])) {
    $bSeries = true;
} else {
    $bSeries = false;
} 

if (isset($_REQUEST['id'])) {
	// something like foo&id=&bar was passed
    	if (empty($_REQUEST['id'])) {
	        SchoorbsTPL::error(Lang::_('An entry id must be specified.'));
	        exit(1);
	} else {
	        $nId = intval($_REQUEST['id']);
	        // No integer was submitted, don't accept fault values
	        if (strval($nId) !== $_REQUEST['id']) {
			SchoorbsTPL::error(Lang::_('An entry id must be specified.'));
			exit(1);
	        }
    	}
} else {
        SchoorbsTPL::error(Lang::_('An entry id must be specified.'));
        exit(1);
}
	
## Main ##

$oEntry = Entry::getById($nId);

SchoorbsTPL::populateVar('entry', $oEntry);
SchoorbsTPL::renderPage('view-entry');
