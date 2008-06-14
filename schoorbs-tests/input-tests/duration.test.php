<?php
/**
 * This file tests the input_Duration function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 * 
 * @ignore
 */
define('SCHOORBS_NOGUI',true);

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/input.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';

## Test ##

/**
 * Testsuite for the input checking and getting of input_DayMonthYear()
 * 
 * @package Schoorbs-Test
 * @subpackage Input
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_DurationTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if default values will be accepted
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNonPeriod_Default()
	{
		global $enable_periods, $periods;
		
		$enable_periods = false;
		$periods = null;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 1);
		$this->assertEquals($dur_units, 'seconds');
		$this->assertEquals($units, 1);
	}
	
	/**
	 * Checks if default values will be accepted
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testPeriod_Default()
	{
		global $enable_periods, $periods;
		
		$enable_periods = true;
		$periods = array('1', '2', '3');
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 1);
		$this->assertEquals($dur_units, 'periods');
		$this->assertEquals($units, 60);
	}
	
	/**
	 * Checks if false duration values will be handled right
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNonPeriod_FalseDuration()
	{
		global $enable_periods, $periods;
		
		$enable_periods = false;
		$periods = null;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['duration'] = 'a';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 1);
		$this->assertEquals($dur_units, 'seconds');
		$this->assertEquals($units, 1);
	}
	
	/**
	 * Checks if false duration values will be handled right
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testPeriod_FalseDuration()
	{
		global $enable_periods, $periods;
		
		$enable_periods = true;
		$periods = array('1', '2', '3');
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['duration'] = 'a';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 1);
		$this->assertEquals($dur_units, 'periods');
		$this->assertEquals($units, 60);
	}
	
	/**
	 * Checks if good values will pass through
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNonPeriod_AllOk()
	{
		global $enable_periods, $periods;
		
		$enable_periods = false;
		$periods = null;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['duration'] = 3;
        $_REQUEST['dur_units'] = 'hours';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 3);
		$this->assertEquals($dur_units, 'hours');
		$this->assertEquals($units, 3600);
	}
	
	/**
	 * Checks if good values will pass through
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testPeriod_AllOk()
	{
		global $enable_periods, $periods;
		
		$enable_periods = true;
		$periods = array('1', '2', '3');
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['duration'] = 3;
        $_REQUEST['dur_units'] = 'periods';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 3);
		$this->assertEquals($dur_units, 'periods');
		$this->assertEquals($units, 60);
	}
	
	/**
	 * Checks if false dur_units values will be handled right
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNonPeriod_FalseDurUnits()
	{
		global $enable_periods, $periods;
		
		$enable_periods = false;
		$periods = null;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['dur_units'] = 1;
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 1);
		$this->assertEquals($dur_units, 'seconds');
		$this->assertEquals($units, 1);
	}
	
	/**
	 * Checks if false dur_units values will be handled right
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testPeriod_FalseDurUnits()
	{
		global $enable_periods, $periods;
		
		$enable_periods = true;
		$periods = array('1', '2', '3');
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['dur_units'] = 1;
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 1);
		$this->assertEquals($dur_units, 'periods');
		$this->assertEquals($units, 60);
	}
	
	/**
	 * Checks if period entries lasting more than one day will go
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testPeriod_OverflowPeriods()
	{
		global $enable_periods, $periods, $period;
		
		$enable_periods = true;
		$periods = array('1', '2', '3');
		$period = 1;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['dur_units'] = 'periods';
        $_REQUEST['duration'] = '5';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 1442);
		$this->assertEquals($dur_units, 'periods');
		$this->assertEquals($units, 60);
	}
	
	/**
	 * Checks if period entries lasting one day will go
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testPeriod_OneDay()
	{
		global $enable_periods, $periods, $period;
		
		$enable_periods = true;
		$periods = array('1', '2', '3');
		$period = 0;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['dur_units'] = 'days';
        $_REQUEST['duration'] = '1';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 3);
		$this->assertEquals($dur_units, 'periods');
		$this->assertEquals($units, 60);
	}
	
	/**
	 * Checks if non-period entries lasting 1+ years will go
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNonPeriod_Years()
	{
		global $enable_periods, $periods;
		
		$enable_periods = false;
		$periods = null;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['dur_units'] = 'years';
        $_REQUEST['duration'] = '2';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 2);
		$this->assertEquals($dur_units, 'years');
		$this->assertEquals($units, 52*7*24*60*60);
	}
	
	/**
	 * Checks if non-period entries lasting 1+ weeks will go
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNonPeriod_Weeks()
	{
		global $enable_periods, $periods;
		
		$enable_periods = false;
		$periods = null;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['dur_units'] = 'weeks';
        $_REQUEST['duration'] = '2';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 2);
		$this->assertEquals($dur_units, 'weeks');
		$this->assertEquals($units, 7*24*60*60);
	}
	
	/**
	 * Checks if non-period entries lasting 1+ days will go
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNonPeriod_Days()
	{
		global $enable_periods, $periods;
		
		$enable_periods = false;
		$periods = null;
		
		unset($_GET['duration']);
        unset($_POST['duration']);
        unset($_COOKIE['duration']);
        unset($_REQUEST['duration']);
        
        unset($_GET['dur_units']);
        unset($_POST['dur_units']);
        unset($_COOKIE['dur_units']);
        unset($_REQUEST['dur_units']);
        
        $_REQUEST['dur_units'] = 'days';
        $_REQUEST['duration'] = '2';
		
		list($duration, $dur_units, $units) = input_Duration();
		$this->assertEquals($duration, 2);
		$this->assertEquals($dur_units, 'days');
		$this->assertEquals($units, 24*60*60);
	}
}
