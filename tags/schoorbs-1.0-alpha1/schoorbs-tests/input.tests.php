<?php
/**
 * The Testsuite for all input related functions
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Input_AllTests::main');
}

## Defines ##

define('SCHOORBS_NOGUI',true);

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../schoorbs-includes/input.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

## The Input Tests ##
 
require_once 'input-tests/area.test.php';
require_once 'input-tests/daymonthyear.test.php';
require_once 'input-tests/room.test.php';
require_once 'input-tests/type.test.php';
require_once 'input-tests/name.test.php';
require_once 'input-tests/description.test.php';
require_once 'input-tests/capacity.test.php';

## The Testsuite ##

class Input_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
        $suite->addTestSuite('Input_AreaTest');
        $suite->addTestSuite('Input_DayMonthYearTest');
        $suite->addTestSuite('Input_RoomTest');
        $suite->addTestSuite('Input_TypeTest');
        $suite->addTestSuite('Input_TypeTest_Exceptions');
        $suite->addTestSuite('Input_NameTest');
        $suite->addTestSuite('Input_NameTest_Exceptions');
        $suite->addTestSuite('Input_DescriptionTest');
        $suite->addTestSuite('Input_CapacityTest');
        $suite->addTestSuite('Input_CapacityTest_Exceptions');
 
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Input_AllTests::main') {
    Input_AllTests::main();
}