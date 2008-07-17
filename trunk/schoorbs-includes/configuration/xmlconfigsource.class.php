<?php
/**
 * Xml source for configurations
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Configuration
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/// Xml source for configurations ///

/**
 * Xml source for configurations
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Configuration
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */
class XmlConfigSource {

	/// private variables ///

	/**
	 * The configuration as XML
	 *
	 * @var SimpleXML
	 **/
	private $oXml;

	/// public functions ///

	/**
	 * Get an entry
	 * 
	 * @param $sKey string
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function get($sKey) {
		$aResult = $this->oXml->xpath('/*/option[@key="'.$sKey.'"]');
		return $aResult[0]['value'];
	}

	/**
	 * Creates a new instance of this class and loads the configuration
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function __construct($aOptions) {
		$this->oXml = simplexml_load_file($aOptions['filename']);
	}
}
