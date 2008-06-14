<h2 style="text-align: center;">{$this_time_name} - {$this_area_name} - {$this_room_name}</h2>
{if $pview neq 1}
	<table width="100%">
	<tr>
		<td>
			<a href="month.php?year={$yy}&amp;month={$ym}&amp;area={$area}&amp;room={$room}">
				&lt;&lt; {get_vocab text="monthbefore"}
			</a>
		</td>
		<td style="text-align: center;">
			<a href="month.php?area={$area}&amp;room={$room}">
				{get_vocab text="gotothismonth"}
			</a>
		</td>
      	<td style="text-align: right;">
      		<a href="month.php?year={$ty}&amp;month={$tm}&amp;area={$area}&amp;room={$room}">
      			{get_vocab text="monthafter"} &gt;&gt;
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
<table border="1" cellspacing="0" width="100%">
<tr>
{foreach from=$daynames item=dayname}
	<th style="width: 14%;">{$dayname}</th>
{/foreach}
</tr>
<tr>
{foreach from=$skipdays item=skipday}
	<td style="background-color: #cccccc; height: 100px;">&nbsp;</td>
{/foreach}
{foreach from=$days item=days_item}
	{if $days_item.breakline eq "true"}
		</tr>
		<tr>
	{/if}
	<td style="vertical-align: top; height: 100px;" class="month"
	{if $javascript_cursor eq "true"}
		onmouseover="HighlightCell(this);"
		onmouseout="UnHighlightCell(this);"
	{/if}>
		<div class="monthday">
			<a href="day.php?year={$year}&amp;month={$month}&amp;day={$days_item.cday}&amp;area={$area}">
				{$days_item.cday}
			</a>
			&nbsp;
    	</div>
    	{if $days_item.defined eq "true"}
    		<span style="font-size: 8px;">
    			{$days_item.out}
    		</span>
    	{/if}
 		<br />
 		{if $pview neq 1}
        	{if $enable_periods eq "true"}
            	<a href="edit_entry.php?room={$room}&amp;area={$area}&amp;period=0&amp;year={$year}&amp;month={$month}&amp;day={$days_item.cday}">
            	
        	{else}
            	<a href="edit_entry.php?room={$room}&amp;area={$area}&amp;hour={$morningstarts}&amp;minute=0&amp;year={$year}&amp;month={$month}&amp;day={$days_item.cday}">
            {/if}
            	<img src="schoorbs-misc/gfx/list-add-small.png" style="border: 0px;" alt="neuer Eintrag" />
            </a>
    	{else}
        	&nbsp;
        {/if}
	</td>
{/foreach}
{foreach from=$skipdays2 item=skipday}
	<td style="background-color: #cccccc; height: 100px;">&nbsp;</td>
{/foreach}
</tr>
</table>