<?php
/**
 * This file tests the checkfree REST-function
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
 * Testsuite for the checkfree REST-function
 * 
 * @package Schoorbs-Test
 * @subpackage REST
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class REST_CheckfreeTest extends PHPUnit_Extensions_OutputTestCase
{
	/**
	 * Setup the database tables
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	protected function setUp()
	{
		global $enable_periods, $periods;
		
		DatabaseHelper::removeTestTables();
		DatabaseHelper::createTestTables();
		DatabaseHelper::flavourTblGlobals();
		$this->nArea = DatabaseHelper::addArea('test1');
		$this->sRoom = 'Test1';
		$this->nRoom = DatabaseHelper::addRoom($this->nArea, $this->sRoom, 
			'description', 2);
		
		// Booking at 10/31/2007 12:00
		$this->nBookingDay = 31;
		$this->nBookingMonth = 10;
		$this->nBookingYear = 2007;
		$this->nBookingPeriod = 1;
		$this->nBookingStarttime = mktime(12, $this->nBookingPeriod, 0, 
			$this->nBookingMonth, $this->nBookingDay, $this->nBookingYear);
		// 1 Period
		$this->nBookingEndtime = mktime(12, $this->nBookingPeriod + 1, 0, 
			$this->nBookingMonth, $this->nBookingDay, $this->nBookingYear);
		// Book, type => Internal
		schoorbsCreateSingleEntry($this->nBookingStarttime, 
			$this->nBookingEndtime, 0, 0, $this->nRoom, 'TestUser',
			'TestBooking', 'I', 'TestDescription');
		
		$enable_periods = true;
		$periods = array('p1', 'p2', 'p3');
	}
	
	/**
	 * Remove all may-set super-global inputs
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function cleanUpInputGlobals()
	{
		unset($_GET['day']);
		unset($_POST['day']);
		unset($_REQUEST['day']);
		
		unset($_GET['month']);
		unset($_POST['month']);
		unset($_REQUEST['month']);
		
		unset($_GET['year']);
		unset($_POST['year']);
		unset($_REQUEST['year']);
		
		unset($_GET['period']);
		unset($_POST['period']);
		unset($_REQUEST['period']);
		
		unset($_GET['room']);
		unset($_POST['room']);
		unset($_REQUEST['room']);
	}

	/**
	 * Test with nothing given
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testNoneGiven()
    {
		$this->cleanUpInputGlobals();
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'checkFree';
		SchoorbsREST::handleRequest();
    }
    
    /**
	 * Test with only dates given
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDatesGiven()
    {
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array(1,2);
		$_REQUEST['month'] = array(2,2);
		$_REQUEST['year'] = array(2007, 2007);
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'checkFree';
		SchoorbsREST::handleRequest();
    }
    
    /**
	 * Test with only dates + period given
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDatesPeriodGiven()
    {
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array(1,2);
		$_REQUEST['month'] = array(2,2);
		$_REQUEST['year'] = array(2007, 2007);
		$_REQUEST['period'] = 1;
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'checkFree';
		SchoorbsREST::handleRequest();
    }
    
    /**
	 * Test with satisfied data, but a non-exsiting date
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testFalseDateGiven()
    {
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array(1,3);
		$_REQUEST['month'] = array(19,2);
		$_REQUEST['year'] = array(2007, 2007);
		$_REQUEST['period'] = 1;
		$_REQUEST['room'] = $this->nRoom;
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'checkFree';
		SchoorbsREST::handleRequest();
    }
    
    /**
	 * Test with satisfied data
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testAllGivenNotFree()
    {
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['room'] = $this->nRoom;
		
		$this->expectOutputRegex('/<free>false<\/free>/');
		
		$_REQUEST['call'] = 'checkFree';
		SchoorbsREST::handleRequest();
    }
    
    /**
	 * Test with satisfied data
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testAllGivenFree()
    {
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay - 1);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['room'] = $this->nRoom;
		
		$this->expectOutputRegex('/<free>true<\/free>/');
		
		$_REQUEST['call'] = 'checkFree';
		SchoorbsREST::handleRequest();
    }
}
