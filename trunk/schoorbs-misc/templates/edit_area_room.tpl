<h2>{get_vocab text="editroomarea"}</h2>
  {if $room neq ""}
    <table style="left: 45%; position: relative">
    <tr>
      <td>
        <h3 style="text-align: center">{get_vocab text="editroom"}</h3>
        <form action="edit_area_room.php" method="post">
          <div>
            <input type="hidden" name="room" value="{$row.id}" />
            <table style="text-align: center">
            <tr>
              <td>{get_vocab text="name"}:</td>
              <td><input type="text" name="room_name" value="{$row.room_name|escape:"html"}" /></td>
            </tr>
            <tr>
              <td>{get_vocab text="description"}</td>
              <td><input type="text" name="description" value="{$row.description|escape:"html"}" /></td>
            </tr>
            <tr>
              <td>{get_vocab text="capacity"}:</td>
              <td><input type="text" name="capacity" value="{$row.capacity|escape:"html"}" /></td>
            </tr>
            </table>
            <br />
            <input type="submit" name="change_room" value="{get_vocab text="change"}" />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" name="change_done" value="{get_vocab text="backadmin"}" />
          </div>
        </form>
      </td>
    </tr>
    </table>
  {/if}
  {if $area neq ""}
    <table style="left: 45%; position: relative">
    <tr>
      <td>
        <h3 style="text-align: center;">{get_vocab text="editarea"}</h3>
        <form action="edit_area_room.php" method="post">
          <div>
            <input type="hidden" name="area" value="{$row.id}" />
            <table style="text-align: center">
            <tr>
              <td>{get_vocab text="name"}:</td>
              <td><input type="text" name="area_name" value="{$row.area_name|escape:"html"}" /></td>
            </tr>
            </table>
            <input type="submit" name="change_area" value="{get_vocab text="change"}" />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" name="change_done" value="{get_vocab text="backadmin"}" />
          </div>
        </form>
      </td>
    </tr>
    </table>
  {/if}
