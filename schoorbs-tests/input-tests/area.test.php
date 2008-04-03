<?php
/**
 * This file tests the input_Area function
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
class Input_AreaTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if the default area is returned, if there is no entry 'area' set.
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testGetDefaultArea_NotSet()
    {
        unset($_GET['area']);
        unset($_POST['area']);
        unset($_COOKIE['area']);
        unset($_REQUEST['area']);
 
        $this->assertEquals(get_default_area(), input_Area());
    }
 
 	/**
 	 * Checks if the deafult area is returned, if allways an empty string is defined
 	 * 
 	 * @author Uwe L. Korn <uwelk@xhochy.org>
 	 */
    public function testGetDefaultArea_Empty()
    {
		$_GET['area'] = '';
        $_POST['area'] = '';
        $_COOKIE['area'] = '';
        $_REQUEST['area'] = ''; 
 
        $this->assertEquals(get_default_area(), input_Area());
    }
    
    /**
     * The area should be extracted out of REQUEST, so we are able to get it from all GPC-Arrays
     * 
     * @author Uwe L. Korn <uwelk@xhochy.org>
     */
    public function testGetArea_OutOf_REQUEST()
    {
    	unset($_GET['area']);
        unset($_POST['area']);
        unset($_COOKIE['area']);
        unset($_REQUEST['area']);
        
        $_REQUEST['area'] = get_default_area();
        
        $this->assertEquals(get_default_area(), input_Area());
    }
    
    /**
     * Test, if the default area is returned, when inserting a string into the area field
     * 
     * @author Uwe L. Korn <uwelk@xhochy.org>
     */
    public function testGetArea_String()
    {
    	unset($_GET['area']);
        unset($_POST['area']);
        unset($_COOKIE['area']);
        unset($_REQUEST['area']);
        
        $_REQUEST['area'] = 'sss';
        
        $this->assertEquals(get_default_area(), input_Area());
    }
}
