<?php
/**
 * This file tests the toPeriodString function
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
 * Testsuite for the toPeriodString function
 * 
 * @package Schoorbs-Test
 * @subpackage Time
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Time_ToPeriodStringTest extends PHPUnit_Framework_TestCase
{
	/**
	 * output periods
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testDefaultPeriods()
    {
		global $periods;
		
		// fake period array
		$periods = array(1, 1, 1, 1);
		
		$start_period = 1;
		$dur = 2 * 60;
		$units = null;
		
		toPeriodString($start_period, $dur, $units);
		
		$this->assertEquals(2, $dur);
		$this->assertEquals(get_vocab('periods'), $units);
    }
    
    /**
	 * output one day
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testOneDay()
    {
		global $periods;
		
		// fake period array
		$periods = array(1, 1, 1, 1);
		
		$start_period = 0;
		$dur = 4 * 60;
		$units = null;
		
		toPeriodString($start_period, $dur, $units);
		
		$this->assertEquals(1, $dur);
		$this->assertEquals(get_vocab('days'), $units);
    }
    
    /**
	 * outputs two+ days
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testMutipleWholeDays()
    {
		global $periods;
		
		// fake period array
		$periods = array(1, 1, 1, 1);
		
		$start_period = 0;
		$dur = 4 * 60 * 60 * 24;
		$units = null;
		
		toPeriodString($start_period, $dur, $units);
		
		$this->assertEquals(4, $dur);
		$this->assertEquals(get_vocab('days'), $units);
    }
    
    /**
	 * outputs 2 periods (overflow)
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testTwoAndAHalfDays()
    {
		global $periods;
		
		// fake period array
		$periods = array(1, 1, 1, 1);
		
		$start_period = 0;
		$dur = 10 * 60;
		$units = null;
		
		toPeriodString($start_period, $dur, $units);
		
		$this->assertEquals(2, $dur);
		$this->assertEquals(get_vocab('periods'), $units);
    }
}
