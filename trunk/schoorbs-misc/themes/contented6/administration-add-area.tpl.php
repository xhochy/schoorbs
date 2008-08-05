<div id="schoorbs-administration-add-area">
  <a class="head" href="#"><?php echo get_vocab('addarea'); ?></a>
  <div class="form">
    <form action="<?php echo self::makeInternalUrl('add-area.php'); ?>" method="post">
      Name: <input type="text" name="area-name" />
      <input type="submit" value="<?php echo get_vocab('addarea'); ?>" />
    </form>
  </div>
</div>
