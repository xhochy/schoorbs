<?php SchoorbsTPL::includeJS('search.js'); ?>
<div id="schoorbs-search-container">
  <ul id="schoorbs-search-tabs">
    <li><a href="#fragment-1"><?php echo Lang::_('Search'); ?></a></li>
    <li><a href="#fragment-2"><?php echo Lang::_('Advanced search'); ?></a></li>
  </ul>
    
  <form action="search.php" method="post">
    <div id="fragment-1">
      <label for="search-for"><?php echo Lang::_('Search For'); ?></label>
      <input type="text" name="search-for" id="search-for" size="25" />
      <input type="submit" class="submit" value="<?php echo Lang::_('Search'); ?>" />
      <input type="hidden" name="searchtype" value="simple" />
    </div>
  </form>
  
  <div id="fragment-2">
    <form action="search.php" method="post">
      <ul>
        <li>
          <label for="description"><?php echo Lang::_('Description:'); ?></label>
          <input type="text" name="description" id="description" size="25" />
        </li>
        <li>
          <label for="create_by"><?php echo Lang::_('Created By:'); ?></label>
          <input type="text" name="create_by" id="create_by" size="25" />
        </li>
        <li>
          <label for="type"><?php echo Lang::_('Type:'); ?></label>
          <select name="type" id="type">
            <option value="-ignore-" selected="selected">----</option>
            <?php foreach($types as $aType) { ?>
              <option value="<?php echo $aType['c']; ?>"><?php echo $aType['text']; ?></option>
            <?php } ?>
          </select>
        </li>
        <li>
          <label for="room"><?php echo Lang::_('Room:'); ?></label>
          <select name="room" id="room">
            <option value="-1" selected="selected">----</option>
            <?php 
              foreach(Area::getAreas() as $oArea) { 
                $aRooms = Room::getRooms($oArea);
                if (count($aRooms) == 0) continue;
            ?>
              <optgroup label="<?php echo $oArea->getName(); ?>">
                <?php foreach ($aRooms as $oRoom) { ?>
                  <option value="<?php echo $oRoom->getId(); ?>"><?php echo $oRoom->getName(); ?></option>
                <?php } ?>
              </optgroup>
            <?php } ?>
          </select>
        </li>
        <li>
          <input type="submit" class="submit" value="<?php echo Lang::_('Search'); ?>" />
          <input type="hidden" name="searchtype" value="advanced" />
        </li>
      </ul>
    </form>
  </div>
</div>
