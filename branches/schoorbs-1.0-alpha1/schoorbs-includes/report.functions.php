<?php
/**
 * This functions are only needed by report.php
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, thierry_bo
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

function date_time_string($t)
{
	global $twentyfourhour_format;
	
	if ($twentyfourhour_format)
	            $timeformat = "%H:%M:%S";
	else
	            $timeformat = "%I:%M:%S%p";
	return utf8_strftime("%A %d %B %Y ".$timeformat, $t);
}

function hours_minutes_seconds_format()
{
	global $twentyfourhour_format;

    if ($twentyfourhour_format)
	            $timeformat = "%H:%M:%S";
	else
	            $timeformat = "%I:%M:%S%p";
	return $timeformat;
}

//TODO: Remove this function
function genDateSelector($prefix, $day, $month, $year)
{
	Global $smarty;

	echo smarty_function_genDateSelector(array('prefix' => $prefix, 
		'day' => $day, 'month' => $month, 'year' => $year), $smarty);
}

# Convert a start time and end time to a plain language description.
# This is similar but different from the way it is done in view_entry.
function describe_span($starts, $ends)
{
	global $twentyfourhour_format;
	$start_date = utf8_strftime('%A %d %B %Y', $starts);
    $start_time = utf8_strftime(hours_minutes_seconds_format(), $starts);
	$duration = $ends - $starts;
	if ($start_time == "00:00:00" && $duration == 60*60*24)
		return $start_date . " - " . get_vocab("all_day");
	toTimeString($duration, $dur_units);
	return $start_date . " " . $start_time . " - " . $duration . " " . $dur_units;
}

# Convert a start period and end period to a plain language description.
# This is similar but different from the way it is done in view_entry.
function describe_period_span($starts, $ends)
{
	list( $start_period, $start_date) =  period_date_string($starts);
	list( , $end_date) =  period_date_string($ends, -1);
	$duration = $ends - $starts;
	toPeriodString($start_period, $duration, $dur_units);
	return $start_date . " - " . $duration . " " . $dur_units;
}

# this is based on describe_span but it displays the start and end
# date/time of an entry
function start_to_end($starts, $ends)
{
	global $twentyfourhour_format;
	
	$start_date = utf8_strftime('%A %d %B %Y', $starts);
    $start_time = utf8_strftime(hours_minutes_seconds_format(), $starts);
    
	$end_date = utf8_strftime('%A %d %B %Y', $ends);
    $end_time = utf8_strftime(hours_minutes_seconds_format(), $ends);
    
	return $start_date . " " . $start_time . " - " . $end_date . " " . $end_time;
}


# this is based on describe_period_span but it displays the start and end
# date/period of an entry
function start_to_end_period($starts, $ends)
{
	list( , $start_date) =  period_date_string($starts);
	list( , $end_date) =  period_date_string($ends, -1);
	return $start_date . " - " . $end_date;
}

# Report on one entry. See below for columns in $row[].
# $last_area_room remembers the current area/room.
# $last_date remembers the current date.
function reporton(&$row, &$last_area_room, &$last_date, $sortby, $display)
{
	global $typel;
        global $enable_periods;
	# Display Area/Room, but only when it changes:
	$area_room = htmlspecialchars($row[8]) . " - " . htmlspecialchars($row[9]);
	$date = utf8_strftime("%d-%b-%Y", $row[1]);
	# entries to be sorted on area/room
	if( $sortby == "r" )
	{
		if ($area_room != $last_area_room)
			echo "<hr><h2>". get_vocab("room") . ": " . $area_room . "</h2>\n";
		if ($date != $last_date || $area_room != $last_area_room)
		{
			echo "<hr noshade=\"true\"><h3>". get_vocab("date") . " " . $date . "</h3>\n";
			$last_date = $date;
		}
		# remember current area/room that is being processed.
		# this is done here as the if statement above needs the old
		# values
		if ($area_room != $last_area_room)
			$last_area_room = $area_room;
	}
	else
	# entries to be sorted on start date
	{
		if ($date != $last_date)
			echo "<hr><h2>". get_vocab("date") . " " . $date . "</h2>\n";
		if ($area_room != $last_area_room  || $date != $last_date)
		{
			echo "<hr noshade=\"true\"><h3>". get_vocab("room") . ": " . $area_room . "</h3>\n";
			$last_area_room = $area_room;
		}
		# remember current date that is being processed.
		# this is done here as the if statement above needs the old
		# values
		if ($date != $last_date)
			$last_date = $date;
	}

	echo "<hr><table width=\"100%\">\n";

	# Brief Description (title), linked to view_entry:
	echo "<tr><td class=\"BL\"><a href=\"view_entry.php?id=$row[0]\">"
		. htmlspecialchars($row[3]) . "</a></td>\n";

	# what do you want to display duration or end date/time
	if( $display == "d" )
		# Start date/time and duration:
		echo "<td class=\"BR\" align=right>" .
			(empty($enable_periods) ?
				describe_span($row[1], $row[2]) :
				describe_period_span($row[1], $row[2])) .
			"</td></tr>\n";
	else
		# Start date/time and End date/time:
		echo "<td class=\"BR\" align=right>" .
			(empty($enable_periods) ?
				start_to_end($row[1], $row[2]) :
				start_to_end_period($row[1], $row[2])) .
			"</td></tr>\n";

	# Description:
	echo "<tr><td class=\"BL\" colspan=2><b>".get_vocab("description")."</b> " .
		nl2br(htmlspecialchars($row[4])) . "</td></tr>\n";

	# Entry Type:
	$et = empty($typel[$row[5]]) ? "?$row[5]?" : $typel[$row[5]];
	echo "<tr><td class=\"BL\" colspan=2><b>".get_vocab("type")."</b> $et</td></tr>\n";
	# Created by and last update timestamp:
	echo "<tr><td class=\"BL\" colspan=2><small><b>".get_vocab("createdby")."</b> " .
		htmlspecialchars($row[6]) . ", <b>".get_vocab("lastupdate")."</b> " .
		date_time_string($row[7]) . "</small></td></tr>\n";

	echo "</table>\n";
}

# Collect summary statistics on one entry. See below for columns in $row[].
# $sumby selects grouping on brief description (d) or created by (c).
# This also builds hash tables of all unique names and rooms. When sorted,
# these will become the column and row headers of the summary table.
function accumulate(&$row, &$count, &$hours, $report_start, $report_end,
	&$room_hash, &$name_hash)
{
	global $sumby;
	# Use brief description or created by as the name:
	$name = htmlspecialchars($row[($sumby == "d" ? 3 : 6)]);
    # Area and room separated by break:
	$room = htmlspecialchars($row[8]) . "<br>" . htmlspecialchars($row[9]);
	# Accumulate the number of bookings for this room and name:
	@$count[$room][$name]++;
	# Accumulate hours used, clipped to report range dates:
	@$hours[$room][$name] += (min((int)$row[2], $report_end)
		- max((int)$row[1], $report_start)) / 3600.0;
	$room_hash[$room] = 1;
	$name_hash[$name] = 1;
}

function accumulate_periods(&$row, &$count, &$hours, $report_start, $report_end,
	&$room_hash, &$name_hash)
{
	global $sumby;
        global $periods;
        $max_periods = count($periods);

	# Use brief description or created by as the name:
	$name = htmlspecialchars($row[($sumby == "d" ? 3 : 6)]);
    # Area and room separated by break:
	$room = htmlspecialchars($row[8]) . "<br>" . htmlspecialchars($row[9]);
	# Accumulate the number of bookings for this room and name:
	@$count[$room][$name]++;
	# Accumulate hours used, clipped to report range dates:
        $dur = (min((int)$row[2], $report_end) - max((int)$row[1], $report_start))/60;
	@$hours[$room][$name] += ($dur % $max_periods) + floor( $dur/(24*60) ) * $max_periods;
        $room_hash[$room] = 1;
	$name_hash[$name] = 1;
}

# Output a table cell containing a count (integer) and hours (float):
function cell($count, $hours)
{
	echo "<td class=\"BR\" align=right>($count) "
	. sprintf("%.2f", $hours) . "</td>\n";
}

# Output the summary table (a "cross-tab report"). $count and $hours are
# 2-dimensional sparse arrays indexed by [area/room][name].
# $room_hash & $name_hash are arrays with indexes naming unique rooms and names.
function do_summary(&$count, &$hours, &$room_hash, &$name_hash)
{
	global $enable_periods;
        
        # Make a sorted array of area/rooms, and of names, to use for column
	# and row indexes. Use the rooms and names hashes built by accumulate().
	# At PHP4 we could use array_keys().
	reset($room_hash);
	while (list($room_key) = each($room_hash)) $rooms[] = $room_key;
	ksort($rooms);
	reset($name_hash);
	while (list($name_key) = each($name_hash)) $names[] = $name_key;
	ksort($names);
	$n_rooms = sizeof($rooms);
	$n_names = sizeof($names);

	echo "<hr><h1>".
             (empty($enable_periods) ? get_vocab("summary_header") : get_vocab("summary_header_per")).
             "</h1><table border=2 cellspacing=4>\n";
	echo "<tr><td>&nbsp;</td>\n";
	for ($c = 0; $c < $n_rooms; $c++)
	{
		echo "<td class=\"BL\" align=left><b>$rooms[$c]</b></td>\n";
		$col_count_total[$c] = 0;
		$col_hours_total[$c] = 0.0;
	}
	echo "<td class=\"BR\" align=right><br><b>".get_vocab("total")."</b></td></tr>\n";
	$grand_count_total = 0;
	$grand_hours_total = 0;

	for ($r = 0; $r < $n_names; $r++)
	{
		$row_count_total = 0;
		$row_hours_total = 0.0;
		$name = $names[$r];
		echo "<tr><td class=\"BR\" align=right><b>$name</b></td>\n";
		for ($c = 0; $c < $n_rooms; $c++)
		{
			$room = $rooms[$c];
			if (isset($count[$room][$name]))
			{
				$count_val = $count[$room][$name];
				$hours_val = $hours[$room][$name];
				cell($count_val, $hours_val);
				$row_count_total += $count_val;
				$row_hours_total += $hours_val;
				$col_count_total[$c] += $count_val;
				$col_hours_total[$c] += $hours_val;
			} else {
				echo "<td>&nbsp;</td>\n";
			}
		}
		cell($row_count_total, $row_hours_total);
		echo "</tr>\n";
		$grand_count_total += $row_count_total;
		$grand_hours_total += $row_hours_total;
	}
	echo "<tr><td class=\"BR\" align=right><b>".get_vocab("total")."</b></td>\n";
	for ($c = 0; $c < $n_rooms; $c++)
		cell($col_count_total[$c], $col_hours_total[$c]);
	cell($grand_count_total, $grand_hours_total);
	echo "</tr></table>\n";
}
?>
