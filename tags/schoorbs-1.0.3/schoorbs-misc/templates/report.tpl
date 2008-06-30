{if $pview neq 1}
	<h1>{get_vocab text="report_on"}</h1>
	<form method="get" action="report.php">
	<table>
	<tr>
		<td class="CR">{get_vocab text="report_start"}</td>
    	<td class="CL">
    		<span style="font-size: 10px;">
    			{genDateSelector prefix="From_" day=$From_day month=$From_month year=$From_year}
    		</span>
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="report_end"}</td>
    	<td class="CL">
    		<span style="font-size: 10px;">
    			{genDateSelector prefix="To_" day=$To_day month=$To_month year=$To_year}
    		</span>
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="match_area"}</td>
    	<td class="CL">
    		<input type="text" name="areamatch" size="18" value="{$areamatch_default}" />
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="match_room"}</td>
    	<td class="CL">
    		<input type="text" name="roommatch" size="18" value="{$roommatch_default}" />
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="match_type"}</td>
    	<td class="CL" style="vertical-align: top;">
    		<table>
    		<tr>
    			<td>
        			<select name="typematch[]" multiple="multiple">
        				{foreach from=$typel item=typel_item}
        					<option value="{$typel_item.key}"{if $typel_item.selected eq "true"} selected="selected"{/if}>
		     					{$typel_item.val}
		     				</option>
        				{/foreach}
					</select>
				</td>
				<td>{get_vocab text="ctrl_click_type"}</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="CR">{get_vocab text="match_entry"}</td>
    	<td class="CL">
    		<input type="text" name="namematch" size="18" value="{$namematch_default}" />
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="match_descr"}</td>
    	<td class="CL">
    		<input type="text" name="descrmatch" size="18" value="{$descrmatch_default}" />
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="createdby"}</td>
    	<td class="CL">
    		<input type="text" name="creatormatch" size="18" value="{$creatormatch_default}" />
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="include"}</td>
    	<td class="CL">
      		<input type="radio" name="summarize" value="1"{if $summarize eq 1} checked="checked"{/if} />{get_vocab text="report_only"}
      		<input type="radio" name="summarize" value="2"{if $summarize eq 2} checked="checked"{/if} />{get_vocab text="summary_only"}
      		<input type="radio" name="summarize" value="3"{if $summarize eq 3} checked="checked"{/if} />{get_vocab text="report_and_summary"}
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="sort_rep"}</td>
    	<td class="CL">
      		<input type="radio" name="sortby" value="r"{if $sortby eq "r"} checked="checked"{/if} />{get_vocab text="room"}
      		<input type="radio" name="sortby" value="s"{if $sortby eq "s"} checked="checked"{/if} />{get_vocab text="sort_rep_time"}
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="rep_dsp"}</td>
    	<td class="CL">
      		<input type="radio" name="display" value="d"{if $display eq "d"} checked="checked"{/if} />{get_vocab text="rep_dsp_dur"}
      		<input type="radio" name="display" value="e"{if $display eq "e"} checked="checked"{/if} />{get_vocab text="rep_dsp_end"}
    	</td>
    </tr>
	<tr>
		<td class="CR">{get_vocab text="summarize_by"}</td>
    	<td class="CL">
      		<input type="radio" name="sumby" value="d"{if $sumby eq "d"} checked="checked"{/if} />{get_vocab text="sum_by_descrip"}
      		<input type="radio" name="sumby" value="c"{if $sumby eq	"c"} checked="checked"{/if} />{get_vocab text="sum_by_creator"}
    	</td>
    </tr>
	<tr>
		<td colspan="2" style="text-align: center;">
			<input type="submit" value="{get_vocab text="submitquery"}" />
		</td>
	</tr>
	</table>
	</form>
{/if}