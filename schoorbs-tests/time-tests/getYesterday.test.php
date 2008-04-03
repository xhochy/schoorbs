<?php
/**
 * This file tests the getYesterday function
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
 * Testsuite for the getYesterday function
 * 
 * @package Schoorbs-Test
 * @subpackage Time
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Time_GetYesterdayTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Just the a day change
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDayChange()
    {
		// Today 4.5.2007
		// Yesterday 3.5.2007
		
		list($day, $month, $year) = getYesterday(4, 5, 2007);
		
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
		// Today 1.5.2007
		// Yesterday 30.4.2007
		
		list($day, $month, $year) = getYesterday(1, 5, 2007);
		
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
		// Today 1.1.2007
		// Yesterday 31.12.2006
		
		list($day, $month, $year) = getYesterday(1, 1, 2007);
		
		$this->assertEquals(31, $day);
		$this->assertEquals(12, $month);
		$this->assertEquals(2006, $year);
    }
}
