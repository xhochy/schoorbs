<?php
/**
 * General functions, should be separated in specific pieces
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/// Includes ///

/** The Schoorbs configuration */
require_once dirname(__FILE__).'/../config.inc.php';
/** Funtions for time handling */
require_once 'time.functions.php';

/// Functions ///

/**
 * Error handler - this is used to display serious errors such as database
 * errors without sending incomplete HTML pages. This is only used for
 * errors which "should never happen", not those caused by bad inputs.
 * If $need_header!=0 output the top of the page too, else assume the
 * caller did that. Alway outputs the bottom of the page and exits.
 *
 * @param boolean $need_header
 * @param string $message
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 */
function fatal_error($need_header, $message = '')
{
	if($need_header !== true && $need_header !== false)
		$message = $need_header;//sometimes fatal_error is called wrong
		// no time to fix this in general, so I made this short fix
		// REMOVE IT IN FUTURE !!!
	
	if(defined('SCHOORBS_NOGUI')) {
		throw new Exception($message);
	} else {
		// @codeCoverageIgnoreStart
		if ($need_header) print_header();
		echo $message;
		require_once 'trailer.php';
		exit(1);
		// @codeCoverageIgnoreEnd
	}
}

/**
 * Remove backslash-escape quoting if PHP is configured to do it with
 * magic_quotes_gpc. Use this whenever you need the actual value of a GET/POST
 * form parameter (which might have special characters) regardless of PHP's
 * magic_quotes_gpc setting.
 * 
 * @author jberanek
 * @param string $s
 * @return string 
 */
function unslashes($s)
{
	if (get_magic_quotes_gpc()) return stripslashes($s);
	else return $s;
}

/**
 * Get the local day name based on language. Note 2000-01-02 is a Sunday.
 * 
 * @param int $daynumber
 * @return string
 */
function day_name($daynumber)
{
	return utf8_strftime('%A', mktime(0,0,0,1,2+$daynumber,2000));
}

/**
 * If crossing dst determine if you need to make a modification
 * of 3600 seconds (1 hour) in either direction.
 *
 * @param int $start
 * @param int $end
 * @return int
 */
function cross_dst ( $start, $end )
{
	
	// entering DST
	if( !date( "I", $start) &&  date( "I", $end))
		$modification = -3600;

	// leaving DST
	elseif(  date( "I", $start) && !date( "I", $end))
		$modification = 3600;
	else
		$modification = 0;

	return $modification;
}

/**
 * Alias for htmlentities
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param string the text which should be escaped
 * @param int $nQuoteStyle
 * @param string $sCharset
 */
function ht($sCode, $nQuoteStyle = ENT_COMPAT, $sCharset = 'UTF-8')
{
	return htmlentities($sCode, $nQuoteStyle, $sCharset);
}
