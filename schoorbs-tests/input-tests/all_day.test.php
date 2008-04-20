<?php
/**
 * This file tests the input_All_Day function
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
 * Testsuite for the input checking and getting of input_All_Day()
 * 
 * @package Schoorbs-Test
 * @subpackage Input
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Input_All_DayTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Checks if empty values are accepted
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testEmpty()
    {
        unset($_GET['all_day']);
        unset($_POST['all_day']);
        unset($_COOKIE['all_day']);
        unset($_REQUEST['all_day']);
 
        $this->assertEquals('no', input_All_Day());
    }
    
    /**
	 * Checks lowercase yes
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testLowercaseYES()
    {
        unset($_GET['all_day']);
        unset($_POST['all_day']);
        unset($_COOKIE['all_day']);
        unset($_REQUEST['all_day']);
        
        $_REQUEST['all_day'] = 'yes';
 
        $this->assertEquals('yes', input_All_Day());
    }
    
    /**
	 * Checks uppercase yes
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testUppercaseYES()
    {
        unset($_GET['all_day']);
        unset($_POST['all_day']);
        unset($_COOKIE['all_day']);
        unset($_REQUEST['all_day']);
        
        $_REQUEST['all_day'] = 'YES';
 
        $this->assertEquals('yes', input_All_Day());
    }
    
    /**
	 * Checks mixedcase yes
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testMixedcaseYES()
    {
        unset($_GET['all_day']);
        unset($_POST['all_day']);
        unset($_COOKIE['all_day']);
        unset($_REQUEST['all_day']);
        
        $_REQUEST['all_day'] = 'yEs';
 
        $this->assertEquals('yes', input_All_Day());
    }
    
    /**
	 * Checks lowercase no
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testLowercaseNO()
    {
        unset($_GET['all_day']);
        unset($_POST['all_day']);
        unset($_COOKIE['all_day']);
        unset($_REQUEST['all_day']);
        
        $_REQUEST['all_day'] = 'no';
 
        $this->assertEquals('no', input_All_Day());
    }
    
    /**
	 * Checks uppercase no
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testUppercaseNO()
    {
        unset($_GET['all_day']);
        unset($_POST['all_day']);
        unset($_COOKIE['all_day']);
        unset($_REQUEST['all_day']);
        
        $_REQUEST['all_day'] = 'NO';
 
        $this->assertEquals('no', input_All_Day());
    }
    
    /**
	 * Checks mixedcase yes
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testMixedcaseNO()
    {
        unset($_GET['all_day']);
        unset($_POST['all_day']);
        unset($_COOKIE['all_day']);
        unset($_REQUEST['all_day']);
        
        $_REQUEST['all_day'] = 'nO';
 
        $this->assertEquals('no', input_All_Day());
    }
}
