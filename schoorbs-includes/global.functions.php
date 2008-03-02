<?php
/**
 * General functions, should be separated in specific pieces
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The Schoorbs configuration */
require_once dirname(__FILE__).'/../config.inc.php';
/** Smarty Template Engine */
require_once 'smarty.functions.php';
/** Funtions for time handling */
require_once 'time.functions.php';
/** Functions for area handling */
require_once 'area.functions.php';
/** The authetication wrappers */
require_once dirname(__FILE__).'/../schoorbs-includes/authentication/schoorbs_auth.php';

## Functions ##

# 3-value compare: Returns result of compare as "< " "= " or "> ".
function cmp3($a, $b)
{
    if ($a < $b) return "< ";
    if ($a == $b) return "= ";
    return "> ";
}


/**
 * Prints the Header of the XHTML page
 *
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 */
function print_header()
{
	global $mrbs_company, $search_str, $locale_warning, $pview;
	global $smarty, $unicode_encoding, $vocab, $unicode_encoding;

    list($day, $month, $year) = input_DayMonthYear();
    $area = input_Area();
	if (empty($search_str)) $search_str = '';

	if ($unicode_encoding) {
		if (eregi("msie", $_SERVER['HTTP_USER_AGENT']) && !eregi("opera", $_SERVER['HTTP_USER_AGENT'])) {
			// MS Internet Explorer
			header('Content-Type: text/html; charset=utf-8');
		} else {
			header('Content-Type: application/xhtml+xml; charset=utf-8');
		}
	} else {
		# We use $vocab directly instead of get_vocab() because we have
		# no requirement to convert the vocab text, we just output
		# the charset
		if (eregi("msie", $_SERVER['HTTP_USER_AGENT']) && !eregi("opera", $_SERVER['HTTP_USER_AGENT'])) {
			// MS Internet Explorer
			header('Content-Type: text/html; charset='.$vocab['charset']);
		} else {
			header('Content-Type: application/xhtml+xml; charset='.$vocab['charset']);
		}
	}

	header('Pragma: no-cache');                          // HTTP 1.0
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');    // Date in the past
	
	/** day, month, year selector **/
	// months
	$months = array();
	for($i = 1; $i <= 12; $i++)
		$months[] = array('string' => utf8_strftime("%b", mktime(0, 0, 0, $i, 1, $year)),
		    'id' => $i);
	// years
	$min = min($year, date("Y")) - 5;
	$max = max($year, date("Y")) + 5;
	$years = array();
	for($i = $min; $i <= $max; $i++)
		$years[] = $i;
	
	$sPViewEcho = ''; $sLogonBox = '';
	if($pview != 1) {
        if (!empty($locale_warning)) {
            $sPViewEcho.= '[Warning: '.$locale_warning.']';
        }

        $sPViewEcho.= '<input type="hidden" name="area" value="'.$area.'" />';

        // For session protocols that define their own logon box...
        if (function_exists('PrintLogonBox')) {
   	        ob_start();
   	        PrintLogonBox();
   	        $sLogonBox = ob_get_contents();
   	        ob_end_clean();
       	}        
    }
    
    $smarty->assign(array(
		'months' => $months, 'years' => $years,
		'prefix' => '',
		'Area' => $area,
		'SearchStr' => $search_str,
		'Day' => $day, 'Month' => $month, 'Year' => $year,
		'mrbs_company' => $mrbs_company,
		'pview' => $sPViewEcho,
		'logonbox' => $sLogonBox
		));
	$smarty->display('head.tpl');
}


