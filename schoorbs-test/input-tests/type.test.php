<?php
/**
 * This file tests the input_Type function
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
require_once 'PHPUnit/Extensions/ExceptionTestCase.php';

## Test ##

/**
 * Testsuite for the input checking and getting of input_Area() //only Exceptions
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_TypeTest_Exceptions extends PHPUnit_Extensions_ExceptionTestCase
{
	/**
	 * Checks if an exception is thrown if type isn't set
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testNotSet()
    {
        unset($_GET['type']);
        unset($_POST['type']);
        unset($_COOKIE['type']);
        unset($_REQUEST['type']);
 
        $this->setExpectedException('Exception');
        input_Type();
    }
    
    /**
	 * Checks if an exception is thrown if type is empty
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testEmpty()
    {
        unset($_GET['type']);
        unset($_POST['type']);
        unset($_COOKIE['type']);
        unset($_REQUEST['type']);
 
 		$_REQUEST['type'] = '';
 
        $this->setExpectedException('Exception');
        input_Type();
    }
    
    /**
	 * Checks if an exception is thrown if type is junk
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testJunk()
    {
        unset($_GET['type']);
        unset($_POST['type']);
        unset($_COOKIE['type']);
        unset($_REQUEST['type']);
 
 		$_REQUEST['type'] = 'blabla';
 
        $this->setExpectedException('Exception');
        input_Type();
    }
}

/**
 * Testsuite for the input checking and getting of input_Type()
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_TypeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if room is returned when entering room
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testRoom()
    {
        unset($_GET['type']);
        unset($_POST['type']);
        unset($_COOKIE['type']);
        unset($_REQUEST['type']);
 
 		$_REQUEST['type'] = 'room';
 
        $this->assertEquals('room', input_Type());
    }
    
    /**
	 * Checks if area is returned when entering area
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testArea()
    {
        unset($_GET['type']);
        unset($_POST['type']);
        unset($_COOKIE['type']);
        unset($_REQUEST['type']);
 
 		$_REQUEST['type'] = 'area';
 
        $this->assertEquals('area', input_Type());
    }
}