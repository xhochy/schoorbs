<div id="schoorbs-centeredbox">
  <span class="headline"><?php echo get_vocab('editarea'); ?>:</span>
  <br /><br />
  <div class="form">
    <form method="post" action="<?php echo self::makeInternalUrl('edit-area.php', array('area' => $area->getId())); ?>">
      <label for="area-name"><?php echo get_vocab('name'); ?></label> <input type="text" id="area-name" name="area-name" value="<?php echo ht($area->getName()); ?>" />
      <br />
      <input type="submit"value="<?php echo get_vocab('change'); ?>" />
    </form>
  </div>
</div>
