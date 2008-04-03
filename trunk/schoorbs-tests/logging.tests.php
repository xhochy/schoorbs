<?php
/**
 * The Testsuite for all logging related functions
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Logging
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

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

## The Logging Tests ##

require_once 'logging-tests/syslog.test.php';
 
## The Testsuite ##

/**
 * Test all logging-Functions
 * 
 * @package Schoorbs-Test
 * @subpackage Logging
 */
class Logging_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
        $suite->addTestSuite('Logging_SyslogTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Logging_AllTests::main') {
    Logging_AllTests::main();
}
