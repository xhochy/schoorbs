<?php
/**
 * Outputs the three calenders at the top of the page
 * 
 * @author David Wilkinson <davidw@cascade.org.uk>, gwalker, Uwe L. Korn <uwelk@xhochy.org>
 * @package PHP-Calender
 */

//PHP Calendar Class
//  
// Copyright David Wilkinson 2000. All Rights reserved.
// 
// This software may be used, modified and distributed freely
// providing this copyright notice remains intact at the head 
// of the file.
//
// This software is freeware. The author accepts no liability for
// any loss or damages whatsoever incurred directly or indirectly 
// from the use of this script.
//
// URL:   http://www.cascade.org.uk/software/php/calendar/
// Email: davidw@cascade.org.uk
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
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
    
        $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
   
        $d = $days[$month - 1];
   
        if ($month == 2)
        {
            // Check for leap year
            // Forget the 4000 rule, I doubt I'll be around then...
        
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                }
                else
                {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }

    function getHTML()
    {
        global $weekstarts, $smarty;
        global $day;
        global $month;

        if (!isset($weekstarts)) $weekstarts = 0;
        $s = "";
        
        $daysInMonth = $this->getDaysInMonth($this->month, $this->year);
		// $prevYear is the current year unless the previous month is
		// December then you need to decrement the year
		if( $this->month - 1 > 0 )
		{
			$prevMonth = $this->month - 1;
			$prevYear = $this->year;
		}
		else
		{
			$prevMonth = 12;
			$prevYear = $this->year -1;
		}
        $daysInPrevMonth = $this->getDaysInMonth($prevMonth, $prevYear);
        $date = mktime(12, 0, 0, $this->month, 1, $this->year);
        
        $first = (strftime("%w",$date) + 7 - $weekstarts) % 7;
        $monthName = utf8_strftime("%B",$date);
        
        $d = 1 - $first;
            
        # this is used to highlight days in upcoming month
        $days_to_highlight = ($d + 7);

		$loop1 = array();
        while ($d <= $daysInMonth)
        {
            $loop2 = array();
            for ($i = 0; $i < 7; $i++)
            {
            	$loop_array = array();		
                if ($d > 0 && $d <= $daysInMonth)
                {
                	$loop_array['empty'] = 'false';
                	$loop_array['d'] = $d;
                	$d_week = ($d - 7);

                    if (preg_match("/day/i", basename($_SERVER['PHP_SELF'])))
                    {
                        $loop_array['type'] = 'day';
                    }
                    elseif (preg_match("/week/i", basename($_SERVER['PHP_SELF'])))
                    {
					    $loop_array['type'] = 'week';
                        if (($this->day <= $d) && ($this->day > $d_week) && ($this->h))
                            $loop_array['high'] = 'true';
                        elseif (($this->day < $days_to_highlight) && ($d < $days_to_highlight) && (($day - $daysInPrevMonth) > (-6)) && ($this->month == (($month + 1)%12)) && ($first != 0))
                            $loop_array['high'] = 'true';
                        else
                            $loop_array['high'] = 'false';
                    }
                    elseif (preg_match("/month/i", basename($_SERVER['PHP_SELF'])))
                        $loop_array['type'] = 'month';
                }
                else
                    $loop_array['empty'] = 'true';
                
                $d++;
                $loop2[] = $loop_array;
            }
            $loop1[] = $loop2;
        }
        
        $smarty->assign('days_to_highlight',$days_to_highlight);
        $smarty->assign('daysInPrevMonth',$daysInPrevMonth);
        $smarty->assign('first',$first);
        $smarty->assign('monthName',$monthName);
        $smarty->assign('year',$this->year);
        $smarty->assign('month',$this->month);
        $smarty->assign('loop1',$loop1);
        $smarty->assign('room',$this->room);
        $smarty->assign('area',$this->area);
        $smarty->assign('dweek',$d_week);
        $smarty->assign('h',$this->h);
        $smarty->assign('day',(int)$this->day);
        $smarty->assign('dmy',$this->dmy);
        // First days
        $basetime = mktime(12,0,0,6,11+$weekstarts,2000);
      	for ($i = 0, $firstdays = array(); $i < 7; $i++)
      	{
        	$show = $basetime + ($i * 24 * 60 * 60);
         	$firstdays[] = utf8_strftime('%a',$show);
      	}
      	$smarty->assign('firstdays',$firstdays);
      	// catch Output
      	ob_start();
        $smarty->display('minicals.tpl');
        $s.= ob_get_contents();
        ob_end_clean();
        
        return $s;
    }
}

/**
 * Specifies the class and builds the three calenders
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
	$lastmonth = mktime(12, 0, 0, $month-1, 1, $year);
	$thismonth = mktime(12, 0, 0, $month,   $day, $year);
	$nextmonth = mktime(12, 0, 0, $month+1, 1, $year);
	
	echo "<td>";
	$cal = new Calendar(date("d",$lastmonth), date("m",$lastmonth), date("Y",$lastmonth), 0, $area, $room, $dmy);
	echo $cal->getHTML();
	echo "</td>";
	
	echo "<td>";
	$cal = new Calendar(date("d",$thismonth), date("m",$thismonth), date("Y",$thismonth), 1, $area, $room, $dmy);
	echo $cal->getHTML();
	echo "</td>";
	
	echo "<td>";
	$cal = new Calendar(date("d",$nextmonth), date("m",$nextmonth), date("Y",$nextmonth), 0, $area, $room, $dmy);
	echo $cal->getHTML();
	echo "</td>";
}
?>
