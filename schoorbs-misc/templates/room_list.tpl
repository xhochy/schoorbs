<td>
  <div id="roomlist">
	<div id="roomlist-title">{get_vocab text="rooms"}</div>
	{if $area_list_format eq "select"}
           {$room_select_list}
	{else}
		{foreach from=$rooms item=rooms_item}
			<a href="{$dwm}?year={$year}&amp;month={$month}&amp;day={$day}&amp;area={$area}&amp;room={$rooms_item.id}" title="{$rooms_item.description}">
				{if $rooms_item.id eq $room}<span style="color: red;">{/if}
				{$rooms_item.name|escape:"html"}
				{if $rooms_item.id eq $room}</span>{/if}
			</a>
			<br />
		{/foreach}
	{/if}
  </div>
</td>  