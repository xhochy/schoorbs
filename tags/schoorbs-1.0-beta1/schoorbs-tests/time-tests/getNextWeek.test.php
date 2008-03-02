<?php
/**
 * This file tests the getNextWeek function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Time
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 * 
 * @ignore
 */
define('SCHOORBS_NOGUI', true);

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/time.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';

## Test ##

/**
 * Testsuite for the getNextWeek function
 * 
 * @package Schoorbs-Test
 * @subpackage Time
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Time_GetNextWeekTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Just the a day change
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDayChange()
    {
		// Today 3.5.2007
		// Next Week 10.5.2007
		
		list($day, $month, $year) = getNextWeek(3, 5, 2007);
		
		$this->assertEquals(10, $day);
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
		// Today 30.4.2007
		// Next Week 7.5.2007
		
		list($day, $month, $year) = getNextWeek(30, 4, 2007);
		
		$this->assertEquals(7, $day);
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
		// Today 31.12.2006
		// Next Week 7.1.2007
		
		list($day, $month, $year) = getNextWeek(31, 12, 2006);
		
		$this->assertEquals(7, $day);
		$this->assertEquals(1, $month);
		$this->assertEquals(2007, $year);
    }
}
