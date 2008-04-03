<?php
/**
 * This file tests the getperiodid REST-function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/rest.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

## Test ##

/**
 * Testsuite for the getperiodid REST-function
 * 
 * @package Schoorbs-Test
 * @subpackage REST
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class REST_GetperiodidTest extends PHPUnit_Extensions_OutputTestCase
{
	/**
	 * Setup the database tables
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	protected function setUp()
	{
		global $periods;
		
		$periods = array('p1', 'p2');
	}

	/**
	 * Test with an existing period
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testPeriodExist()
    {
		unset($_GET['name']);
		unset($_POST['name']);
		unset($_REQUEST['name']);
		
		$_REQUEST['name'] = 'p2';
		
		$this->expectOutputRegex('/<period_id>1<\/period_id>/');
		
		$_REQUEST['call'] = 'getPeriodID';
		SchoorbsREST::handleRequest();
    }
    
    /**
	 * Test with an non-existing period
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testPeriodNonExist()
    {
		unset($_GET['name']);
		unset($_POST['name']);
		unset($_REQUEST['name']);
		
		$_REQUEST['name'] = 'p3';
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'getPeriodID';
		SchoorbsREST::handleRequest();
    }
}
