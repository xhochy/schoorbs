<?php
/**
 * This file tests the getTomorrow function
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
 * Testsuite for the getTomorrow function
 * 
 * @package Schoorbs-Test
 * @subpackage Time
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Time_GetTomorrowTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Just the a day change
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDayChange()
    {
		// Tomorrow 4.5.2007
		// Today 3.5.2007
		
		list($day, $month, $year) = getTomorrow(3, 5, 2007);
		
		$this->assertEquals(4, $day);
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
		// Tomorrow 1.5.2007
		// Today 30.4.2007
		
		list($day, $month, $year) = getTomorrow(30, 4, 2007);
		
		$this->assertEquals(1, $day);
		$this->assertEquals(5, $month);
		$this->assertEquals(2007, $year);
    }
    
    /**
	 * Just the a year change
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testYearChange()
    {
		// Tomorrow 1.1.2007
		// Today 31.12.2006
		
		list($day, $month, $year) = getTomorrow(31, 12, 2006);
		
		$this->assertEquals(1, $day);
		$this->assertEquals(1, $month);
		$this->assertEquals(2007, $year);
    }
}
