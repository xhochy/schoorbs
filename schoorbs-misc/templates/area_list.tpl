<table width="100%">
    <tr>
        <td>
           <div id="arealist">
             <div id="arealist-title">{get_vocab text="areas"}</div>
             {if $area_list_format eq "select"}
               {$area_select_list}
             {else}
                 {foreach from=$areas item=row}
                     <a href="{$dwm}?year={$year}&amp;month={$month}&amp;day={$day}&amp;area={$row.id}">
                     {if $row.id eq $area}
                       <span style="color: red;">{$row.area_name|escape:"html"}</span>
                     {else}
                       {$row.area_name|escape:"html"}
                     {/if}
                     </a><br />
                 {/foreach}
             {/if}
           </div>
        </td>
