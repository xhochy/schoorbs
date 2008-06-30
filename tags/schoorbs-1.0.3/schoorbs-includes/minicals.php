<?php
/**
 * Outputs the three calenders at the top of the page
 * 
 * @author David Wilkinson <davidw@cascade.org.uk>, gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @package PHP-Calender
 * @license - opensource
 */

/**
 * PHP Calendar Class
 * 
 * Copyright David Wilkinson 2000. All Rights reserved.
 * 
 * 
 * This software may be used, modified and distributed freely
 * providing this copyright notice remains intact at the head 
 * of the file.
 *
 * This software is freeware. The author accepts no liability for
 * any loss or damages whatsoever incurred directly or indirectly 
 * from the use of this script.
 *
 * URL:   http://www.cascade.org.uk/software/php/calendar/
 * Email: davidw@cascade.org.uk
 *
 * Modified for Schoorbs!
 *
 * Copyright Uwe L. Korn 2007-2008. All Rights reserved.
 * 
 * The modification is under the same license as Schoorbs.
 *
 * @package PHP-Calender
 * @author David Wilkinson <davidw@cascade.org.uk>, gwalker, Uwe L. Korn <uwelk@xhochy.org>
 */
class Calendar
{
    var $month;
    var $year;
    var $day;
    var $h;
    var $area;
    var $room;
    var $dmy;
    
    function Calendar($day, $month, $year, $h, $area, $room, $dmy)
    {
        $this->day   = $day;
        $this->month = $month;
        $this->year  = $year;
        $this->h     = $h;
        $this->area  = $area;
        $this->room  = $room;
        $this->dmy   = $dmy;
    }
   
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12) {
            return 0;
        }
    
        $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
   
        $d = $days[$month - 1];

		// Check for leap year
        // Forget the 4000 rule, I doubt I'll be around then...   
        // If it's a leap year set the returned value to 29, else leave it on 
        // 28.
        if ($month == 2) {
            if ($year%4 == 0) {
                if ($year%100 == 0) {
                    if ($year%400 == 0) {
                        $d = 29;
                    }
                } else {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }

    function getHTML()
    {
        global $weekstarts, $smarty, $day, $month;

        if (!isset($weekstarts)) $weekstarts = 0;
        
        $daysInMonth = $this->getDaysInMonth($this->month, $this->year);
		// $prevYear is the current year unless the previous month is
		// December then you need to decrement the year
		if (($this->month - 1) > 0) {
			$prevMonth = $this->month - 1;
			$prevYear = $this->year;
		} else {
			$prevMonth = 12;
			$prevYear = $this->year -1;
		}
        $daysInPrevMonth = $this->getDaysInMonth($prevMonth, $prevYear);
        // Get the time of this month, 1st, 12:00:00
        $date = mktime(12, 0, 0, $this->month, 1, $this->year);
        
        $first = (strftime("%w",$date) + 7 - $weekstarts) % 7;
        // Get the name of the current month
        $monthName = utf8_strftime("%B",$date);
        
        $d = 1 - $first;
            
        # this is used to highlight days in upcoming month
        $days_to_highlight = ($d + 7);

		$loop1 = array();
        while ($d <= $daysInMonth) {
            $loop2 = array();
            for ($i = 0; $i < 7; $i++) {
            	$loop_array = array();		
                if ($d > 0 && $d <= $daysInMonth) {
                	$loop_array['empty'] = 'false';
                	$loop_array['d'] = $d;
                	$d_week = ($d - 7);
                	
                	// Get the filename of the initial called PHP-script to
                	// identify which highlighting to use.
                	$sCalledScript = basename($_SERVER['PHP_SELF']);
                    if (preg_match("/day/i", $sCalledScript)) {
                        $loop_array['type'] = 'day';
                    } elseif (preg_match("/week/i", $sCalledScript)) {
					    $loop_array['type'] = 'week';
                        if (($this->day <= $d) && ($this->day > $d_week) && ($this->h)) {
                            $loop_array['high'] = 'true';
                        } elseif (($this->day < $days_to_highlight) && ($d < $days_to_highlight) && (($day - $daysInPrevMonth) > (-6)) && ($this->month == (($month + 1)%12)) && ($first != 0)) {
                            $loop_array['high'] = 'true';
                        } else {
                            $loop_array['high'] = 'false';
                        }
                    } elseif (preg_match("/month/i", $sCalledScript))
                        $loop_array['type'] = 'month';
                    } else {
		                $loop_array['empty'] = 'true';
		            }
                
                $d++;
                $loop2[] = $loop_array;
            }
            $loop1[] = $loop2;
        }
        
        // Assign all need variable for the template
        $smarty->assign(array(
        	'days_to_highlight' => $days_to_highlight,
        	'daysInPrevMonth' => $daysInPrevMonth,
        	'first' => $first,
			'monthName' => $monthName,
			'year' => $this->year,
        	'month' => $this->month,
        	'loop1' => $loop1,
        	'room' => $this->room, 
        	'area' => $this->area, 
        	'dweek' => $d_week, 
        	'h' => $this->h,
        	'day' => intval($this->day),
			'dmy' => $this->dmy
        ));
        // First days
        $basetime = mktime(12,0,0,6,11+$weekstarts,2000);
      	for ($i = 0, $firstdays = array(); $i < 7; $i++) {
        	$show = $basetime + ($i * 24 * 60 * 60);
         	$firstdays[] = utf8_strftime('%a',$show);
      	}
      	$smarty->assign('firstdays',$firstdays);
      	// At this point we have assigned all variable we need to disply the 
      	// Smarty template for the minicals, but we do not want to send the 
      	// calendars directly to the user, so we will capture the ouput with 
      	// PHP's ouput handling functions.
      	ob_start();
        $smarty->display('minicals.tpl');
        $s = ob_get_contents();
        ob_end_clean();
        
        return $s;
    }
}

/**
 * Build three calendars for a given month (calendars include the previous and
 * the upcoming month). The calendars are embedded in table cells (td-tags).
 * There is no surronding tr- or table-tag, this must be defined separately.
 * 
 * @author David Wilkinson <davidw@cascade.org.uk>, gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @param int $year
 * @param int $month
 * @param int $day
 * @param int $area
 * @param int $room
 * @param string $dmy 
 */
function minicals($year, $month, $day, $area, $room, $dmy) 
{
	// The time for the previous month, 1st, 12:00:00
	$lastmonth = mktime(12, 0, 0, $month-1, 1, $year);
	// The time for the given month, 1st, 12:00:00
	$thismonth = mktime(12, 0, 0, $month, $day, $year);
	// The time for the upcoming month, 1st, 12:00:00
	$nextmonth = mktime(12, 0, 0, $month+1, 1, $year);
	
	// Display the calendar for the previous month
    puts('<td style="width: 100%">&nbsp;</td>');
	puts('<td class="calendar-wrapper">');
	$cal = new Calendar(date("d",$lastmonth), date("m",$lastmonth), date("Y",$lastmonth), 0, $area, $room, $dmy);
	echo $cal->getHTML();
	echo "</td>";
	
	// Display the calendar for the current month
	puts('<td class="calendar-wrapper">');
	$cal = new Calendar(date("d",$thismonth), date("m",$thismonth), date("Y",$thismonth), 1, $area, $room, $dmy);
	echo $cal->getHTML();
	echo "</td>";
	
	// Display the calendar for the upcoming month
	puts('<td class="calendar-wrapper">');
	$cal = new Calendar(date("d",$nextmonth), date("m",$nextmonth), date("Y",$nextmonth), 0, $area, $room, $dmy);
	echo $cal->getHTML();
	echo "</td>";
}
