<?php
/**
 * The General Testsuite, executes all Tests
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    /**
     * Set AllTests::main as main method that should be started with PHPUnit 
     */
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}

## Defines ##

/**
 * Define that we are running Schoorbs without a GUI
 */
define('SCHOORBS_NOGUI',true);

## PHPUnit Includes ##

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

## Check for a suitable config.inc.php ##

if (!file_exists(dirname(__FILE__).'/../config.inc.php')) {
    $sHost = php_uname('n');
    
    $sDir = dirname(__FILE__).'/buildbot-test-configurations/'.$sHost;
        
    echo "# Building Test Environment for ${sUser}@${sHost}\n";
    
    if (file_exists($sDir.'/config.inc.php')) {
        echo "## Found suitable config.inc.php\n";
        copy($sDir.'/config.inc.php', dirname(__FILE__).'/../config.inc.php');
    } else {
        echo "## Didn't find suitable config.inc.php\n";
    }
    
    if (file_exists($sDir.'/pre-test.sh')) {
           system('/usr/bin/env sh '.escapeshellarg($sDir.'/pre-test.sh').' '.escapeshellarg(realpath(dirname(__FILE__).'/../')));
    }
} else {
    echo "# Using given environment for tests\n";
}

/** Include the configuration */
require_once dirname(__FILE__).'/../config.inc.php';

require_once 'test.configuration.php';

/** Override session module, since some of them are not yet suitable for the unittests */
$auth["session"] = "http";

## Underlying Test Suites ##
 
require_once 'input.tests.php';
require_once 'logging.tests.php';

## The Testsuite ##
 
/**
 * Interface Class for all available Tests
 *  
 * @package Schoorbs-Test
 */
class AllTests
{
    /**
     * Start the Tests 
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    /**
     * Get all Tests
     * 
     * @return PHPUnit_Framework_TestSuite Suite containing all available Tests
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit');
 
        $suite->addTest(Input_AllTests::suite());
        $suite->addTest(Logging_AllTests::suite());
 
        return $suite;
    }
}
 
if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}
