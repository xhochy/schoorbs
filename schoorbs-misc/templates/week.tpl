<h2 style="text-align: center">{$this_area_name} - {$this_room_name}</h2>
{if $pview neq 1}
	<table width="100%">
	<tr>
		<td>
	  		<a href="week.php?year={$yy}&amp;month={$ym}&amp;day={$yd}&amp;area={$area}&amp;room={$room}">
	  			&lt;&lt; {get_vocab text="weekbefore"}
	  		</a>
	  	</td>
	  	<td style="text-align: center;">
	  		<a href="week.php?area={$area}&amp;room={$room}">
	  			{get_vocab text="gotothisweek"}
	  		</a>
	  	</td>
	  	<td style="text-align: right;">
	  		<a href="week.php?year={$ty}&amp;month={$tm}&amp;day={$td}&amp;area={$area}&amp;room={$room}">
	  			{get_vocab text="weekafter"} &gt;&gt;
	  		</a>
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
	<th style="width: 1%">
		{if $enable_periods eq "true"}{get_vocab text="period"}{else}{get_vocab text="time"}{/if}
	</th>
	{foreach from=$days item=days_item}
		<th class="weekday-header">
			<a href="day.php?year={$days_item.year}&amp;month={$days_item.month}&amp;day={$days_item.day}&amp;area={$area}" 
				title="{get_vocab text="viewday"}">{$days_item.text}</a>
		</th>
    {/foreach}
    {if $times_right_side eq "true"}
    	<th style="width: 1%">
			<br />
			{if $enable_periods eq "true"}{get_vocab text="period"}{else}{get_vocab text="time"}{/if}
		</th>
	{/if}
</tr>
{foreach from=$times item=times_item}
	<tr>
		<td class="red">
			<a href="{$hilite_url}={$times_item.time_t}"  title="{get_vocab text="highlight_line"}">{$times_item.time}</a>
		</td>
		{foreach from=$times_item.WeekDays item=WeekDay}
			<td class="{$WeekDay.color}" style="text-align: center;"
				{if $javascript_cursor eq "true"}
					onmouseover="HighlightCell(this);"
					onmouseout="UnHighlightCell(this);"
				{/if}>
				{if $WeekDay.id eq ""}
					{if $pview neq 1}
						{if $enable_periods eq "true"}
							<a href="edit_entry.php?room={$room}&amp;area={$area}&amp;period={$WeekDay.time_t_stripped}&amp;year={$WeekDay.wyear}&amp;month={$WeekDay.wmonth}&amp;day={$WeekDay.wday}">
								<img style="border: 0px;" src="schoorbs-misc/gfx/list-add-small.png" width="10" height="10" alt="New Button" />
							</a>
						{else}
							<a href="edit_entry.php?room={$room}&amp;area={$area}&amp;hour={$WeekDay.hour}&amp;minute={$WeekDay.minute}&amp;year={$WeekDay.wyear}&amp;month={$WeekDay.wmonth}&amp;day={$WeekDay.wday}">
								<img style="border: 0px;" src="schoorbs-misc/gfx/list-add-small.png" width="10" height="10" alt="New Button" />
							</a>
						{/if}
					{else}
						&nbsp;
					{/if}
				{elseif $WeekDay.description neq ""}
					<a href="view_entry.php?id={$WeekDay.id}&amp;area={$area}&amp;day={$WeekDay.wday}&amp;month={$WeekDay.wmonth}&amp;year={$WeekDay.wyear}" title="{$WeekDay.long_descr}">
						{$WeekDay.description} <span style="font-weight: bold;">({$WeekDay.create_by})</span>
					</a>
				{else}
					&nbsp;"&nbsp;
				{/if}
			</td>
		{/foreach}
		{if $times_right_side eq "true"}
			<td class="red">
				<a href="{$hilite_url}={$times_item.time_t}"  title="{get_vocab text="highlight_line"}">{$times_item.time}</a>
			</td>
		{/if}
		</tr>
{/foreach}
</table>