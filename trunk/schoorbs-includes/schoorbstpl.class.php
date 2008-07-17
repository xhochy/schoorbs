<?php
/**
 * Template system for Schoorbs
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Layout
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */

/// Includes ///

/** The configuration manager */
require_once dirname(__FILE__).'/configuration/schoorbsconfig.class.php';
/** The language system */
require_once dirname(__FILE__).'/lang.class.php';

/// Main Theming class ///

/**
 * Template system for Schoorbs
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Layout
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @license http://opensource.org/licenses/mit-license.php MIT-style license
 */
class SchoorbsTPL {
	/// private static variables ///
	
	/**
	 * The page to be rendered
	 *
	 * @var string
	 */
	private static $sPage = 'index';
	
	/**
	 * The variables that should be exposed to the templates
	 *
	 * @var array
	 */
	private static $aVariables = array();

	/// public static functions ///

	/**
	 * Render a specific page
	 * 
	 * @param $sRenderedPage string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function renderPage($sRenderedPage) {
		// Always use UTF-8 since all needed characters are shipped with
		// it. If some translations were submitted in an other encoding,
		// convert them.
		header('Content-Type: text/html; charset=utf-8');
		// Prevent all Schoorbs pages from being cached since nearly
		// everything is dynamic content.
		// HTTP 1.0
		header('Pragma: no-cache');      
		// HTTP 1.1
		header("Cache-Control: no-cache, must-revalidate");   
		// Date in the past                 
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');    
		self::$sPage = $sRenderedPage;
		
		self::render('document');
	}
	
	/**
	 * Load a template file and parse the code
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $sPartName string
	 */
	public static function render($sPartName) {
		// Expose all stored variabels
		foreach (self::$aVariables as $sName=>$mVar) {
			$$sName = $mVar;
		}
		
		require self::getThemePath().'/'.$sPartName.'.tpl.php';
	}
	
	/**
	 * Render the page specific body
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function renderBody() {
		self::render(self::$sPage);
	}
	
	/** 
	 * Get the path to the theme directory
	 *
	 * This functions returns at the moment a static path which could be
	 * made in future versions dynamic, e.g. allowing to randomly choose a
	 * theme or introduce a theme after a specific time, for example when
	 * relaunching a site.
	 * 
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public static function getThemePath() {
		return dirname(__FILE__).'/../schoorbs-misc/themes/'
			.self::getThemeName();
	}
	
	/**
	 * Get the name of the used Theme
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @return string
	 */
	public static function getThemeName() {
		// defaults to contented6 as it ships with Schoorbs
		return SchoorbsConfig::getOption('theme-name', 'contented6');
	}
	
	/**
	 * Include a css-file with the right path
	 *
	 * @param $sCSSFile string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function includeCSS($sCSSFile) {
		echo '<link href="schoorbs-misc/themes/'.self::getThemeName().'/'
			.$sCSSFile.'" rel="stylesheet" type="text/css" />';
	}
	
	/**
	 * Include a JavaScript-file with the right path
	 *
	 * @param $sJSFile string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function includeJS($sJSFile) {
		echo '<script type="text/javascript" src="schoorbs-misc/themes/'
			.self::getThemeName().'/'.$sJSFile.'"></script>';
	}
	
	/**
	 * Create the url to the week-view page
	 *
	 * Fill by default with the values returned by input_*()
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $nArea int
	 * @param $nRoom int
	 * @param $nDayMonthYear array (day, month, year)
	 */
	public static function getWeekViewUrl($nArea = null, $nRoom = null, $aDayMonthYear = null) {
		if ($nArea === null) $nArea = input_Area();
		if ($nRoom === null) $nRoom = input_Room();
		if ($aDayMonthYear === null) $aDayMonthYear = input_DayMonthYear();
		
		return sprintf(
			'week-view.php?area=%d&amp;room=%d&amp;day=%d&amp;'
			.'month=%d&amp;year=%d', 
			$nArea, $nRoom, $aDayMonthYear[0], $aDayMonthYear[1],
			$aDayMonthYear[2]
		);
	}
	
	/**
	 * Create the url to the search page
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getSearchUrl() {
		return 'search.php';
	}
	
	/**
	 * Create the url to the help page
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function getHelpUrl() {
		return 'help.php';
	}
	
	/**
	 * Create the url to the admin page
	 *
	 * Fill by default with the values returned by input_*()
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $nArea int
	 */
	public static function getAdminUrl($nArea = null) {
		if ($nArea === null) $nArea = input_Area();
		
		return sprintf('administration.php?area=%d', $nArea);
	}
	
	/**
	 * Create the url to the month-view page
	 *
	 * Fill by default with the values returned by input_*()
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $nArea int
	 * @param $nRoom int
	 * @param $nDayMonthYear array (day, month, year)
	 */
	public static function getMonthViewUrl($nArea = null, $nRoom = null, $aDayMonthYear = null) {
		if ($nArea === null) $nArea = input_Area();
		if ($nRoom === null) $nRoom = input_Room();
		if ($aDayMonthYear === null) $aDayMonthYear = input_DayMonthYear();
		
		return sprintf(
			'month.php?area=%d&amp;room=%d&amp;day=%d&amp;'
			.'month=%d&amp;year=%d', 
			$nArea, $nRoom, $aDayMonthYear[0], $aDayMonthYear[1],
			$aDayMonthYear[2]
		);
	}
	
	/**
	 * Create the url to the day-view page
	 *
	 * Fill by default with the values returned by input_*()
	 *
	 * @return string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 * @param $nArea int
	 * @param $nRoom int
	 * @param $nDayMonthYear array (day, month, year)
	 */
	public static function getDayViewUrl($nArea = null, $nRoom = null, $aDayMonthYear = null) {
		if ($nArea === null) $nArea = input_Area();
		if ($nRoom === null) $nRoom = input_Room();
		if ($aDayMonthYear === null) $aDayMonthYear = input_DayMonthYear();
		
		return sprintf(
			'day-view.php?area=%d&amp;room=%d&amp;day=%d&amp;'
			.'month=%d&amp;year=%d', 
			$nArea, $nRoom, $aDayMonthYear[0], $aDayMonthYear[1],
			$aDayMonthYear[2]
		);
	}
	
	/**
	 * Make a variable visible to the template system
	 *
	 * @param $mVar mixed
	 * @param $sName string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function populateVar($sName, $mVar) {
		self::$aVariables[$sName] = $mVar;
	}
	
	/**
	 * Build an Url for internal links
	 *
	 * Adds all Parameters found in $_GET and inserts $aParameters in it
	 *
	 * @param $sPath string
	 * @param $aParameters array
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function makeInternalUrl($sPath, $aParameters = array()) {
		$aParams = array_merge($_GET, $aParameters);
		$aParams2 = array();
		// Masquerade all parameters for URL usage
		foreach ($aParams as $sKey=>$sValue) {
			$aParams2[] = urlencode($sKey).'='.urlencode($sValue);
		}		
		
		// No parameters are appended just return the path
		if (count($aParams) == 0) {
			return $sPath;
		} else {
			return $sPath.'?'.implode('&', $aParams2);
		}
	}
}
