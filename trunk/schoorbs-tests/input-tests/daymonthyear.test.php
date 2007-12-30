<?php
/**
 * This file tests the input_DayMonthYear function
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
class Input_DayMonthYearTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if today is returned if no date is given
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testToday()
	{
		unset($_GET['day']);
        unset($_POST['day']);
        unset($_COOKIE['day']);
        unset($_REQUEST['day']);
        
        unset($_GET['month']);
        unset($_POST['month']);
        unset($_COOKIE['month']);
        unset($_REQUEST['month']);
        
        unset($_GET['year']);
        unset($_POST['year']);
        unset($_COOKIE['year']);
        unset($_REQUEST['year']);
		
		list($day, $month, $year) = input_DayMonthYear();
		
		$this->assertEquals(date('d'), $day);
		$this->assertEquals(date('m'), $month);
		$this->assertEquals(date('Y'), $year);
	}
	
	/**
	 * Check if 30th Februrary is automatically corrected
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function test30Feb()
	{
		unset($_GET['day']);
        unset($_POST['day']);
        unset($_COOKIE['day']);
        unset($_REQUEST['day']);
        
        unset($_GET['month']);
        unset($_POST['month']);
        unset($_COOKIE['month']);
        unset($_REQUEST['month']);
        
        unset($_GET['year']);
        unset($_POST['year']);
        unset($_COOKIE['year']);
        unset($_REQUEST['year']);
        
        $_REQUEST['day'] = 30;
        $_REQUEST['month'] = 2;
        $_REQUEST['year'] = 2007;
        
        list($day, $month, $year) = input_DayMonthYear();
		
		$this->assertEquals(28, $day);
		$this->assertEquals(2, $month);
		$this->assertEquals(2007, $year);
	}
	
	/**
	 * Check if numbers with a letter are ignored
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDayLetters()
	{
		unset($_GET['day']);
        unset($_POST['day']);
        unset($_COOKIE['day']);
        unset($_REQUEST['day']);
        
        unset($_GET['month']);
        unset($_POST['month']);
        unset($_COOKIE['month']);
        unset($_REQUEST['month']);
        
        unset($_GET['year']);
        unset($_POST['year']);
        unset($_COOKIE['year']);
        unset($_REQUEST['year']);
        
        $_REQUEST['day'] = 'e2e';
        $_REQUEST['month'] = 2;
        $_REQUEST['year'] = 2007;
        
        list($day, $month, $year) = input_DayMonthYear();
		
		$this->assertEquals(1, $day);
		$this->assertEquals(2, $month);
		$this->assertEquals(2007, $year);
	}
	
	/**
	 * Check if numbers with a letter are ignored
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testMonthLetters()
	{
		unset($_GET['day']);
        unset($_POST['day']);
        unset($_COOKIE['day']);
        unset($_REQUEST['day']);
        
        unset($_GET['month']);
        unset($_POST['month']);
        unset($_COOKIE['month']);
        unset($_REQUEST['month']);
        
        unset($_GET['year']);
        unset($_POST['year']);
        unset($_COOKIE['year']);
        unset($_REQUEST['year']);
        
        $_REQUEST['day'] = 2;
        $_REQUEST['month'] = 'e2e';
        $_REQUEST['year'] = 2007;
        
        list($day, $month, $year) = input_DayMonthYear();
		
		$this->assertEquals(2, $day);
		$this->assertEquals(1, $month);
		$this->assertEquals(2007, $year);
	}
	
	/**
	 * Check if numbers with a letter are ignored
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testYearLetters()
	{
		unset($_GET['day']);
	        unset($_POST['day']);
        	unset($_COOKIE['day']);
	        unset($_REQUEST['day']);
	        
	        unset($_GET['month']);
	        unset($_POST['month']);	
	        unset($_COOKIE['month']);
	        unset($_REQUEST['month']);
	        
	        unset($_GET['year']);
	        unset($_POST['year']);
	        unset($_COOKIE['year']);
	        unset($_REQUEST['year']);
        
        	$_REQUEST['day'] = 2;
	        $_REQUEST['month'] = 2;
        	$_REQUEST['year'] = 'sgs';
        
	        list($day, $month, $year) = input_DayMonthYear();
		
		$this->assertEquals(2, $day);
		$this->assertEquals(2, $month);
		$this->assertEquals(1970, $year);
	}
}
