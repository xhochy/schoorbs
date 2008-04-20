<?php
/**
 * The view of one day.
 * 
 * @author gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/// Includes ///

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The 3 minicalendars */
require_once 'schoorbs-includes/minicals.php';
/** The modern ORM databse layer */
require_once 'schoorbs-includes/database/schoorbsdb.class.php';

/// Var Init ///

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();
/** area */
$area = input_Area();
// determinate, if today we have day saving time
$dst_change = is_dst($month,$day,$year);
// The time of the first possibile entry
$am7 = am7($day, $month, $year);
// The end-time of the last possibile entry
$pm7 = pm7($day, $month, $year);

// y? are year, month and day of yesterday
list($yd, $ym, $yy) = getYesterday($day, $month, $year);
// t? are year, month and day of tomorrow
list($td, $tm, $ty) = getTomorrow($day, $month, $year);

/// Main ///

// print the page header
print_header();

if ($pview != 1) {
    // We need to show either a select box or a normal html list,
    // depending on the settings in config.inc.php
    if ($area_list_format == 'select') {
    	$smarty->assign('area_select_list', 
    		make_area_select_html('day.php', $area, $year, $month, $day));
    } else {
    	// show the standard html list
    	$smarty->assign('areas', getAreas()); 
    }
   
    $smarty->assign(array(
    	'area' => $area, 'dwm' => 'day.php',
    	'day' => $day, 'year' => $year, 'month' => $month,
    	'area_list_format' => $area_list_format
    ));
    $smarty->display('area_list.tpl');
    
    // Draw the three month calendars
    minicals($year, $month, $day, $area, '', 'day');
    puts('</tr></table>');
}

// Initialize some common Smarty variable values
$smarty->assign(array(
	'am7' => utf8_strftime("%A %d %B %Y", $am7),
	'pview' => $pview, 'area' => $area,
	'yy' => $yy, 'ym' => $ym, 'yd' => $yd,
	'ty' => $ty, 'tm' => $tm, 'td' => $td,
	'year' => $year, 'day' => $day, 'month' => $month,
	'times_right_side' => ($times_right_side ? 'true' : 'false'),
	'enable_periods' => ($enable_periods ? 'true' : 'false')
));

// Get the area as an ORM instance
$oArea = Area::getById($area);
// Get the name of the area we are working on out of the database
$area_name = $oArea->getName();
// Collect all rooms in the choosen area
$aRooms = Room::getRooms($oArea);
// If there are no rooms in this area, there won't be any entries to display.
// In this case display a message, which should be seens as a remark and not
// as an error. There could exist areas without rooms as placeholders for
// future times.
if (count($aRooms) === 0) {
	echo '<h1>'.get_vocab('no_rooms_for_area').'</h1>';
} else {
	// Get all appointments for today in the area that we care about
	// 
	// The entries will be stored in a specific array, so that we could use it
	// with Smarty more easily. Some things might occur stupid, but they help us
	// with things in the templates. The array has the following structure:
	//
	// $aEntries 
	//   --> [Timestamp of row]
	//     --> ['entries']
	//       --> [Room-id]
	//         --> ['entry'] => Entry-Object 
	//         --> ['room'] => Room-Obeject
	//     --> ['timestring'] => Time formatted as a nice string
	//     --> ['time'] => Unix timestamp of the starttime of this row
	//     --> ['urlparams'] => The URL-parameters which should be added to 
	//                          the links to identify the time of the row.
	$aEntries = array();
	// Fill up the array with empty time rows
	for ($t = $am7; $t < $pm7; $t += $resolution) {
		$aEntries[$t] = array('entries' => array());
		// Save the time of this line, need for comparisons, if an entry
		// starts in this row or a proceeding one
		$aEntries[$t]['time'] = $t;
		
		// Depending on which time system we have, we generate the formatted
		// time string
		if($enable_periods) {
			// Get the number of the period => minute of the hour
			$time_t = preg_replace("/^0/", "", date('i', $t));
			// Get the name/title of the period out of the configuration array
			// $period
			$aEntries[$t]['timestring'] = $periods[$time_t];
			// Make up the URL-parameters for this period
			$aEntries[$t]['urlparams'] = htmlentities('&period='.$time_t);
		} else {
			// Use utf8_strftime to support multilingual date output
			$aEntries[$t]['timestring'] = utf8_strftime(hour_min_format(), $t);
			// Make up the URL-parameters for this time
			$aEntries[$t]['urlparams'] = htmlentities('&hour='.$hour
				.'&minute='.$minute);
		}		
	}
	foreach ($aRooms as $oRoom) {
		for ($t = $am7; $t < $pm7; $t += $resolution) {
			// Fill $aEntries subarray with default values. Always include the
			// room-object, so that we can make up the links. If entry is null
			// the room is free at this time, otherwise it is filled with the 
			// fitting booking. One could determinate if this is the first time
			// an entry occurs by comparing <entry>->getStartTime() and
			// $aEntries[$t]['time'].
			$aEntries[$t]['entries'][$oRoom->getId()] = array(
				'entry' => null, 'room' => $oRoom);
		}
		// Go through each entry and save it in teh $aEntries array
		foreach (Entry::getBetween($oRoom, $pm7, $am7) as $oEntry) {
			// Fill in the map for this meeting. Start at the meeting start time,
			// end one slot before the meeting end time (since the next slot is 
			// for meetings which start then), or at the last slot in the day, 
			// whichever is earlier.
			//
			// Start either at morning or the start of the booking, we choose 
			// the later one.
			$start_t = max($oEntry->getStartTime(), $am7);
			// End $resolution before the endtime of the booking or, if earlier,
			// at the end of the day.
			$end_t = min(($oEntry->getEndTime() - $resolution), $pm7);
			for ($t = $start_t; $t <= $end_t; $t += $resolution) {
				$aEntries[$t]['entries'][$oRoom->getId()]['entry'] = $oEntry;
			}
		}
	}
	
	// Sort the array, sorting should be done via looking in the keys.
	ksort($aEntries);
	// Remove the keys, so we could use simply foreach in Smarty
	$aEntries = array_values($aEntries);
	
	// Assign all remaining variable values for Smarty
	$smarty->assign(array(
		'rooms' => $aRooms,
		'entries' => $aEntries
	));
	
	// Display the template for the timetable-day-view
	$smarty->display('day.tpl');
	// Show the colur keys with the types of bookinhgs
	show_colour_key();
}

/** The footer of the HTML Page */
require_once 'schoorbs-includes/trailer.php';
