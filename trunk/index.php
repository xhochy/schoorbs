<?php
/**
 * Index is just a stub to redirect to the appropriate view
 * as defined in config.inc.php using the variable $default_view
 * If $default_room is defined in config.inc.php then this will
 * be used to redirect to a particular room.
 * 
 * @author gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

## Includes ##

/** The Schoorbs configuration file */
require_once "config.inc.php";
/** The global include for webscripts */
require_once 'schoorbs-includes/global.web.php';
/** The database functions */
require_once "schoorbs-includes/database/$dbsys.php";

## Vars ##

/** day, month, year */
list($day, $month, $year) = input_DayMonthYear();

## Main ##

switch ($default_view) {
case "month":
    $redirect_str = "month.php?year=$year&month=$month";
    break;
case "week":
    $redirect_str = "week-view.php?year=$year&month=$month&day=$day";
    break;
default:
    $redirect_str = "day.php?day=$day&month=$month&year=$year";
}

if(!empty($default_room)) {
    $sQuery = 'SELECT area_id FROM $tbl_room WHERE id = '
        .sql_escape_arg($default_room);
    $res = sql_query($sql);
    if ($res) {
        if (sql_count($res) == 1) {
            $row = sql_row($res, 0);
            $area = $row[0];
            $room = $default_room;
            $redirect_str .= "&area=$area&room=$room";
        }
    }
}

header("Location: $redirect_str");
