<?php
/**
 * The Testsuite for all database related functions
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Database
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
 
 
/*** ATTENTION !!! ***/
// Tests with backend=pgsql are not supported at the moment since this backend
// uses a global variable to store the connection
 
if (!defined('PHPUnit_MAIN_METHOD')) {
    /**
     * Set Input_AllTests::main as main method that should be started 
     * with PHPUnit
     * 
     * @ignore
     */
    define('PHPUnit_MAIN_METHOD', 'Database_AllTests::main');
}

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

## Helper includes ##

require_once 'database.helper.php';

// Ignore Creole code in the code coverage report.
PHPUnit_Util_Filter::addDirectoryToFilter(realpath(dirname(__FILE__).'/../schoorbs-includes/database/creole'));

## The Database Tests ##

//require_once 'rest-tests/getroomid.test.php';

## The Testsuite ##

/**
 * Test all Database-Functions
 * 
 * @package Schoorbs-Test
 * @subpackage Database
 */
class Database_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
        //$suite->addTestSuite('REST_GetroomidTest');
        
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Database_AllTests::main') {
    Database_AllTests::main();
}
