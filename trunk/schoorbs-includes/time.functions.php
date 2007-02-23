<?php
/**
 * Functions which deal with time
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Time
 */
 
/**
 * This will return the appropriate value for isdst for mktime().
 * The order of the arguments was chosen to match those of mktime.
 * hour is added so that this function can when necessary only be
 * run if the time is between midnight and 3am (all DST changes
 * occur in this period.
 * 
 * @author jberanek
 * @param int $month
 * @param int $day
 * @param int $year
 * @param int $hour
 * @return int
 */
function is_dst ( $month, $day, $year, $hour="-1" )
{

	if( $hour != -1  && $hour > 3)
		return -1;
	# entering DST
	if( !date( "I", mktime(12, 0, 0, $month, $day-1, $year)) && 
	    date( "I", mktime(12, 0, 0, $month, $day, $year)))
		return 0; 
	# leaving DST
	elseif( date( "I", mktime(12, 0, 0, $month, $day-1, $year)) && 
	    !date( "I", mktime(12, 0, 0, $month, $day, $year)))
		return 1;
	else
		return -1;
}
?>
