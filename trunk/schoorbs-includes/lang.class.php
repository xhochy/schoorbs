<?php
/**
 * Translation handler
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Internationalization
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/// Main Language class ///

/**
 * Translation handler
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Internationalization
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
class Lang {

	/// public static functions ///
	
	/**
	 * Translate a string into the language which fits best to the user.
	 *
	 * @param $sString string
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function _($sString) {
		return self::getUserLanguage()->translate($sString);
	}
	
	/**
	 * Check if a specific language exists
	 *
	 * @param $sLang string
	 * @return bool
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function languageExists($sLang) {
		return file_exists(self::getLanguageFilename($sLang));
	}
	
	/**
	 * Gets the expected path to a specific language file
	 *
	 * @param $sLang string
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getLanguageFilename($sLang) {
		return realpath(dirname(__FILE__).'/lang/schoorbs-'.$sLang
			.'.po-xml');
	}
	
	/**
	 * Get the a Lang instance for the best fitting user language
	 *
	 * @return Lang
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getUserLanguage() {
		static $oLang = null;
		
		if ($oLang !== null) return $oLang;
		
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			// Split up the HTTP-language string
			$aLangSpecifiers = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$aLangs = array();
			// Parse the HTTP language string
			foreach ($aLangSpecifiers as $sSpecifier)
			{
				// Language;Priority
				if (preg_match('/([a-zA-Z\-]+);q=([0-9\.]+)/', $sSpecifier, $aMatches)) {
					$sLangCode = preg_replace('/\\-([a-z]+)/e', '\'_\'.strtoupper(\'\\1\')', $aMatches[1]);
					$aLangs[$sLangCode] = $aMatches[2];
				// Language
				} else if (preg_match('/([a-zA-Z\-]+)/', $sSpecifier, $aMatches)) {
					$sLangCode = preg_replace('/\\-([a-z]+)/e', '\'_\'.strtoupper(\'\\1\')', $aMatches[1]);
					$aLangs[$sLangCode] = 1.0;
				}
			}
			// Sort the languages by their priorities
			arsort($aLangs, SORT_NUMERIC);
			
			// Get the best available language
			foreach ($aLangs as $sLang=>$nPriority) {
				if (self::languageExists($sLang)) {
					$oLang = self::getLanguage($sLang);
					return $oLang;
				}
				// If we have an xx_XX-Langcode, try xx too
				if (preg_match('/([a-z]+)_([A-Z]+)/', $sLang, $aMatches)) {
					if (self::languageExists($aMatches[1])) {
						$oLang = self::getLanguage($aMatches[1]);
						return $oLang;
					} 
				}
			}
		} 
		
		// In the case we haven't found a fitting language, use english.
		$oLang = self::getLanguage('en');
		return $oLang;
	}
	
	public static function getLanguage($sLang) {
		if ($sLang == 'en') {
			return new Lang_EN();
		} else {
			$oLang = new Lang();
			$oLang->feedXml(simplexml_load_file(self::getLanguageFilename($sLang)));
			return $oLang;
		}
	}
	
	/// public functions ///
	
	/**
	 * @var array
	 */
	private $aTranslations = array();
	
	/**
	 * Initialise a new Lang object
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function __construt() {
	}
	
	/**
	 * Read translation data from a xml-file with the following format
	 *
	 * <translation>
	 *   <string id="<untranslated" translated="<translated>" />
	 * </translation>
	 *
	 * @param $oXml SimpleXMLElement
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function feedXml(SimpleXMLElement $oXml) {
		foreach ($oXml->string as $sEntry) {
			$this->aTranslations[strval($sEntry['id'])] = 
				strval($sEntry['translated']);
		}
	}
	
	/**
	 * Translate a string into the specific language
	 *
	 * If a string couldn't be translated, return the input
	 *
	 * @param $sString
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function translate($sString) {
		if (isset($this->aTranslations[$sString])) {
			return $this->aTranslations[$sString];
		} else {
			return $sString;
		}
	}
}

/**
 * Fake english translation
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Internationalization
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */
class Lang_EN extends Lang {

	/**
	 * Do not translate the string, just return the input
	 *
	 * @param $sString string
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public function translate($sString) {
		return $sString;
	}
}
