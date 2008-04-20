<?php
/**
 * This file tests the toTimeString function
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
 * Testsuite for the toTimeString function
 * 
 * @package Schoorbs-Test
 * @subpackage Time
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Time_ToTimeStringTest extends PHPUnit_Framework_TestCase
{
	/**
	 * output seconds
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testSeconds()
    {
		$dur = 12;
		$units = null;
		
		toTimeString($dur, $units);
		
		$this->assertEquals(12, $dur);
		$this->assertEquals(get_vocab('seconds'), $units);
    }
    
    /**
	 * output minutes
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testMinutes()
    {
		$dur = 12 * 60;
		$units = null;
		
		toTimeString($dur, $units);
		
		$this->assertEquals(12, $dur);
		$this->assertEquals(get_vocab('minutes'), $units);
    }
    
    /**
	 * output hours
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testHours()
    {
		$dur = 12 * 60 * 60;
		$units = null;
		
		toTimeString($dur, $units);
		
		$this->assertEquals(12, $dur);
		$this->assertEquals(get_vocab('hours'), $units);
    }
    
    /**
	 * output days
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDays()
    {
		$dur = 24 * 60 * 60 * 2;
		$units = null;
		
		toTimeString($dur, $units);
		
		$this->assertEquals(2, $dur);
		$this->assertEquals(get_vocab('days'), $units);
    }
    
    /**
	 * output weeks
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testWeeks()
    {
		$dur = 24 * 60 * 60 * 2 * 7;
		$units = null;
		
		toTimeString($dur, $units);
		
		$this->assertEquals(2, $dur);
		$this->assertEquals(get_vocab('weeks'), $units);
    }
    
    /**
	 * output years
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testYears()
    {
		$dur = 24 * 60 * 60 * 2 * 7 * 52;
		$units = null;
		
		toTimeString($dur, $units);
		
		$this->assertEquals(2, $dur);
		$this->assertEquals(get_vocab('years'), $units);
    }
}
