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
{if $javascript_cursor eq "true"}
	<script type="text/javascript">
		InitCellManagement({$times_right_side});
	</script>
{/if}
<table cellspacing="0" border="1" width="100%">
<tr>
	<th style="width: 1%;">{$period_title}</th>
{foreach from=$rooms item=room}
	<th style="width: {$room_column_width}%;">
		<a href="week.php?year={$year}&amp;month={$month}&amp;day={$day}&amp;area={$area}&amp;room={$room.id}" title="{get_vocab text="viewweek"}: {$room.description}">
            {$room.title|escape:"html"}({$room.capacity})
        </a>
	</th>
{/foreach}
{if $times_right_side eq "true"}
 	<th style="width: 1%;">{$period_title}</th>
{/if}
</tr>	
{foreach from=$times item=time}
	<tr>
		<td class="red"><a href="{$hilite_url}={$time.time}" title="{get_vocab text="highlight_line"}">{$time.title}</a></td>
		{foreach from=$time.cols item=col}
			<td class="{$col.css_class}" style="text-align: center"
				{if $javascript_cursor eq "true"}
					onmouseover="HighlightCell(this);"
					onmouseout="UnHighlightCell(this);"
				{/if}>
			{if $col.id eq ""}
				{if $pview neq 1}
					<a href="edit_entry.php?area={$area}&amp;room={$col.room}&amp;year={$year}&amp;month={$month}&amp;day={$day}{$col.period_param}">
					<img alt="gfx/list-add-small.png" src="gfx/list-add-small.png" style="width: 10px; height: 10px; border: 0px" /></a>
				{else}
					&nbsp;
				{/if}
			{elseif $col.descr neq ""}
				<a href="view_entry.php?id={$col.id}&amp;area={$area}&amp;day={$day}&amp;month={$month}&amp;year={$year}" title="{$col.long_descr}">
		 			<span style="font-weight: bold;">{$col.create_by}</span>&mdash;{$col.descr}
				</a>
			{else}
				&nbsp;"&nbsp;
			{/if}
			</td>
		{/foreach}
		{if $times_right_side eq "true"}
			<td class="red"><a href="{$hilite_url}={$time.time}" title="{get_vocab text="highlight_line"}">{$time.title}</a></td>
		{/if}
	</tr>
{/foreach}
</table>