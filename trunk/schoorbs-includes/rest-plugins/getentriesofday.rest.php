<?php
/**
 * REST-Plugin 'getEntriesOfDay'
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 * @package Schoorbs
 * @subpackage REST
 */
 
/**
 * Returns all entries of one Day for a special room
 * 
 * @author Uwe L. Korn
 */ 
function rest_function_getEntriesOfDay()
{
	global $morningstarts, $morningstarts_minutes;
	global $eveningends, $eveningends_minutes;
	global $tbl_entry, $_TPL;
	global $enable_periods, $periods;
	
	list($nDay, $nMonth, $nYear) = input_DayMonthYear();
	$nRoom = input_Room();
	
	if($enable_periods) 
	{
		$morningstarts = 12;
		$morningstarts_minutes = 0;
		$eveningends = 12;
		$eveningends_minutes = count($periods)-1;
	}
	
	$nAM7 = mktime($morningstarts,$morningstarts_minutes,0,
		$nMonth,$nDay,$nYear,is_dst($nMonth,$nDay,$nYear,$morningstarts));
	$nPM7 = mktime($eveningends,$eveningends_minutes,0,
		$nMonth,$nDay,$nYear,is_dst($nMonth,$nDay,$nYear,$eveningends));
	
	
	$sQuery = 'SELECT start_time, end_time, id, name, description, create_by FROM '.$tbl_entry
		.' WHERE room_id = '.$nRoom.' AND start_time <= '.$nPM7.' AND end_time > '.$nAM7;
	$res = sql_query($sQuery);
	if(!$res)
		sendRESTError('Database failure: '.sql_error(),3);
	$aEntries = array();
	for ($i = 0; ($row = sql_row($res, $i)); $i++) 
	{
		$aEntries[] = array('start_time' => $row[0], 'end_time' => $row[1], 'id' => $row[2],
			'name' => $row[3], 'description' => $row[4], 'create_by' => $row[5]);	
	}
	sendRESTHeaders();
	$_TPL->assign('entries',$aEntries);
	$_TPL->display('getentriesofday.tpl');
}
?>
