<?php
/**
 * Deletes an area or a room
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

require_once "grab_globals.php";
require_once "config.inc.php";
require_once 'schoorbs-includes/global.web.php';
require_once 'schoorbs-includes/global.functions.php';
require_once "schoorbs-includes/database/$dbsys.php";
require_once 'schoorbs-includes/authentication/schoorbs_auth.php';

#If we dont know the right date then make it up
list($day, $month, $year) = input_DayMonthYear();

# This is gonna blast away something. We want them to be really
# really sure that this is what they want to do.

if($type == "room")
{
	# We are supposed to delete a room
	if(isset($confirm))
	{
		# They have confirmed it already, so go blast!
		sql_begin();
		# First take out all appointments for this room
		sql_command("delete from $tbl_entry where room_id=$room");
		
		# Now take out the room itself
		sql_command("delete from $tbl_room where id=$room");
		sql_commit();
		
		# Go back to the admin page
		Header("Location: admin.php");
	}
	else
	{
		print_header();
		
		# We tell them how bad what theyre about to do is
		# Find out how many appointments would be deleted
		
		$sql = "select name, start_time, end_time from $tbl_entry where room_id=$room";
		$res = sql_query($sql);
		if (! $res) echo sql_error();
		elseif (sql_count($res) > 0)
		{
			echo get_vocab("deletefollowing") . ":<ul>";
			
			for ($i = 0; ($row = sql_row($res, $i)); $i++)
			{
				echo "<li>$row[0] (";
				echo time_date_string($row[1]) . " -> ";
				echo time_date_string($row[2]) . ")";
			}
			
			echo "</ul>";
		}
		
		echo "<center>";
		echo "<h1>" .  get_vocab("sure") . "</h1>";
		echo "<h1><a href=\"del.php?type=room&room=$room&confirm=Y\">" . get_vocab("YES") . "!</a> &nbsp;&nbsp;&nbsp; <a href=admin.php>" . get_vocab("NO") . "!</a></h1>";
		echo "</center>";
		require_once 'schoorbs-includes/trailer.php';
	}
}

if($type == "area")
{
	# We are only going to let them delete an area if there are
	# no rooms. its easier
    $n = sql_query1("select count(*) from $tbl_room where area_id=$area");
	if ($n == 0)
	{
		# OK, nothing there, lets blast it away
		sql_command("delete from $tbl_area where id=$area");
		
		# Redirect back to the admin page
		header("Location: admin.php");
	}
	else
	{
		# There are rooms left in the area
		print_header();
		
		echo get_vocab("delarea");
		echo "<a href=admin.php>" . get_vocab("backadmin") . "</a>";
		require_once 'schoorbs-includes/trailer.php';
	}
}
