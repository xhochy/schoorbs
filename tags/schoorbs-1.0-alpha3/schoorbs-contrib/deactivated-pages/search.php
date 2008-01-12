<?php
/**
 * The Search UI 
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, gwalker
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

require_once "grab_globals.php";
require_once "config.inc.php";
require_once 'schoorbs-includes/global.web.php';
require_once 'schoorbs-includes/global.functions.php';
require_once "schoorbs-includes/database/$dbsys.php";

#If we dont know the right date then make it up 
list($day, $month, $year) = input_DayMonthYear();

$area = input_Area();

# Need all these different versions with different escaping.
# search_str must be left as the html-escaped version because this is
# used as the default value for the search box in the header.
if (!empty($search_str)) 
{
	$search_text = unslashes($search_str);
	$search_url = urlencode($search_text);
	$search_str = htmlspecialchars($search_text);
}

print_header($day, $month, $year, $area);

if (!empty($advanced))
{
	$smarty->assign('day',$day);
	$smarty->assign('month',$month);
	$smarty->assign('year',$year);
	$smarty->display('advanced_search.tpl');
	require_once 'schoorbs-includes/trailer.php';
	exit(0);
}

if (!$search_str)
{
	echo "<h3>" . get_vocab("invalid_search") . "</h3>";
	require_once 'schoorbs-includes/trailer.php';
	exit;
}

# now is used so that we only display entries newer than the current time
echo "<h3>" . get_vocab("search_results") . " \"<font color=\"blue\">$search_str</font>\"</h3>\n";

$now = mktime(0, 0, 0, $month, $day, $year);

# This is the main part of the query predicate, used in both queries:
$sql_pred = "( " . sql_syntax_caseless_contains("E.create_by", $search_text)
		. " OR " . sql_syntax_caseless_contains("E.name", $search_text)
		. " OR " . sql_syntax_caseless_contains("E.description", $search_text)
		. ") AND E.end_time > $now";

# The first time the search is called, we get the total
# number of matches.  This is passed along to subsequent
# searches so that we don't have to run it for each page.
if(!isset($total))
	$total = sql_query1("SELECT count(*) FROM $tbl_entry E WHERE $sql_pred");

if($total <= 0)
{
	echo "<strong>" . get_vocab("nothing_found") . "</strong>\n";
	require_once 'schoorbs-includes/trailer.php';
	exit;
}

if(!isset($search_pos) || ($search_pos <= 0))
	$search_pos = 0;
elseif($search_pos >= $total)
	$search_pos = $total - ($total % $search["count"]);

# Now we set up the "real" query using LIMIT to just get the stuff we want.
$sql = "SELECT E.id, E.create_by, E.name, E.description, E.start_time, R.area_id
        FROM $tbl_entry E, $tbl_room R
        WHERE $sql_pred
        AND E.room_id = R.id
        ORDER BY E.start_time asc "
    . sql_syntax_limit($search["count"], $search_pos);

# this is a flag to tell us not to display a "Next" link
$result = sql_query($sql);
if (! $result) fatal_error(0, sql_error());
$num_records = sql_count($result);

$has_prev = $search_pos > 0;
$has_next = $search_pos < ($total-$search["count"]);

if($has_prev || $has_next)
{
	echo "<b>" . get_vocab("records") . ($search_pos+1) . get_vocab("through") . ($search_pos+$num_records) . get_vocab("of") . $total . "</b><br />";

	# display a "Previous" button if necessary
	if($has_prev)
	{
		echo "<a href=\"search.php?search_str=$search_url&search_pos=";
		echo max(0, $search_pos-$search["count"]);
		echo "&total=$total&year=$year&month=$month&day=$day\">";
	}

	echo "<b>" . get_vocab("previous") . "</b>";

	if($has_prev)
		echo "</a>";

	# print a separator for Next and Previous
	echo(" | ");

	# display a "Previous" button if necessary
	if($has_next)
	{
		echo "<a href=\"search.php?search_str=$search_url&search_pos=";
		echo max(0, $search_pos+$search["count"]);
		echo "&total=$total&year=$year&month=$month&day=$day\">";
	}

	echo "<b>". get_vocab("next") ."</b>";

	if($has_next)
		echo "</a>";
}
?>
  <p>
  <table border=2 cellspacing=0 cellpadding=3>
   <tr>
    <th><?php echo get_vocab("entry") ?></th>
    <th><?php echo get_vocab("createdby") ?></th>
    <th><?php echo get_vocab("namebooker") ?></th>
    <th><?php echo get_vocab("description") ?></th>
    <th><?php echo get_vocab("start_date") ?></th>
   </tr>
<?php
for ($i = 0; ($row = sql_row($result, $i)); $i++)
{
	echo "<tr>";
	echo "<td><a href=\"view_entry.php?id=$row[0]\">".get_vocab("view")."</a></td>\n";
	echo "<td>" . htmlspecialchars($row[1]) . "</td>\n";
	echo "<td>" . htmlspecialchars($row[2]) . "</td>\n";
	echo "<td>" . htmlspecialchars($row[3]) . "</td>\n";
	// generate a link to the day.php
	$link = getdate($row[4]);
	echo "<td><a href=\"day.php?day=$link[mday]&month=$link[mon]&year=$link[year]&area=$row[5]\">";
	if(empty($enable_periods)){
        	$link_str = time_date_string($row[4]);
        }
        else {
        	list(,$link_str) = period_date_string($row[4]);
        }
        echo "$link_str</a></td>";
	echo "</tr>\n";
}

echo "</table>\n";

require_once 'schoorbs-includes/trailer.php';