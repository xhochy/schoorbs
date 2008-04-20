<h2 style="text-align: center;">{$am7}</h2>
{if $pview neq 1}
	<table style="width: 100%;">
	<tr>
		<td>
			<a href="day.php?year={$yy}&amp;month={$ym}&amp;day={$yd}&amp;area={$area}">&lt;&lt;&nbsp;{get_vocab text="daybefore"}</a>
		</td>
        <td style="text-align: center">
        	<a href="day.php?area={$area}">{get_vocab text="gototoday"}</a>
        </td>
        <td style="text-align: right">
        	<a href="day.php?year={$ty}&amp;month={$tm}&amp;day={$td}&amp;area={$area}">{get_vocab text="dayafter"}&nbsp;&gt;&gt;</a>
        </td>
    </tr>
    </table>
{/if}
<script type="text/javascript">
	InitCellManagement({$times_right_side});
</script>
<table cellspacing="0" border="1" width="100%">
<tr>
	<th style="width: 10px;">
		{if $enable_periods eq "true"}{get_vocab text="period"}{else}{get_vocab text="time"}{/if}
	</th>
{foreach from=$rooms item=room}
	<th>
		<a href="week.php?year={$year}&amp;month={$month}&amp;day={$day}&amp;area={$area}&amp;room={$room->getId()}" title="{get_vocab text="viewweek"}: {$room->getDescription()}">
            {$room->getName()|escape:"html"}({$room->getCapacity()})
        </a>
	</th>
{/foreach}
{if $times_right_side eq "true"}
 	<th style="width: 10px;">
		{if $enable_periods eq "true"}{get_vocab text="period"}{else}{get_vocab text="time"}{/if}
	</th>
{/if}
</tr>	
{foreach from=$entries item=entry_row}
	<tr>
		<td class="times">{$entry_row.timestring|escape:"html"}</td>
		{foreach from=$entry_row.entries item=entry}
			<td {if $entry.entry neq null}class="{$entry.entry->getType()}" {/if}style="text-align: center" onmouseover="HighlightCell(this);" onmouseout="UnHighlightCell(this);">
				{if $entry.entry neq null}
					{if $entry.entry->getStartTime() eq $entry_row.time}
						<a class="url" href="view_entry.php?id={$entry.entry->getId()}&amp;area={$area}&amp;day={$day}&amp;month={$month}&amp;year={$year}" title="{$entry.entry->getDescription()|escape:"html"}">
				 			{$entry.entry->getName()} <span class="uid" style="font-weight: bold;">({$entry.entry->getCreateBy()})</span>
						</a>
					{else}
						"
					{/if}
				{else}
					{if $pview neq 1}
						<a href="edit_entry.php?area={$area}&amp;room={$entry.room->getId()}&amp;year={$year}&amp;month={$month}&amp;day={$day}{$entry_row.urlparams}">
						<img alt="schoorbs-misc/gfx/list-add-small.png" src="schoorbs-misc/gfx/list-add-small.png" style="width: 10px; height: 10px; border: 0px" /></a>
					{else}
						&nbsp;
					{/if}
				{/if}
			</td>
		{/foreach}
		{if $times_right_side eq "true"}
			<td class="time">{$time.title}</td>
		{/if}
	</tr>
{/foreach}
</table>
