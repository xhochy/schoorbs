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
			'month-view.php?area=%d&amp;room=%d&amp;day=%d&amp;'
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
	 * Display a error
	 *
	 * All variables given in $aParams will be populated to the template. 
	 * You can use this feature if you maybe want to display a sidebar on 
	 * some page where an error occurs, but not on all.
	 *
	 * Remark: This function will only display the error page but will not
	 *         exit the script, you have to do this manually. This gives you
	 *         the possiblity to return a specific exit code.
	 *
	 * @param $sErrorText string
	 * @param $aParams array
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function error($sErrorText, $aParams = null) {
		// If there were any parameters passed, we will populate them
		// here.
		if ($aParams !== null) {
			foreach ($aParams as $sKey=>$mValue) {
				// For perfomance improvements we could use 
				// at this place directly the self::$aVariables
				// Array but maybe in future the variable 
				// handling might change, so then we still will
				// only need to change it at one position.
				self::populateVar($sKey, $mValue);
			}
		}
		self::populateVar('SCHOORBS_ERROR', $sErrorText);
		self::renderPage('error');
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
	
	/**
	 * Build an url that leads the user to a yes/no-desicion
	 *
	 * @param $sQuestion string
	 * @param $sReferTo string
	 * @param $sReturnTo string
	 * @author Uwe L. Korn <uwelk@xhochy.org>
	 */
	public static function makeYesNoUrl($sQuestion, $sReferTo, $sReturnTo) {
		return 'yes-no.php?question='.urlencode($sQuestion).'&referto='
			.urlencode($sReferTo).'&returnto='.urlencode($sReturnTo);
	}
	
	/**
	 * Generates a Date selector with 3 select elements.
	 * 
	 * The select elements will have the following names/ids:
	 *  - <prefix>day
	 *  - <prefix>month
	 *  - <prefix>year
	 *
	 * @author Uwe L. Korn <uwelk@xhochy.com>
	 * @param $sPrefix string
	 * @param $nDate int
	 * @return string
	 */
	public static function generateDateSelector($sPrefix, $nDate) {
		// Get seperate Day, Month, Year values
		$nDay = intval(date('j', $nDate));
		$nMonth = intval(date('n', $nDate));
		$nYear = intval(date('Y', $nDate));
		
		// Make the day select element
		// 
		// Make 31 always day-options, let JavaScript fit it always to
		// right amount. If there is no JavaScript available, this 
		// ensures that there are always enough days to select.
		$sOut = '<select class="schoorbstpl-dateselector-day" name="'.$sPrefix.'day" id="'.$sPrefix.'day">';
		for ($i = 1; $i <= 31; $i++) {
			$sOut.= '<option'.($i == $nDay ? ' selected="selected"' : '').'>'.$i.'</option>';
		}
		$sOut.= '</select>';
		
		// Make the month select element
		$sOut.= '<select class="schoorbstpl-dateselector-month" name="'.$sPrefix.'month" id="'.$sPrefix.'month">';
		for ($i = 1; $i <= 12; $i++) {
			$sMonth = Lang::_(date('M', mktime(12, 0, 0, $i, 1, 2000)));
			$sOut.= '<option value="'.$i.'"'.($i == $nMonth ? ' selected="selected"' : '').'>'.$sMonth.'</option>';
		}
		$sOut.= '</select>';
		
		// Start from the lower of (Today, StartTime) - 5 years
		$nMinYear = min($nYear, date('Y')) - 5;
		// to the upper of (Today, EndTime) + 5 years)
		$nMaxYear = max($nYear, date('Y')) + 5;
	
		// Make the year select element
		$sOut.= '<select class="schoorbstpl-dateselector-year" name="'.$sPrefix.'year" id="'.$sPrefix.'year">';
		for($i = $nMinYear; $i <= $nMaxYear; $i++) {
			$sOut.= '<option value="'.$i.'"'.($i == $nYear ? ' selected="selected"' : '').'>'.$i.'</option>';
		}
		$sOut.= '</select>';
		
		return $sOut;
	}
}
