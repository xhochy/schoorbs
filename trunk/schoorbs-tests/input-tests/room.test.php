<?php
/**
 * This file tests the input_Room function
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
 * Testsuite for the input checking and getting of input_Area()
 * 
 * @package Schoorbs-Test
 * @subpackage Input
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_RoomTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Standard works?
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNormal()
	{
		unset($_GET['area']);
        unset($_POST['area']);
        unset($_COOKIE['area']);
        unset($_REQUEST['area']);
        
        unset($_GET['room']);
        unset($_POST['room']);
        unset($_COOKIE['room']);
        unset($_REQUEST['room']);
        
        $_REQUEST['room'] = 1;
        
        $this->assertEquals(1, input_Room());
	}
	
	/**
	 * When not set, is the default returned?
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDefault()
	{
		unset($_GET['area']);
        unset($_POST['area']);
        unset($_COOKIE['area']);
        unset($_REQUEST['area']);
        
        unset($_GET['room']);
        unset($_POST['room']);
        unset($_COOKIE['room']);
        unset($_REQUEST['room']);
        
        $this->assertEquals(get_default_room(get_default_area()), input_Room());
	}
}
