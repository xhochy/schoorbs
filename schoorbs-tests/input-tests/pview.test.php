<?php
/**
 * This file tests the input_PView function
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 * 
 * @ignore
 */
define('SCHOORBS_NOGUI',true);

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/input.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';

## Test ##

/**
 * Testsuite for the input checking and getting of input_PView()
 * 
 * @package Schoorbs-Test
 * @subpackage Input
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_PViewTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if empty values are accepted
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testEmpty()
    {
        unset($_GET['pview']);
        unset($_POST['pview']);
        unset($_COOKIE['pview']);
        unset($_REQUEST['pview']);
 
        $this->assertEquals(0, input_PView());
    }
    
    /**
	 * Checks if valid values are accepted
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testSth()
    {
        unset($_GET['pview']);
        unset($_POST['pview']);
        unset($_COOKIE['pview']);
        unset($_REQUEST['pview']);
        
        $_REQUEST['pview'] = 1;
 
        $this->assertEquals(1, input_PView());
    }
}
