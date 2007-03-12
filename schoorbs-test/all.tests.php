<?php
/**
 * The General Testsuite, executes all Tests
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}

## Defines ##

define('SCHOORBS_NOGUI',true);

## PHPUnit Includes ##

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

## Underlying Test Suites ##
 
require_once 'input.tests.php';

## The Testsuite ##
 
class AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
        $suite->addTest(Input_AllTests::suite());
 
        return $suite;
    }
}
 
if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}