# Error handler - this is used to display serious errors such as database
# errors without sending incomplete HTML pages. This is only used for
# errors which "should never happen", not those caused by bad inputs.
# If $need_header!=0 output the top of the page too, else assume the
# caller did that. Alway outputs the bottom of the page and exits.
function fatal_error($need_header, $message = '')
{
	if($need_header !== true && $need_header !== false)
		$message = $need_header;//sometimes fatal_error is called wrong
		// no time to fix this in general, so I made this short fix
		// REMOVE IT IN FUTURE !!!
	
	if(defined('SCHOORBS_NOGUI')) {
		if(version_compare('5.0.0',PHP_VERSION,'>') === true) {
			trigger_error('Schoorbs Fatal Error: '.$message, E_USER_ERROR);
		} else {
			throw new Exception($message);
		}
	} else {
		if ($need_header) print_header();
		echo $message;
		require_once 'trailer.php';
		exit(1);
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

# Get the local day name based on language. Note 2000-01-02 is a Sunday.
function day_name($daynumber)
{
	return utf8_strftime('%A', mktime(0,0,0,1,2+$daynumber,2000));
}

function hour_min_format()
{
    global $twentyfourhour_format;
    
    if ($twentyfourhour_format) {
		return '%H:%M';
	} else {
		return '%I:%M%p';
	}
}

function period_date_string($t, $mod_time=0)
{
	global $periods;

	$time = getdate($t);
    $p_num = $time['minutes'] + $mod_time;
    if( $p_num < 0 ) $p_num = 0;
    if( $p_num >= count($periods) - 1 ) $p_num = count($periods ) - 1;
	# I have made the separater a ',' as a '-' leads to an ambiguious
	# display in report.php when showing end times.
    return array($p_num, $periods[$p_num] . utf8_strftime(', %A %d %B %Y', $t));
}

function period_time_string($t, $mod_time=0)
{
	global $periods;

	$time = getdate($t);
    $p_num = $time['minutes'] + $mod_time;
    if( $p_num < 0 ) $p_num = 0;
    if( $p_num >= count($periods) - 1 ) $p_num = count($periods ) - 1;
    return $periods[$p_num];
}

function time_date_string($t)
{
    global $twentyfourhour_format;

    if ($twentyfourhour_format)
  	        return utf8_strftime("%H:%M:%S - %A %d %B %Y",$t);
	else
	        return utf8_strftime("%I:%M:%S%p - %A %d %B %Y",$t);
}


# Display the entry-type color key. This has up to 2 rows, up to 5 columns.
function show_colour_key()
{
	global $typel;
	//echo "<table border=\"0\"><tr>\n";
	echo '<div id="colour-keys">';
	$nct = 0;
	for ($ct = "A"; $ct <= "Z"; $ct++) {
		if (!empty($typel[$ct])) {
			if (++$nct > 5) {
				$nct = 0;
				echo '<br />';
			}
			printf('<span class="%s">%s</span>', $ct, $typel[$ct]);
		}
	}
	//echo "</tr></table>\n";
	echo "</div>\n";
}

# Round time down to the nearest resolution
function round_t_down($t, $resolution, $am7)
{
        return (int)$t - (int)abs(((int)$t-(int)$am7)
				  % $resolution);
}

# Round time up to the nearest resolution
function round_t_up($t, $resolution, $am7)
{
	if (($t-$am7) % $resolution != 0) {
		return $t + $resolution - abs(((int)$t-(int)
					       $am7) % $resolution);
	} else {
		return $t;
	}
}

# generates some html that can be used to select which area should be
# displayed.
function make_area_select_html( $link, $current, $year, $month, $day )
{
	global $tbl_area;
	$out_html = "
<form name=\"areaChangeForm\" method=get action=\"$link\">
  <select name=\"area\" onChange=\"document.areaChangeForm.submit()\">";

	$sql = "select id, area_name from $tbl_area order by area_name";
   	$res = sql_query($sql);
   	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++)
   	{
		$selected = ($row[0] == $current) ? "selected" : "";
		$out_html .= "
    <option $selected value=\"".$row[0]."\">" . htmlspecialchars($row[1]);
   	}
	$out_html .= "
  </select>

  <input type=\"hidden\" name=\"day\"        value=\"$day\">
  <input type=\"hidden\" name=\"month\"      value=\"$month\">
  <input type=\"hidden\" name=\"year\"       value=\"$year\">
  <input type=\"submit\" value=\"".get_vocab("change")."\">
</form>\n";

	return $out_html;
} # end make_area_select_html

function make_room_select_html( $link, $area, $current, $year, $month, $day )
{
	global $tbl_room;
	$out_html = "
<form name=\"roomChangeForm\" method=get action=\"$link\">
  <select name=\"room\" onChange=\"document.roomChangeForm.submit()\">";

	$sql = "select id, room_name from $tbl_room where area_id=$area order by room_name";
   	$res = sql_query($sql);
   	if ($res) for ($i = 0; ($row = sql_row($res, $i)); $i++)
   	{
		$selected = ($row[0] == $current) ? "selected" : "";
		$out_html .= "
    <option $selected value=\"".$row[0]."\">" . htmlspecialchars($row[1]);
   	}
	$out_html .= "
  </select>
  <input type=\"hidden\" name=\"day\"        value=\"$day\"        >
  <input type=\"hidden\" name=\"month\"      value=\"$month\"        >
  <input type=\"hidden\" name=\"year\"       value=\"$year\"      >
  <input type=\"hidden\" name=\"area\"       value=\"$area\"         >
  <input type=submit value=\"".get_vocab("change")."\">
</form>\n";

	return $out_html;
} # end make_area_select_html

# if crossing dst determine if you need to make a modification
# of 3600 seconds (1 hour) in either direction
function cross_dst ( $start, $end )
{
	
	# entering DST
	if( !date( "I", $start) &&  date( "I", $end))
		$modification = -3600;

	# leaving DST
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
function ht($sCode, $nQuoteStyle = ENT_COMPAT, $sCharset = 'ISO-8859-1')
{
	return htmlentities($sCode, $nQuoteStyle, $sCharset);
}

/**
 * Output a string and append a linebreak
 *
 * Should make the code clearer through avoiding unessacary double-quotes
 * or ."\n"
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @param string $sText
 */
function puts($sText)
{
	echo $sText."\n";
}
