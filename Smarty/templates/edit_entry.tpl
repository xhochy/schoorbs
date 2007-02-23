<script type="text/javascript">
// do a little form verifying
function validate_and_submit ()
{literal}{{/literal}
  // null strings and spaces only strings not allowed
  if(/(^$)|(^\s+$)/.test(document.forms["main"].name.value))
  {literal}{{/literal}
    alert ( "{get_vocab text="you_have_not_entered"}\n{get_vocab text="brief_description"}");
    return false;
  {literal}}{/literal}
  {if $enable_periods neq "true"}
	
	  h = parseInt(document.forms["main"].hour.value);
	  m = parseInt(document.forms["main"].minute.value);
	
	  if(h > 23 || m > 59)
	  {literal}{{/literal}
	    alert("{get_vocab text="you_have_not_entered"}\n{get_vocab text="valid_time_of_day"}");
	    return false;
	  {literal}}{/literal}
  {/if}

  // check form element exist before trying to access it
  if( document.forms["main"].id )
    i1 = parseInt(document.forms["main"].id.value);
  else
    i1 = 0;

  i2 = parseInt(document.forms["main"].rep_id.value);
  if ( document.forms["main"].rep_num_weeks)
  {literal}{{/literal}
  	n = parseInt(document.forms["main"].rep_num_weeks.value);
  {literal}}{/literal}
  if ((!i1 || (i1 && i2)) && document.forms["main"].rep_type && document.forms["main"].rep_type[6].checked && (!n || n < 2))
  {literal}{{/literal}
    alert("{get_vocab text="you_have_not_entered"}\n{get_vocab text="useful_n-weekly_value"}");
    return false;
  {literal}}{/literal}

  // check that a room(s) has been selected
  // this is needed as edit_entry_handler does not check that a room(s)
  // has been chosen
  if( document.forms["main"].elements['rooms[]'].selectedIndex == -1 )
  {literal}{{/literal}
    alert("{get_vocab text="you_have_not_selected"}\n{get_vocab text="valid_room"}");
    return false;
  {literal}}{/literal}

  // Form submit can take some times, especially if mails are enabled and
  // there are more than one recipient. To avoid users doing weird things
  // like clicking more than one time on submit button, we hide it as soon
  // it is clicked.
  document.forms["main"].save_button.disabled="true";

  // would be nice to also check date to not allow Feb 31, etc...
  document.forms["main"].submit();

  return true;
{literal}}{/literal}
function OnAllDayClick(allday) // Executed when the user clicks on the all_day checkbox.
{literal}{{/literal}
  form = document.forms["main"];
  if (allday.checked) // If checking the box...
  {literal}{{/literal}
    {if $enable_periods neq "true"}
      form.hour.value = "00";
      form.minute.value = "00";
    {/if}
    if (form.dur_units.value!="days") // Don't change it if the user already did.
    {literal}{{/literal}
      form.duration.value = "1";
      form.dur_units.value = "days";
    {literal}}{/literal}
  {literal}}{/literal}
{literal}}{/literal}
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

<form name="main" action="edit_entry_handler.php" method="get">
	<table border="0">
	<tr>
		<td class="CR"><strong>{get_vocab text="namebooker"}</strong></td>
  		<td class="CL"><input name="name" size="40" value="{$name|escape:"html"}" /></td>
  	</tr>
	<tr>
		<td class="TR"><strong>{get_vocab text="fulldescription"}</strong></td>
  		<td class="TL">
  			<textarea name="description" rows="8" cols="40">{$description|escape:"html"}</textarea>
  		</td>
  	</tr>
	<tr>
		<td class="CR"><strong>{get_vocab text="date"}</strong></td>
 		<td class="CL">{genDateSelector prefix="" day=$start_day month=$start_month year=$start_year}</td>
	</tr>
	{if $enable_periods neq "true"}
		<tr>
			<td class="CR"><strong>{get_vocab text="time"}</strong></td>
		  	<td class="CL">
		  		<input name="hour" size="2" value="{if $twentyfourhour_format neq "true" and $start_hour > 12}{$start_hour-12}{else}{$start_hour}{/if}" maxlength="2" />
		  		:
		  		<input name="minute" size="2" value="{$start_min}" maxlength="2" />
		  		{if $twentyfourhour_format neq "true"}
		  			<input name="ampm" type="radio" value="am"{if $start_hour < 12} checked="checked"{/if} />{php}echo utf8_date("a",mktime(1,0,0,1,1,2000));{/php}
		   			<input name="ampm" type="radio" value="pm"{if $start_hour >= 12} checked="checked"{/if} />{php}echo utf8_date("a",mktime(13,0,0,1,1,2000));{/php}
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
				<input name="duration" size="7" value="{$duration}" />
				<select name="dur_units">
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
				<input name="all_day" type="checkbox" value="yes" onclick="OnAllDayCick(this)" />&nbsp;{get_vocab text="all_day"}
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
  					<select name="rooms[]" multiple="yes">
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
	{if $display_rep_num_weeks eq "true"}
		<tr>
			<td class="CR"><strong>{get_vocab text="rep_num_weeks"}</strong> {get_vocab text="rep_for_nweekly"}</td>
			<td class="CL"><input type="text" name="rep_num_weeks" value="{$rep_num_weeks}" /></td>
		</tr>
	{/if}
	<tr>
		<td colspan="2" style="text-align: center">
			<script type="text/javascript">
				document.writeln ( '<input type="button" name="save_button" value="{get_vocab text="save"}" onclick="validate_and_submit()" />' );
			</script>
			<noscript>
				<input type="submit" value="{get_vocab text="save"}" />
			</noscript>
		</td>
	</tr>
	</table>
	<input type="hidden" name="returl" value="{$smarty.server.HTTP_REFFERER}" />
	<input type="hidden" name="create_by" value="{$create_by}" />
	<input type="hidden" name="rep_id" value="{$rep_id}" />
	<input type="hidden" name="edit_type" value="{$edit_type}" />
	{if $id neq ""}
		<input type="hidden" name="id" value="{$id}" />
	{/if}
</form>