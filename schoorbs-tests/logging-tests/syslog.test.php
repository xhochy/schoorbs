<?php
/**
 * This file tests the syslog-logging-backend
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs-Test
 * @subpackage Logging
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Main Schoorbs Code Includes ##

require_once dirname(__FILE__).'/../../schoorbs-includes/logging/syslog.php';

## PHPUnit Includes ##
 
require_once 'PHPUnit/Framework.php';

## Test ##

/**
 * Testsuite for the syslog-logging-backend
 * 
 * @package Schoorbs-Test
 * @subpackage Logging
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */ 
class Logging_SyslogTest extends PHPUnit_Framework_TestCase
{
	protected function setUp() 
	{
		global $_SCHOORBS;
		
		schoobsLogStart_Backend();
		
		$_SCHOORBS['logging']['syslog-facility'] = 'Schoorbs';
		$_SCHOORBS['logging']['syslog-priority'] = LOG_INFO;
	}

	/**
	 * Checks if room is returned when entering room
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */	
    public function testLine()
    {
        $syslog1 = tempnam('/tmp', 'schoorbs-test-logging-syslog');
        $syslog2 = tempnam('/tmp', 'schoorbs-test-logging-syslog');
         
        file_put_contents($syslog1, file_get_contents(TestConfiguration::$sSyslogLocation));
        
        $sLine = 'Schoorbs-Tests->Logging->Logging_SyslogTest->testLine: '.mt_rand();
        
        schoorbsLogWriteLine_Backend($sLine);
        
        // Wait for syslog to flush
        sleep(2);
        
        file_put_contents($syslog2, file_get_contents(TestConfiguration::$sSyslogLocation));
        
        exec('diff '.escapeshellarg($syslog1).' '.escapeshellarg($syslog2), $aOutput);
        
        $sOutput = implode(' ', $aOutput);
        
        if (strpos($sOutput, $sLine) === FALSE) {
        	$this->fail('Couldn\'t find output in syslog.');
        }
        
        unlink($syslog1);
        unlink($syslog2);
    }
}
