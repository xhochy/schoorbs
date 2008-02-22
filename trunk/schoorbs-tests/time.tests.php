<?php
/**
 * The Testsuite for all time related functions
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Time
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
if (!defined('PHPUnit_MAIN_METHOD')) {
    /**
     * Set Input_AllTests::main as main method that should be started 
     * with PHPUnit
     * 
     * @ignore
     */
    define('PHPUnit_MAIN_METHOD', 'Logging_AllTests::main');
}

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 * 
 * @ignore
 */
define('SCHOORBS_NOGUI', true);

## Main Schoorbs Code Includes ##

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

## The Logging Tests ##

require_once 'time-tests/getYesterday.test.php';
require_once 'time-tests/getTomorrow.test.php';
require_once 'time-tests/getNextWeek.test.php';
require_once 'time-tests/getLastWeek.test.php';
 
## The Testsuite ##

/**
 * Test all logging-Functions
 * 
 * @package Schoorbs-Test
 * @subpackage Time
 * @return 
 */
class Time_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
        $suite->addTestSuite('Time_GetYesterdayTest');
        $suite->addTestSuite('Time_GetTomorrowTest');
        $suite->addTestSuite('Time_GetNextWeekTest');
        $suite->addTestSuite('Time_GetLastWeekTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Time_AllTests::main') {
    Logging_AllTests::main();
}
