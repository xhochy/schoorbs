<div class="vevent">
  <h3 class="summary">{$name}</h3>
   <table border="0" class="vevent">
     <tr>
       <td><strong>{get_vocab text="description"}</strong></td>
       <td><span class="description">{$description|nl2br}</span></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="room"}:</strong></td>
       <td><span class="location">{$area_name|nl2br} - {$room_name|nl2br}</span></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="start_date"}</strong></td>
       <td><abbr class="dtstart" title="{$mfStartDate}">{$start_date}</abbr></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="duration"}</strong></td>
       <td><span class="duration">{$duration} {$dur_units}</span></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="end_date"}</strong></td>
       <td><abbr class="dtend" title="{$mfEndDate}">{$end_date}</abbr></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="type"}</strong></td>
       <td><span class="class">{$typelabel}</span></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="createdby"}</strong></td>
       <td><span class="uid">{$create_by}</span></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="lastupdate"}</strong></td>
       <td><span class="dtstamp" title="{$mfUpdated}">{$updated}</span></td>
     </tr>
     <tr>
       <td><strong>{get_vocab text="rep_type"}</strong></td>
       <td>{$repeat_key}</td>
     </tr>
    {$repeatAppend}
  </table>
  <br />
</div>
<p>
  {$repeatAppend2}
  <br />
  {$repeatAppend3}
  <br />
</p>   
<div>
  <img src="schoorbs-misc/gfx/microformat_hcalendar.png" alt="This site includes the hCalendar microformat" />
</div>