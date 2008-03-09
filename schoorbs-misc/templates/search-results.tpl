<table style="width: 100%;">
  <tr>
    <th>{get_vocab text="namebooker"}</th>
    <th>{get_vocab text="start_date"}</th>
    <th>{get_vocab text="end_date"}</th>
    <th>{get_vocab text="description"}</th>
  </tr>
  {foreach from=$bookings item=booking}
    <tr>
      <td style="text-align: center">
        <a href="view_entry.php?id={$booking.id}">{$booking.name}</a>
      </td>
      <td style="text-align: center">{$booking.start_time}</td>
      <td style="text-align: center">{$booking.end_time}</td>
      <td style="text-align: center">{$booking.description}</td>
    </tr>
  {/foreach}
</table>
