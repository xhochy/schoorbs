<script src="schoorbs-misc/js/editentry.js" type="text/javascript"></script>
<script type="text/javascript">
var you_have_not_entered = "{get_vocab text="you_have_not_entered"}";
var brief_description = "{get_vocab text="brief_description"}";
var valid_time_of_day = "{get_vocab text="valid_time_of_day"}";
var valid_room = "{get_vocab text="valid_room"}";
{if $enable_periods neq "true"}
var enablePeriods = false;
{else}
var enablePeriods = true;
{/if}
</script>
<h2>
	{if $id neq -1}
		{if $edit_type eq "series"}
			{get_vocab text="editseries"}
		{else}
			{get_vocab text="editentry"}
		{/if} 
	{else}
		{get_vocab text="addentry"}
	{/if}
</h2>

<form id="main-form" action="edit_entry_handler.php" method="get">
	<table border="0">
	<tr>
		<td class="CR"><strong>{get_vocab text="namebooker"}</strong></td>
  		<td class="CL"><input id="main-name" name="name" size="40" value="{$name|escape:"html"}" /></td>
  	</tr>
	<tr>
		<td class="TR"><strong>{get_vocab text="fulldescription"}</strong></td>
  		<td class="TL">
  			<textarea name="description" rows="8" cols="40">{$description|escape:"html"}</textarea>
  		</td>
  	</tr>
	<tr>
		<td class="CR"><strong>{get_vocab text="date"}</strong></td>
 		<td class="CL">{genDateSelector prefix="edit_" day=$start_day month=$start_month year=$start_year}</td>
	</tr>
	{if $enable_periods neq "true"}
		<tr>
			<td class="CR"><strong>{get_vocab text="time"}</strong></td>
		  	<td class="CL">
		  		<input id="main-hour" name="hour" size="2" value="{if $twentyfourhour_format neq "true" and $start_hour > 12}{$start_hour-12}{else}{$start_hour}{/if}" maxlength="2" />
		  		:
		  		<input id="main-minute" name="minute" size="2" value="{$start_min}" maxlength="2" />
		  		{if $twentyfourhour_format neq "true"}
		  			<input name="ampm" type="radio" value="am"{if $start_hour < 12} checked="checked"{/if} />{php}echo utf8_strftime("%p",mktime(1,0,0,1,1,2000));{/php}
		   			<input name="ampm" type="radio" value="pm"{if $start_hour >= 12} checked="checked"{/if} />{php}echo utf8_strftime("%p",mktime(13,0,0,1,1,2000));{/php}
				{/if}
			</td>
		</tr>
	{else}
		<tr>
			<td class="CR"><strong>{get_vocab text="period"}</strong></td>
		  	<td class="CL">
		    	<select name="period">
		    		{foreach from=$periods item=p_val key=p_num}
		    			<option value="{$p_num}"{if $p_num eq $period or $p_num eq $start_min} selected="selected"{/if}>{$p_val}</option>
		    		{/foreach}
		    	</select>
		    </td>
		</tr>
	{/if}
		<tr>
			<td class="CR"><strong>{get_vocab text="duration"}</strong></td>
			<td class="CL">
				<input id="main-duration" name="duration" size="7" value="{$duration}" />
				<select id="main-dur-units" name="dur_units">
					{if $enable_periods eq "true"}
						{capture assign=unit_text}{get_vocab text="periods"}{/capture}
						<option value="periods"{if $dur_units eq $unit_text} selected="selected"{/if}>{$unit_text}</option>
						{capture assign=unit_text}{get_vocab text="days"}{/capture}
						<option value="days"{if $dur_units eq $unit_text} selected="selected"{/if}>{$unit_text}</option>
					{else}
						{capture assign=unit_text}{get_vocab text="minutes"}{/capture}
						<option value="minutes"{if $dur_units eq $unit_text} selected="selected"{/if}>{get_vocab text="minutes"}</option>
						{capture assign=unit_text}{get_vocab text="hours"}{/capture}
						<option value="hours"{if $dur_units eq $unit_text} selected="selected"{/if}>{$unit_text}</option>
						{capture assign=unit_text}{get_vocab text="days"}{/capture}
						<option value="days"{if $dur_units eq $unit_text} selected="selected"{/if}>{$unit_text}</option>
						{capture assign=unit_text}{get_vocab text="weeks"}{/capture}
						<option value="weeks"{if $dur_units eq $unit_text} selected="selected"{/if}>{$unit_text}</option>
					{/if}
				</select>
				<input name="all_day" id="all-day-checkbox" type="checkbox" value="yes"  />&nbsp;{get_vocab text="all_day"}
			</td>
		</tr>
        {if $num_areas > 1}
			<script type="text/javascript">
			{literal}
				function changeRooms( formObj )
				{
				    areasObj = eval("formObj.areas");
				
				    area = areasObj[areasObj.selectedIndex].value
				    roomsObj = eval("formObj.elements['rooms[]']")
				
				    // remove all entries
				    for (i=0; i < (roomsObj.length); i++)
				    {
				      roomsObj.options[i] = null
				    }
				    // add entries based on area selected
				    switch (area){
			{/literal}
			{$change_room_js_add}
			{literal}
				    } //switch
				}
			{/literal}
			// create area selector if javascript is enabled as this is required
			// if the room selector is to be updated.
			this.document.writeln("<tr><td class=\"CR\"><strong>{get_vocab text="areas"}:</strong></td><td class=\"CL\" style=\"vertical-align: top;\">");
			this.document.writeln("          <select name=\"areas\" onChange=\"changeRooms(this.form)\">");
			{$js_add1}
			this.document.writeln("          </select>");
			this.document.writeln("</td></tr>");
		</script>
	{/if}
	<tr>
		<td class="CR"><strong>{get_vocab text="rooms"}:</strong></td>
  		<td class="CL" style="vertical-align: top;">
  			<table>
  			<tr>
  				<td>
  					<select name="rooms[]" id="main-rooms" multiple="multiple">
  						{foreach from=$rooms item=rooms_item}
  							<option {if $rooms_item.id eq $room_id}selected="selected" {/if}value="{$rooms_item.id}">{$rooms_item.name}</option>
  						{/foreach}
  					</select>
  				</td>
  				<td>{get_vocab text="ctrl_click"}</td>
  			</tr>
  			</table>
    	</td>
    </tr>
	<tr>
		<td class="CR"><strong>{get_vocab text="type"}</strong></td>
		<td class="CL">
			<select name="type">
				{foreach from=$types item=types_item}
					<option value="{$types_item.c}"{if $type eq $types_item.c} selected="selected"{/if}>{$types_item.text}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	{if $edit_type eq "series"}
		<tr>
			<td class="CR"><strong>{get_vocab text="reg_type"}</strong></td>
			<td class="CL">
				{foreach from=$rep_types item=rep_types_item}
					<input name="reptype" type="radio" value="{$rep_types_item.id}"{if $rep_types_item.id eq $rep_type} checked="checked"{/if} />
					{$rep_types_item.text} 
				{/foreach}
			</td>
		</tr>
		<tr>
			<td class="CR"><strong>{get_vocab text="rep_end_date"}</strong></td>
			<td class="CL">{genDateSelector prefix="rep_end_" day=$rep_end_day month=$rep_end_month year=$rep_end_year}</td>
		</tr>
		<tr>
			<td class="CR"><strong>{get_vocab text="rep_rep_day"}</strong> {get_vocab text="rep_for_weekly"}</td>
			<td class="CL">
				{foreach from=$rep_days item=rep_days_item}
					<input name="rep_day[{$rep_days_item.wday}]" type="checkbox"{if $rep_days_item.checked eq "true"} checked="checked"{/if} />
					{$rep_days_item.name}
				{/foreach}
			</td>
		</tr>
	{else}
		<tr>
			<td class="CR"><strong>{get_vocab text="rep_type"}</strong></td>
			<td class="CL">{get_vocab text=$rep_key}</td>
		</tr>
		{$rep_add}
	{/if}
	{if $display_rep_num_week eq "true"}
		<tr>
			<td class="CR"><strong>{get_vocab text="rep_num_weeks"}</strong> {get_vocab text="rep_for_nweekly"}</td>
			<td class="CL"><input type="text" id="main-rep-num-weeks" name="rep_num_weeks" value="{$rep_num_weeks}" /></td>
		</tr>
	{/if}
	<tr>
		<td colspan="2" style="text-align: center">
			<script type="text/javascript">
				// <![CDATA[
				document.writeln ( '<input id="main-save-button" type="button" name="save_button" value="{get_vocab text="save"}" onclick="validate_and_submit()" />' );
				// ]]>
			</script>
			<noscript>
				<div><input type="submit" value="{get_vocab text="save"}" /></div>
			</noscript>
		</td>
	</tr>
	</table>
	<div>
	<input type="hidden" name="returl" value="{$smarty.server.HTTP_REFERER|escape:"html"}" />
	<input type="hidden" name="create_by" value="{$create_by}" />
	<input type="hidden" id="main-rep-id" name="rep_id" value="{$rep_id}" />
	<input type="hidden" name="edit_type" value="{$edit_type}" />
	{if $id neq ""}
		<input type="hidden" id="main-id" name="id" value="{$id}" />
	{/if}
        </div>
</form>
