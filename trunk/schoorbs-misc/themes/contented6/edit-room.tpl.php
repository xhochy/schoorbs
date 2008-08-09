<div id="schoorbs-centeredbox">
  <span class="headline"><?php echo get_vocab('editroom'); ?>:</span>
  <br /><br />
  <div class="form">
    <form method="post" action="<?php echo self::makeInternalUrl('edit-room.php', array('room' => $room->getId())); ?>">
      <label for="room-name" style="width: 100px;"><?php echo get_vocab('name'); ?></label> <input type="text" id="room-name" name="room-name" value="<?php echo $room->getName(); ?>" /><br />
      <label for="capacity"><?php echo get_vocab('capacity'); ?></label> <input type="text" id="capacity" name="capacity" value="<?php echo $room->getCapacity(); ?>" /><br />
      <label for="description"><?php echo get_vocab('description'); ?></label> <input type="text" id="description" name="description" value="<?php echo $room->getDescription(); ?>" /><br />
      <input type="submit"value="<?php echo get_vocab('change'); ?>" />
    </form>
  </div>
</div>
