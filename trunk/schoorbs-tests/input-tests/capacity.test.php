<?php
/**
 * This file tests the input_Capacity function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Defines ##

define('SCHOORBS_NOGUI',true);

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/input.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';

## Test ##

/**
 * Testsuite for the input checking and getting of input_Name()
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_CapacityTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if 1 is returned when entering 1
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testSth()
    {
        unset($_GET['capacity']);
        unset($_POST['capacity']);
        unset($_COOKIE['capacity']);
        unset($_REQUEST['capacity']);
 
 		$_REQUEST['capacity'] = 1;
 
        $this->assertEquals(1, input_Capacity());
    }
    
    /**
	 * Checks if 0 is returned when entering a string
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testString()
    {
        unset($_GET['capacity']);
        unset($_POST['capacity']);
        unset($_COOKIE['capacity']);
        unset($_REQUEST['capacity']);
 
 		$_REQUEST['capacity'] = 'dd';
 
        $this->assertEquals(0, input_Capacity());
    }
    
    /**
	 * Checks if an exception is thrown if type isn't set
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testNotSet()
    {
        unset($_GET['capacity']);
        unset($_POST['capacity']);
        unset($_COOKIE['capacity']);
        unset($_REQUEST['capacity']);
 
        $this->setExpectedException('Exception');
        input_Capacity();
    }
    
    /**
	 * Checks if an exception is thrown if type is empty
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testEmpty()
    {
        unset($_GET['capacity']);
        unset($_POST['capacity']);
        unset($_COOKIE['capacity']);
        unset($_REQUEST['capacity']);
 
 		$_REQUEST['capacity'] = '';
 
        $this->setExpectedException('Exception');
        input_Capacity();
    }
}