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
    /**
     * Set Input_AllTests::main as main method that should be started 
     * with PHPUnit
     * 
     * @ignore
     */
    define('PHPUnit_MAIN_METHOD', 'Input_AllTests::main');
}

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 * 
 * @ignore
 */
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

/**
 * Test all input_*-Functions
 * 
 * @package Schoorbs-Test
 * @subpackage Input
 * @return 
 */
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
        $suite->addTestSuite('Input_NameTest');
        $suite->addTestSuite('Input_DescriptionTest');
        $suite->addTestSuite('Input_CapacityTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Input_AllTests::main') {
    Input_AllTests::main();
}