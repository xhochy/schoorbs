<?php
/**
 * Deletes an area or a room
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/** The Configuration file */
require_once 'config.inc.php';
/** The general 'things' when viewing Schoorbs on the web */
require_once 'schoorbs-includes/global.web.php';
/** The general functions */ 
require_once 'schoorbs-includes/global.functions.php';
/** The database wrapper */
require_once "schoorbs-includes/database/$dbsys.php";
/** The authetication wrappers */
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';

## Var Init ##

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();
if (isset($_REQUEST['confirm'])) $confirm = true;
$type = input_Type();

## Main ##

// This is gonna blast away something. We want them to be really
// really sure that this is what they want to do.

if($type == "room")
{
	// We are supposed to delete a room
	if (isset($confirm)) {
		// They have confirmed it already, so go blast!
		sql_begin();
		// First take out all appointments for this room
		sql_command("DELETE FROM $tbl_entry WHERE room_id=$room");
		
		// Now take out the room itself
		sql_command("DELETE FROM $tbl_room WHERE id=$room");
		sql_commit();
		
		// Go back to the admin page
		header("Location: admin.php");
	} else {
		print_header();
		
		// We tell them how bad what theyre about to do is
		// Find out how many appointments would be deleted
		
		$sQuery = sprintf(
			'SELECT name, start_time, end_time FROM %s where room_id = %d', 
			$tbl_entry, $room
		);
		$res = sql_query($sQuery);
		if (!$res) echo sql_error();
		elseif (sql_count($res) > 0) {
			echo get_vocab("deletefollowing") . ':<ul>';
			
			for ($i = 0; ($row = sql_row($res, $i)); $i++) {
				printf('<li>%s (%s -> %s)</li>', $row[0], 
					time_date_string($row[1]), time_date_string($row[2]));
			}
			
			echo '</ul>';
		}
		
		echo '<div style="text-align: center">';
		echo '<h1>'.get_vocab('sure').'</h1>';
		echo "<h1><a href=\"del.php?type=room&amp;room=$room&amp;confirm=Y\">" . get_vocab('YES') . '!</a> &nbsp;&nbsp;&nbsp; <a href="admin.php">' . get_vocab('NO') . '!</a></h1>';
		echo '</div>';
		require_once 'schoorbs-includes/trailer.php';
	}
}

if ($type == "area") {
	// We are only going to let them delete an area if there are
	// no rooms. its easier
    $n = sql_query1("SELECT COUNT(*) FROM $tbl_room WHERE area_id = $area");
	if ($n == 0) {
		// OK, nothing there, lets blast it away
		sql_command("DELETE FROM $tbl_area WHERE id = $area");
		// Redirect back to the admin page
		header('Location: admin.php');
	} else {
		// There are rooms left in the area
		print_header();
		
		echo get_vocab('delarea');
		echo '<a href="admin.php">'.get_vocab('backadmin').'</a>';
		require_once 'schoorbs-includes/trailer.php';
	}
}
