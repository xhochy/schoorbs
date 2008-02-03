<?php
/**
 * Input-Plugin 'Duration'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage Input
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 */

/**
 * Figure out the duration and the duration units out of the REQUEST-array
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @return array (duration, dur_units, units)
 */ 
function input_Duration() 
{
	global $enable_periods, $periods, $period;

	if (isset($_REQUEST['duration'])) {
		$duration = max(1, intval($_REQUEST['duration']));
	} else {
		$duration = 1;
	}
	// Support locales where ',' is used as the decimal point
	$duration = preg_replace('/,/', '.', $duration);
		
	if (isset($_REQUEST['dur_units'])) {
		$dur_units = unslashes($_REQUEST['dur_units']);
		
		if ($enable_periods) {
			$minute = $period;
			$max_periods = count($periods);
			if (($dur_units == 'periods') && (($minute + $duration) > $max_periods)) {
				$duration = (1440 * floor($duration / $max_periods)) 
					+ ($duration % $max_periods);
			}
			if(($dur_units == 'days') && $minute == 0 ) {
				$dur_units = 'periods';
				$duration = $max_periods + ($duration - 1) * 1440;
			}
		}
		
		switch($dur_units) {
 		case 'years':
   	    	$units = 31449600;
   	    	break;
		case 'weeks':
	        $units = 604800;
	        break;
	    case 'days':
	        $units = 86400;
	        break;
	    case 'hours':
	        $units = 3600;
	        break;
	    case 'periods':
	    case 'minutes':
	        $units = 60;
	        break;
	    case 'seconds':
		default:
			if ($enable_periods) {
				$dur_units = 'periods';
				$units = 60;
			} else {
				$dur_units = 'seconds';
				$units = 1;
			}
			break;
		}
	} else {
		if ($enable_periods) {
			$dur_units = 'periods';
			$units = 60;
		} else {
			$dur_units = 'seconds';
			$units = 1;
		}
	}

	return array($duration, $dur_units, $units);
}
