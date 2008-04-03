<?php
/**
 * This file tests the input_Name function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/input.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';

## Test ##

/**
 * Testsuite for the input checking and getting of input_Name()
 * 
 * @package Schoorbs-Test
 * @subpackage Input
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_NameTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if room is returned when entering room
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testSth()
    {
        unset($_GET['name']);
        unset($_POST['name']);
        unset($_COOKIE['name']);
        unset($_REQUEST['name']);
 
 		$_REQUEST['name'] = 'room';
 
        $this->assertEquals('room', input_Name());
    }
    
    /**
	 * Checks if an exception is thrown if type isn't set
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testNotSet()
    {
        unset($_GET['name']);
        unset($_POST['name']);
        unset($_COOKIE['name']);
        unset($_REQUEST['name']);
 
        $this->setExpectedException('Exception');
        input_Name();
    }
    
    /**
	 * Checks if an exception is thrown if type is empty
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testEmpty()
    {
        unset($_GET['name']);
        unset($_POST['name']);
        unset($_COOKIE['name']);
        unset($_REQUEST['name']);
 
 		$_REQUEST['name'] = '';
 
        $this->setExpectedException('Exception');
        input_Name();
    }
}
