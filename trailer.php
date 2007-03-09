<?php
/**
 * The footer of each site
 * 
 * @author jberanek, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

echo "<div class=\"footer\">";
if ( $pview != 1 ) {

echo "<hr /><strong>".get_vocab("viewday").":</strong>\n";

list($day, $month, $year) = input_DayMonthYear();

if (empty($area))
	$params = "";
else
	$params = "&amp;area=$area";

for($i = -6; $i <= 7; $i++)
{
	$ctime = mktime(0, 0, 0, $month, $day + $i, $year);

	$str = utf8_strftime(empty($dateformat)? "%b %d" : "%d %b", $ctime);

	$cyear  = date("Y", $ctime);
	$cmonth = date("m", $ctime);
	$cday   = date("d", $ctime);
	if ($i != -6) echo " | ";
	if ($i == 0) echo '<strong>[ ';
	echo "<a href=\"day.php?year=$cyear&amp;month=$cmonth&amp;day=$cday$params\">$str</a>\n";
	if ($i == 0) echo ']</strong> ';
}

echo "<br /><strong>".get_vocab("viewweek").":</strong>\n";

if (!empty($room)) $params .= "&amp;room=$room";

$ctime = mktime(0, 0, 0, $month, $day, $year);
# How many days to skip back to first day of week:
$skipback = (date("w", $ctime) - $weekstarts + 7) % 7;
	
for ($i = -4; $i <= 4; $i++)
{
	$ctime = mktime(0, 0, 0, $month, $day + 7 * $i - $skipback, $year);

	$cweek  = date("W", $ctime);
	$cday   = date("d", $ctime);
	$cmonth = date("m", $ctime);
	$cyear  = date("Y", $ctime);
	if ($i != -4) echo " | ";

	if ($view_week_number)
	{
		$str = $cweek;
	}
	else
	{
		$str = utf8_strftime(empty($dateformat)? "%b %d" : "%d %b", $ctime);
	}
	if ($i == 0) echo '<strong>[ ';
	echo "<a href=\"week.php?year=$cyear&amp;month=$cmonth&amp;day=$cday$params\">$str</a>\n";
	if ($i == 0) echo ']</strong> ';
}

echo "<br /><strong>".get_vocab("viewmonth").":</strong>\n";
for ($i = -2; $i <= 6; $i++)
{
	$ctime = mktime(0, 0, 0, $month + $i, 1, $year);
	$str = utf8_strftime("%b %Y", $ctime);
	
	$cmonth = date("m", $ctime);
	$cyear  = date("Y", $ctime);
	if ($i != -2) echo " | ";
	if ($i == 0) echo '<strong>[ ';
	echo "<a href=\"month.php?year=$cyear&amp;month=$cmonth$params\">$str</a>\n";
	if ($i == 0) echo ']</strong> ';
}

echo "<hr />";
echo '<div style="text-align: center"><a href="' . basename($_SERVER['PHP_SELF']) . '?' . htmlspecialchars($_SERVER['QUERY_STRING']) . '&amp;pview=1">' . get_vocab("ppreview") . '</a></div><br />';

}
?>

<hr />
</div>
</body>
</html>
