<?php
/**
 * General functions, should be separated in specific pieces
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 */

## Includes ##

require_once 'smarty.php';
require_once 'schoorbs-includes/input.functions.php';
require_once 'schoorbs-includes/time.functions.php';

## Var Inits ##

# probably a bad place to put this, but for error reporting purposes
# $pview must be defined. if it's not then there's errors generated all
# over the place. so we test to see if it is set, and if not then set
# it.
if (!isset($_REQUEST['pview'])) 
	$pview = 0;
else
    $pview = $_REQUEST['pview'];


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
 * @param int $day
 * @param int $month
 * @param int $year
 * @param int $area
 */
function print_header($day, $month, $year, $area)
{
	global $mrbs_company, $search_str, $locale_warning, $pview;
	global $smarty, $unicode_encoding;

	# If we dont know the right date then make it up 
	if($day < 1 || $day > 31)
		$day   = date("d");
	if($month < 1 || $month > 12)
		$month = date("m");
	if($year < 2000 || $year > 2050)
		$year  = date("Y");
	if (empty($search_str))
		$search_str = "";

	if ($unicode_encoding)
	{
		header("Content-Type: text/html; charset=utf-8");
	}
	else
	{
		# We use $vocab directly instead of get_vocab() because we have
		# no requirement to convert the vocab text, we just output
		# the charset
		header("Content-Type: text/html; charset=".$vocab["charset"]);
	}

	header("Pragma: no-cache");                          // HTTP 1.0
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
	
	$smarty->assign('mrbs_company',$mrbs_company);
	
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
    $smarty->assign('months',$months);
    $smarty->assign('years',$years);
	$smarty->assign('prefix',"");
	
	$smarty->assign('Area', $area);
	$smarty->assign('SearchStr',$search_str);
	$smarty->assign('Day',$day);
	$smarty->assign('Month',$month);
	$smarty->assign('Year', $year);
	$sPViewEcho = "";
	$sLogonBox = "";
	if($pview != 1)
	{
	    # show a warning if this is using a low version of php
        if (substr(phpversion(), 0, 1) == 3)
	        $sPViewEcho.= get_vocab("not_php3");

    	//TODO: Warunung anders ausgeben
        if (!empty($locale_warning))
            $sPViewEcho.= "[Warning: ".$locale_warning."]";

        if (!empty($area))
            $sPViewEcho.= "<input type=\"hidden\" name=\"area\" value=\"$area\" />";

        # For session protocols that define their own logon box...
        if (function_exists('PrintLogonBox'))
   	    {
   	        ob_start();
   	        PrintLogonBox();
   	        $sLogonBox.= ob_get_contents();
   	        ob_end_clean();
       	}        
    }
    $smarty->assign('logonbox',$sLogonBox);
    $smarty->assign('pview',$sPViewEcho);
	$smarty->display('head.tpl');
}

function toTimeString(&$dur, &$units)
{
	if($dur >= 60)
	{
		$dur /= 60;

		if($dur >= 60)
		{
			$dur /= 60;

			if(($dur >= 24) && ($dur % 24 == 0))
			{
				$dur /= 24;

				if(($dur >= 7) && ($dur % 7 == 0))
				{
					$dur /= 7;

					if(($dur >= 52) && ($dur % 52 == 0))
					{
						$dur  /= 52;
						$units = get_vocab("years");
					}
					else
						$units = get_vocab("weeks");
				}
				else
					$units = get_vocab("days");
			}
			else
				$units = get_vocab("hours");
		}
		else
			$units = get_vocab("minutes");
	}
	else
		$units = get_vocab("seconds");
}


function toPeriodString($start_period, &$dur, &$units)
{
	global $enable_periods;
        global $periods;

        $max_periods = count($periods);

	$dur /= 60;

        if( $dur >= $max_periods || $start_period == 0 )
        {
                if( $start_period == 0 && $dur == $max_periods )
                {
                        $units = get_vocab("days");
                        $dur = 1;
                        return;
                }

                $dur /= 60;
                if(($dur >= 24) && is_int($dur))
                {
                	$dur /= 24;
			$units = get_vocab("days");
                        return;
                }
                else
                {
			$dur *= 60;
                        $dur = ($dur % $max_periods) + floor( $dur/(24*60) ) * $max_periods;
                        $units = get_vocab("periods");
                        return;
		}
        }
        else
		$units = get_vocab("periods");
}

# Error handler - this is used to display serious errors such as database
# errors without sending incomplete HTML pages. This is only used for
# errors which "should never happen", not those caused by bad inputs.
# If $need_header!=0 output the top of the page too, else assume the
# caller did that. Alway outputs the bottom of the page and exits.
function fatal_error($need_header, $message)
{
	if ($need_header) print_header(0, 0, 0, 0);
	echo $message;
	include "trailer.php";
	exit(0);
}

