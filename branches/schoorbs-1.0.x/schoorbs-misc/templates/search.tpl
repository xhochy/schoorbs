<script type="text/javascript" src="schoorbs-misc/js/jquery.idTabs.pack.js"></script>
<div id="schoorbs-search-container">
  <ul class="idTabs">
    <li><a href="#fragment-1">{get_vocab text="search"}</a></li>
    <li><a href="#fragment-2">{get_vocab text="advanced_search"}</a></li>
  </ul>
    
  <form action="search.php" method="post">
    <div id="fragment-1">
      <label for="search-for">{get_vocab text="search_for"}</label>
      <input type="text" name="search-for" id="search-for" size="25" />
      <input type="submit" class="submit" value="{get_vocab text="search_button"}" />
      <input type="hidden" name="searchtype" value="simple" />
    </div>
  </form>
  
  <div id="fragment-2">
    <form action="search.php" method="post">
      <ul>
        <li>
          <label for="description">{get_vocab text="description"}</label>
          <input type="text" name="description" id="description" size="25" />
        </li>
        <li>
          <label for="create_by">{get_vocab text="createdby"}</label>
          <input type="text" name="create_by" id="create_by" size="25" />
        </li>
        <li>
          <label for="type">{get_vocab text="type"}</label>
          <select name="type" id="type">
            <option value="-ignore-" selected="selected">----</option>
            {foreach from=$types item=types_item}
              <option value="{$types_item.c}">{$types_item.text}</option>
            {/foreach}
			</select>
        </li>
        <li>
          <label for="room">{get_vocab text="room"}</label>
          <select name="room" id="room">
            <option value="-1" selected="selected">----</option>
            {foreach from=$areas item=area}
              <optgroup label="{$area.area_name}">
                {foreach from=$rooms[$area.id] item=room}
                  <option value="{$room.id}">{$room.name}</option>
                {/foreach}
              </optgroup>
            {/foreach}
          </select>
        </li>
        <li>
          <input type="submit" class="submit" value="{get_vocab text="search_button"}" />
          <input type="hidden" name="searchtype" value="advanced" />
        </li>
      </ul>
    </form>
  </div>
</div>
