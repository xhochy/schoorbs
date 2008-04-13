<h2>{get_vocab text="administration"}</h2>

<table border="1">
<tr>
	<th style="text-align: center;">
		<strong>
			{get_vocab text="areas"}
		</strong>
	</th>
	<th style="text-align: center;">
		<strong>
			{get_vocab text="rooms"}
			{if $area_name neq ""} {get_vocab text="in"} {$area_name|escape:"html"}{/if}
		</strong>
	</th>
</tr>
<tr>
	<td>
		{if $noareas eq "true"}
			{get_vocab text="noareas"}
		{else}
			<ul>
				{foreach from=$areas item=areas_item}
					<li>
						<a href="admin.php?area={$areas_item->getId()}" style="{if $area eq $areas_item->getId()}color: red;{/if}">
							{$areas_item->getName()|escape:"html"}
						</a>
						(<a href="edit_area_room.php?area={$areas_item->getId()}">{get_vocab text="edit"}</a>)
						(<a href="del.php?type=area&amp;area={$areas_item->getId()}">{get_vocab text="delete"}</a>)
					</li>
				{/foreach}
			</ul>
		{/if}
	</td>
	<td>
		{if $area neq 0}
				{if $norooms eq "true"}
					{get_vocab text="norooms"}
				{else}
					<ul>
						{foreach from=$rooms item=rooms_item}
							<li>
								{$rooms_item.name|escape:"html"} ({$rooms_item.description|escape:"html"}, {$rooms_item.capacity})
								(<a href="edit_area_room.php?room={$rooms_item.id}">{get_vocab text="edit"}</a>)
								(<a href="del.php?type=room&amp;room={$rooms_item.id}">{get_vocab text="delete"}</a>)
							</li>
						{/foreach}
					</ul>
				{/if}
		{else}
			{get_vocab text="noarea"}
		{/if}
	</td>
</tr>
<tr>
	<td>
		<h3 style="text-align: center;">{get_vocab text="addarea"}</h3>
		<form action="add.php" method="post">
			<div class="form_wrapper">
				<input type="hidden" name="type" value="area" />
				<table>
				<tr>
					<td>{get_vocab text="name"}</td>
					<td><input type="text" name="name" /></td>
				</tr>
				</table>
				<input type="submit" value="{get_vocab text="addarea"}" />
			</div>
		</form>
	</td>
	<td>
		{if $area neq 0}
			<h3 style="text-align: center">{get_vocab text="addroom"}</h3>
			<form action="add.php" method="post">
				<div class="form_wrapper">
					<input type="hidden" name="type" value="room" />
					<input type="hidden" name="area" value="{$area}" />
					<table>
					<tr>
						<td>{get_vocab text="name"}:</td>
						<td><input type="text" name="name" /></td>
					</tr>
					<tr>
						<td>{get_vocab text="description"}</td>
						<td><input type="text" name="description" /></td>
					</tr>
					<tr>
						<td>{get_vocab text="capacity"}:</td>
						<td><input type="text" name="capacity" /></td>
					</tr>
					</table>
					<input type="submit" value="{get_vocab text="addroom"}" />
				</div>
			</form>
		{else}
			&nbsp;
		{/if}
	</td>
</tr>
</table>
<div class="browserlang">
	<br />
	{get_vocab text="browserlang"} {$smarty.server.HTTP_ACCEPT_LANGUAGE} {get_vocab text="postbrowserlang"}
</div>