# Apply backslash-escape quoting unless PHP is configured to do it
# automatically. Use this for GET/POST form parameters, since we
# cannot predict if the PHP configuration file has magic_quotes_gpc on.
function slashes($s)
{
	if (get_magic_quotes_gpc()) return $s;
	else return addslashes($s);
}

# Remove backslash-escape quoting if PHP is configured to do it with
# magic_quotes_gpc. Use this whenever you need the actual value of a GET/POST
# form parameter (which might have special characters) regardless of PHP's
# magic_quotes_gpc setting.
function unslashes($s)
{
	if (get_magic_quotes_gpc()) return stripslashes($s);
	else return $s;
}

# Return a default area; used if no area is already known. This returns the
# lowest area ID in the database (no guaranty there is an area 1).
# This could be changed to implement something like per-user defaults.
function get_default_area()
{
	global $tbl_area;
	$area = sql_query1("SELECT id FROM $tbl_area ORDER BY area_name LIMIT 1");
	return ($area < 0 ? 0 : $area);
}

# Return a default room given a valid area; used if no room is already known.
# This returns the first room in alphbetic order in the database.
# This could be changed to implement something like per-user defaults.
function get_default_room($area)
{
	global $tbl_room;
	$room = sql_query1("SELECT id FROM $tbl_room WHERE area_id=$area ORDER BY room_name LIMIT 1");
	return ($room < 0 ? 0 : $room);
}

# Get the local day name based on language. Note 2000-01-02 is a Sunday.
function day_name($daynumber)
{
	return utf8_strftime("%A", mktime(0,0,0,1,2+$daynumber,2000));
}

function hour_min_format()
{
        global $twentyfourhour_format;
        if ($twentyfourhour_format)
	{
  	        return "H:i";
	}
	else
	{
		return "h:ia";
	}
}

function period_date_string($t, $mod_time=0)
{
        global $periods;

	$time = getdate($t);
        $p_num = $time["minutes"] + $mod_time;
        if( $p_num < 0 ) $p_num = 0;
        if( $p_num >= count($periods) - 1 ) $p_num = count($periods ) - 1;
	# I have made the separater a ',' as a '-' leads to an ambiguious
	# display in report.php when showing end times.
        return array($p_num, $periods[$p_num] . utf8_strftime(", %A %d %B %Y",$t));
}

function period_time_string($t, $mod_time=0)
{
        global $periods;

	$time = getdate($t);
        $p_num = $time["minutes"] + $mod_time;
        if( $p_num < 0 ) $p_num = 0;
        if( $p_num >= count($periods) - 1 ) $p_num = count($periods ) - 1;
        return $periods[$p_num];
}

function time_date_string($t)
{
        global $twentyfourhour_format;
        # This bit's necessary, because it seems %p in strftime format
        # strings doesn't work
        $ampm = utf8_date("a",$t);
        if ($twentyfourhour_format)
	{
  	        return utf8_strftime("%H:%M:%S - %A %d %B %Y",$t);
	}
	else
	{
	        return utf8_strftime("%I:%M:%S$ampm - %A %d %B %Y",$t);
	}
}

# Display the entry-type color key. This has up to 2 rows, up to 5 columns.
function show_colour_key()
{
	global $typel;
	echo "<table border=\"0\"><tr>\n";
	$nct = 0;
	for ($ct = "A"; $ct <= "Z"; $ct++)
	{
		if (!empty($typel[$ct]))
		{
			if (++$nct > 5)
			{
				$nct = 0;
				echo "</tr><tr>";
			}
			echo "<td class=\"$ct\">";
			echo "$typel[$ct]</td>\n";
		}
	}
	echo "</tr></table>\n";
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
	if (($t-$am7) % $resolution != 0)
	{
		return $t + $resolution - abs(((int)$t-(int)
					       $am7) % $resolution);
	}
	else
	{
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

  <INPUT TYPE=HIDDEN NAME=day        VALUE=\"$day\">
  <INPUT TYPE=HIDDEN NAME=month      VALUE=\"$month\">
  <INPUT TYPE=HIDDEN NAME=year       VALUE=\"$year\">
  <input type=submit value=\"".get_vocab("change")."\">
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
  <INPUT TYPE=HIDDEN NAME=day        VALUE=\"$day\"        >
  <INPUT TYPE=HIDDEN NAME=month      VALUE=\"$month\"        >
  <INPUT TYPE=HIDDEN NAME=year       VALUE=\"$year\"      >
  <INPUT TYPE=HIDDEN NAME=area       VALUE=\"$area\"         >
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
?>
