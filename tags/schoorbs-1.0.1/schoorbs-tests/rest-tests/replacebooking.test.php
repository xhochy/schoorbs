<?php
/**
 * This file tests the replaceBooking REST-function
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
 * Testsuite for the replaceBooking REST-function
 * 
 * @package Schoorbs-Test
 * @subpackage REST
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class REST_ReplaceBookingTest extends PHPUnit_Extensions_OutputTestCase
{
	/**
	 * Setup the database tables
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	protected function setUp()
	{
		global $enable_periods, $periods, $auth;
		
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
		$this->nBookingConflictingStarttime = mktime(12, $this->nBookingPeriod, 0, 
			$this->nBookingMonth, $this->nBookingDay-10, $this->nBookingYear);
		// 1 Period
		$this->nBookingConflictingEndtime = mktime(12, $this->nBookingPeriod + 1, 0, 
			$this->nBookingMonth, $this->nBookingDay-10, $this->nBookingYear);
		$this->sBookingName = 'TestBooking';
		$this->sBookingDescription = 'TestDescription';
		// Do not book, we want to test it later on
			
		// Authorise the testing System via Session/HTTP
		$_SERVER['PHP_AUTH_PW'] = 'TestPassword';
		$_SERVER['PHP_AUTH_USER'] = 'TestUser';
		// ... and Auth/Config
		$auth['user']['TestUser'] = 'TestPassword';
		$auth['admin'][] = 'TestUser';
		
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
		
		unset($_GET['name']);
		unset($_POST['name']);
		unset($_REQUEST['name']);
		
		unset($_GET['description']);
		unset($_POST['description']);
		unset($_REQUEST['description']);
		
		unset($_GET['type']);
		unset($_POST['type']);
		unset($_REQUEST['type']);
		
		unset($_GET['room']);
		unset($_POST['room']);
		unset($_REQUEST['room']);
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
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
		
		$_SERVER['PHP_AUTH_PW'] = $pw;
		$_SERVER['PHP_AUTH_USER'] = $user;
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
		
		$_REQUEST['call'] = 'replaceBooking';
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
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with false dates given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testFalseDatesGiven()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay + 100);
		$_REQUEST['month'] = array($this->nBookingMonth + 100);
		$_REQUEST['year'] = array($this->nBookingYear);
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with only dates+room given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDatesRoomGiven()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with only dates+room+period given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDatesRoomPeriodGiven()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with only dates+room+period+empty name given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDatesRoomPeriodEmptyNameGiven()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = '';
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with only dates+room+period+name given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDatesRoomPeriodNameGiven()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = $this->sBookingName;
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with only dates+room+period+name+description given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDatesRoomPeriodNameDescriptionGiven()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = $this->sBookingName;
		$_REQUEST['description'] = $this->sBookingDescription;
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with only dates+room+period+name+description+empty type given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDatesRoomPeriodNameDescriptionEmptyTypeGiven()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = $this->sBookingName;
		$_REQUEST['description'] = $this->sBookingDescription;
		$_REQUEST['type'] = '';
		
		$this->expectOutputRegex('/(<rsp)[\s]+(stat="fail">)/');
		$this->setExpectedException('Exception');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with all needed data given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testAllOk()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = $this->sBookingName;
		$_REQUEST['description'] = $this->sBookingDescription;
		$_REQUEST['type'] = 'I';
		
		$this->expectOutputRegex('/<made_booking>true<\/made_booking>/');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with all needed data given and a different user specified
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testAllOkDifferentUser()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = $this->sBookingName;
		$_REQUEST['description'] = $this->sBookingDescription;
		$_REQUEST['type'] = 'I';
		$_REQUEST['user'] = 'Test2User';
		
		$this->expectOutputRegex('/<made_booking>true<\/made_booking>/');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with all needed data given and an empty user specified
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testAllOkEmptyUser()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = $this->sBookingName;
		$_REQUEST['description'] = $this->sBookingDescription;
		$_REQUEST['type'] = 'I';
		$_REQUEST['user'] = '';
		
		$this->expectOutputRegex('/<made_booking>true<\/made_booking>/');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
	
	/**
	 * Test with all needed data given
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testConflictingBooking()
	{
		$this->cleanUpInputGlobals();
		
		$_REQUEST['day'] = array($this->nBookingDay-10);
		$_REQUEST['month'] = array($this->nBookingMonth);
		$_REQUEST['year'] = array($this->nBookingYear);
		$_REQUEST['room'] = $this->nRoom;
		$_REQUEST['period'] = $this->nBookingPeriod;
		$_REQUEST['name'] = $this->sBookingName;
		$_REQUEST['description'] = $this->sBookingDescription;
		$_REQUEST['type'] = 'I';
		
		// prebook a conflicting entry
		schoorbsCreateSingleEntry($this->nBookingConflictingStarttime, 
			$this->nBookingConflictingEndtime, 0, 0, $this->nRoom, 'TestUser',
			'TestBooking', 'I', 'TestDescription');
		
		// there's a conflict, but we don't care
		$this->expectOutputRegex('/<made_booking>true<\/made_booking>/');
		
		$_REQUEST['call'] = 'replaceBooking';
		SchoorbsREST::handleRequest();
	}
}
