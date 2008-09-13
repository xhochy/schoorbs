<div id="schoorbs-centeredbox">
  <span class="headline"><?php echo Lang::_('Add Room'); ?>:</span>
  <br /><br />
  <div class="form">
    <form method="post" action="<?php echo self::makeInternalUrl('add-room.php', array('area' => $area->getId())); ?>">
      <label for="room-name" style="width: 100px;"><?php echo Lang::_('Name'); ?></label> <input type="text" id="room-name" name="room-name" value="" /><br />
      <label for="capacity"><?php echo Lang::_('Capacity'); ?></label> <input type="text" id="capacity" name="capacity" value="0" /><br />
      <label for="description"><?php echo Lang::_('Description:'); ?></label> <input type="text" id="description" name="description" value="" /><br />
      <input type="submit"value="<?php echo Lang::_('Change'); ?>" />
    </form>
  </div>
</div>
