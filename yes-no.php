<?php
/**
 * The administration interface
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
/// Includes ///

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The authetication wrappers */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';
/** The template system */
require_once 'schoorbs-includes/schoorbstpl.class.php';

if (!isset($_REQUEST['question'])) {
	SchoorbsTPL::error(Lang::_('No question to ask was provided!'));
}

if (empty($_REQUEST['question'])) {
	SchoorbsTPL::error(Lang::_('No question to ask was provided!'));
}

if (!isset($_REQUEST['referto'])) {
	SchoorbsTPL::error(Lang::_('No url to refer was provided!'));
}

if (empty($_REQUEST['referto'])) {
	SchoorbsTPL::error(Lang::_('No url to refer to was provided!'));
}

if (!isset($_REQUEST['returnto'])) {
	SchoorbsTPL::error(Lang::_('No url to return to after a negative answer was provided!'));
}

if (empty($_REQUEST['returnto'])) {
	SchoorbsTPL::error(Lang::_('No url to return to after a negative answer was provided!'));
}

SchoorbsTPL::populateVar('question', unslashes($_REQUEST['question']));
SchoorbsTPL::populateVar('referTo', unslashes($_REQUEST['referto']));
SchoorbsTPL::populateVar('returnTo', unslashes($_REQUEST['returnto']));
SchoorbsTPL::renderPage('yes-no');
