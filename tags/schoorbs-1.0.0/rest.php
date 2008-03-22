<?php
/**
 * Handles incoming REST-requests
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/** The REST functions/plugins */
require_once 'schoorbs-includes/rest.functions.php';

// just let the SchoorbsREST class handle everything
SchoorbsREST::handleRequest();
