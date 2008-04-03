<?php
/**
 * The Testsuite for all REST related functions
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage REST
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
if (!defined('PHPUnit_MAIN_METHOD')) {
    /**
     * Set Input_AllTests::main as main method that should be started 
     * with PHPUnit
     * 
     * @ignore
     */
    define('PHPUnit_MAIN_METHOD', 'REST_AllTests::main');
}

## Defines ##

/**
 * Define that no HTTP headers should be sent when outputting a REST-result
 */
define('REST_TESTING', true);

/**
 * Define that no HTTP headers should be sent when outputting a REST-result
 *
 * @ignore
 */
define('REST_NO_HEADERS', true);

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../schoorbs-includes/database/schoorbs_sql.php';
require_once dirname(__FILE__).'/../schoorbs-includes/rest.functions.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

## The REST Tests ##

require_once 'rest-tests/getroomid.test.php';
require_once 'rest-tests/getperiodid.test.php';
require_once 'rest-tests/checkfree.test.php';
require_once 'rest-tests/makebooking.test.php';
require_once 'rest-tests/replacebooking.test.php';
require_once 'rest-tests/login.test.php';
require_once 'rest-tests/general.test.php';

## The Testsuite ##

/**
 * Test all REST-Functions
 * 
 * @package Schoorbs-Test
 * @subpackage REST
 */
class REST_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
 		$suite->addTestSuite('REST_GeneralTest');
        $suite->addTestSuite('REST_GetroomidTest');
        $suite->addTestSuite('REST_GetperiodidTest');
        $suite->addTestSuite('REST_CheckfreeTest');
        $suite->addTestSuite('REST_MakeBookingTest');
        $suite->addTestSuite('REST_ReplaceBookingTest');
        $suite->addTestSuite('REST_LoginTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'REST_AllTests::main') {
    REST_AllTests::main();
}
