<?php
/**
 * This file tests some general REST-functions
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## PHPUnit Includes ##

/** The PHPUnit Framework **/ 
require_once 'PHPUnit/Framework.php';
/** The OutputTestCase exteions for PHPUnit */
require_once 'PHPUnit/Extensions/OutputTestCase.php';

## Test ##

/**
 * Testsuite for some general REST-functions
 * 
 * @package Schoorbs-Test
 * @subpackage REST
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class REST_GeneralTest extends PHPUnit_Extensions_OutputTestCase
{
	/**
	 * Test what happens if we call an not-existing REST-function
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testCallNonExist() 
	{
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
				
		$_SERVER['REDIRECT_URL'] = 'http://localhost/REST/nonexistfunction';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test what happens if we call an url pointing not to the REST endpoint
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testCallNotRESTEndpoint() 
	{
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
				
		$_SERVER['REDIRECT_URL'] = 'http://localhost/';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test what happens if we call an url pointing only to the REST endpoint
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testCallOnlyRESTEndpoint() 
	{
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
				
		$_SERVER['REDIRECT_URL'] = 'http://localhost/REST/';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test what happens if we call an url containing non-alphanumeric characters
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testCallNonAlphaNum() 
	{
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
				
		$_SERVER['REDIRECT_URL'] = 'http://localhost/REST/sas-sss';
		SchoorbsREST::handleRequest();
	}
}
