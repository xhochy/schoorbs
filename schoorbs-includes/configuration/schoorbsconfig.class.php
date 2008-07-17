<?php
/**
 * Configuration manager for Schoorbs
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Configuration
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/// Main Configuration class ///

/**
 * Configuration manager for Schoorbs
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Configuration
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */
class SchoorbsConfig {
	/// private static variables ////
	
	/**
	 * The configuration source which will be used.
	 *
	 * @var ISchoorbsConfigSource
	 */
	private static $oSource = null;

	/// public static functions ///
	
	/**
	 * Get a configuration entry
	 * 
	 * @param $sKey string
	 * @param $sDefault string
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getOption($sKey, $sDefault = '') {
		// Try to get the option from the ConfigurationSource
		$sResult = self::getSource()->get($sKey);
		// If this failed return the default value
		if ($sResult == null) {
			return $sDefault;
		}

		return $sResult;
	}
	
	/**
	 * Connect to the choosen configuration source
	 *
	 * -> Look up the source type
	 * -> Include the code
	 * -> Create an instance of this type
	 *
	 * @return ISchoorbsConfigSource
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function connectToSource() {
		global $_BASE_CONFIG;
		
		$sSource = $_BASE_CONFIG['configuration']['source'];
		
		require_once dirname(__FILE__).'/'.
			strtolower($sSource).'configsource.class.php';
		$cSource = new ReflectionClass(ucwords($sSource).'ConfigSource');
		return self::$oSource = 
			$cSource->newInstance($_BASE_CONFIG['configuration']);
	}

	/** 
	 * Get the reference to the current configuration source
	 *
	 * @return ISchoorbsConfigSource
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getSource() {
		// If we are not already connected to a source do this!
		if (self::$oSource == null) {
			return self::connectToSource();
		}
		return self::$oSource;
	}
}
