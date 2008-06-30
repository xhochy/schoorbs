<?php
/**
 * This file tests the login REST-function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 * 
 * @ignore
 */
define('SCHOORBS_NOGUI', true);

/**
 * Define that no HTTP headers should be sent when outputting a REST-result
 *
 * @ignore
 */
define('REST_NO_HEADERS', true);

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/rest.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

## Test ##

/**
 * Testsuite for the login REST-function
 * 
 * @package Schoorbs-Test
 * @subpackage REST
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class REST_LoginTest extends PHPUnit_Extensions_OutputTestCase
{
	/**
	 * Setup the Auth/Config & Session/HTTP Things
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	protected function setUp()
	{
		global $auth;
		
		// Authorise the testing System via Session/HTTP
		$_SERVER['PHP_AUTH_PW'] = 'TestPassword';
		$_SERVER['PHP_AUTH_USER'] = 'TestUser';
		// ... and Auth/Config
		$auth['user']['TestUser'] = 'TestPassword';
    }
    
    /**
	 * Test that we need to be authenticated
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testUnauthorised() 
	{
		$pw = $_SERVER['PHP_AUTH_PW'];
		$user = $_SERVER['PHP_AUTH_USER'];

		unset($_SERVER['PHP_AUTH_PW']);
		unset($_SERVER['PHP_AUTH_USER']);
	
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'login';
		SchoorbsREST::handleRequest();
		
		$_SERVER['PHP_AUTH_PW'] = $pw;
		$_SERVER['PHP_AUTH_USER'] = $user;
	}
	
	/**
	 * Test that we are authenticated
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testAuthorised() 
	{
		$this->expectOutputRegex('/(<username)[\s]+(value=")('.
			$_SERVER['PHP_AUTH_USER'].')(")[\s]*\/>/');
		
		$_REQUEST['call'] = 'login';
		SchoorbsREST::handleRequest();
	}
}
