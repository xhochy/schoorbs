<table class="minical">
<tr>
	<td class="calendarHeader" colspan="7">{$monthName}&nbsp;{$year}</td> 
</tr>
<tr>
{foreach from=$firstdays item=firstdays_item}
	<td class="minical-header">{$firstdays_item}</td>
{/foreach}
</tr>
{foreach from=$loop1 item=loop1_item}
<tr>
	{foreach from=$loop1_item item=loop2_item}
		<td class="{if $loop2_item.empty eq "true"}minical-empty-day">&nbsp;{else}minical-day"><a href="{$dmy}.php?year={$year}&amp;month={$month}&amp;day={$loop2_item.d}&amp;area={$area}{if $room neq ""}&amp;room={$room}{/if}">
			{if $loop2_item.type eq "day"}{if $loop2_item.d eq $day and $h eq 1}<span class="calendarHighlight">{/if}{$loop2_item.d}{if $loop2_item.d eq $day and $h eq 1}</span>{/if}
			{elseif $loop2_item.type eq "week"}{if $loop2_item.high eq "true"}<span class="calendarHighlight">{/if}{$loop2_item.d}{if $loop2_item.high eq "true"}</span>{/if}
			{else}{if $h eq 1}<span class="calendarHighlight">{/if}{$loop2_item.d}{if $h eq 1}</span>{/if}{/if}</a>{/if}</td>	
	{/foreach}
</tr>
{/foreach}
</table>
