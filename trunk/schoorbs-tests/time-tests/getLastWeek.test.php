<?php
/**
 * This file tests the getLastWeek function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Time
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/time.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';

## Test ##

/**
 * Testsuite for the getLastWeek function
 * 
 * @package Schoorbs-Test
 * @subpackage Time
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Time_GetLastWeekTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Just the a day change
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDayChange()
    {
		// Last Week 3.5.2007
		// Today 10.5.2007
		
		list($day, $month, $year) = getLastWeek(10, 5, 2007);
		
		$this->assertEquals(3, $day);
		$this->assertEquals(5, $month);
		$this->assertEquals(2007, $year);
    }
    
    /**
	 * Just the a month change
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testMonthChange()
    {
		// Last Week 30.4.2007
		// Today 7.5.2007
		
		list($day, $month, $year) = getLastWeek(7, 5, 2007);
		
		$this->assertEquals(30, $day);
		$this->assertEquals(4, $month);
		$this->assertEquals(2007, $year);
    }
    
    /**
	 * Just the a year change
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testYearChange()
    {
		// Last Week 31.12.2006
		// Today 7.1.2007
		
		list($day, $month, $year) = getLastWeek(7, 1, 2007);
		
		$this->assertEquals(31, $day);
		$this->assertEquals(12, $month);
		$this->assertEquals(2006, $year);
    }
}
