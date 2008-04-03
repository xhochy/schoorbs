<?php
/**
 * This file tests the input_Description function
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
 * Testsuite for the input checking and getting of input_Description()
 * 
 * @package Schoorbs-Test
 * @subpackage Input
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_DescriptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Standard works?
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testNormal()
	{
		unset($_GET['description']);
        unset($_POST['description']);
        unset($_COOKIE['description']);
        unset($_REQUEST['description']);
                
        $_REQUEST['description'] = 'test';
        
        $this->assertEquals('test', input_Description());
	}
	
	/**
	 * When not set, is the default returned?
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function testDefault()
	{
		unset($_GET['description']);
        unset($_POST['description']);
        unset($_COOKIE['description']);
        unset($_REQUEST['description']);
        
        $this->assertEquals('', input_Description());
	}
}
