<div id="schoorbs-centeredbox">
  <span class="headline"><?php echo get_vocab('addroom'); ?>:</span>
  <br /><br />
  <div class="form">
    <form method="post" action="<?php echo self::makeInternalUrl('add-room.php', array('area' => $area->getId())); ?>">
      <label for="room-name" style="width: 100px;"><?php echo get_vocab('name'); ?></label> <input type="text" id="room-name" name="room-name" value="" /><br />
      <label for="capacity"><?php echo get_vocab('capacity'); ?></label> <input type="text" id="capacity" name="capacity" value="0" /><br />
      <label for="description"><?php echo get_vocab('description'); ?></label> <input type="text" id="description" name="description" value="" /><br />
      <input type="submit"value="<?php echo get_vocab('change'); ?>" />
    </form>
  </div>
</div>
