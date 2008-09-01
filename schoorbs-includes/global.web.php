<?php
/**
 * General things that need be done, when browsing Schoorbs on Web
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** Smarty Template Engine */
require_once 'smarty.functions.php';
/** The input getting & validating functions */
require_once 'input.functions.php';
 
## Var Inits ##

$pview = input_PView();

// ensure that $morningstarts_minutes defaults to zero if not set
if(empty($morningstarts_minutes)) {
	$morningstarts_minutes = 0;
}

$format = "Gi";
if ($enable_periods) {
	$format = "i";
	$resolution = 60;
	$morningstarts = 12;
	$morningstarts_minutes = 0;
	$eveningends = 12;
	$eveningends_minutes = count($periods)-1;
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
                header('Content-Type: text/html; charset=utf-8');
        } else {
                // We use $vocab directly instead of get_vocab() because we have
                // no requirement to convert the vocab text, we just output
                // the charset
                header('Content-Type: text/html; charset='.$vocab['charset']);
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
                'pviewecho' => $sPViewEcho,
                'pview' => $pview,
                'logonbox' => $sLogonBox
                ));
        $smarty->display('head.tpl');
}

