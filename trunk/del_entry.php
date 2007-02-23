<?php
/**
 * Deletes an entry
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>, jberanek
 * @package Schoorbs
 */

## Includes ##

require_once "grab_globals.php";
require_once "config.inc.php";
require_once "functions.php";
require_once "db/$dbsys.php";
require_once "auth/mrbs_auth.php";
require_once "mrbs_sql.php";

## Main ##

if(getAuthorised(1) && ($info = mrbsGetEntryInfo($id)))
{
	$day   = strftime("%d", $info["start_time"]);
	$month = strftime("%m", $info["start_time"]);
	$year  = strftime("%Y", $info["start_time"]);
	$area  = mrbsGetRoomArea($info["room_id"]);

    if($info['start_time'] < time())
        fatal_error(true,'Start time in Past, could not delete entry!');

    if (MAIL_ADMIN_ON_DELETE)
    {
        require_once "functions_mail.php";
        // Gather all fields values for use in emails.
        $mail_previous = getPreviousEntryData($id, $series);
    }
    sql_begin();
	$result = mrbsDelEntry(getUserName(), $id, $series, 1);
	sql_commit();
	if ($result)
	{
        // Send a mail to the Administrator
        (MAIL_ADMIN_ON_DELETE) ? $result = notifyAdminOnDelete($mail_previous) : '';
        Header("Location: day.php?day=$day&month=$month&year=$year&area=$area");
		exit();
	}
}

// If you got this far then we got an access denied.
showAccessDenied($day, $month, $year, $area);
?>
