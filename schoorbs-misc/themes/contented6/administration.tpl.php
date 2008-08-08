<!-- include needed JavaScript -->
<?php SchoorbsTPL::includeJS('administration.js'); ?>

<h1><?php echo get_vocab('administration'); ?></h1>

<?php SchoorbsTPL::render('administration-add-area'); ?>

<ul id="schoorbs-administration-accordion" class="ui-accordion-container">
<?php
$aAreas = Area::getAreas(); 

foreach ($aAreas as $oArea) {
?>
<li class="ui-accordion-primary-list"> 
        <span class="ui-accordion-left"></span> 
        <a href='#' class="ui-accordion-link">
            <?php echo $oArea->getName(); ?> 
            <span class="ui-accordion-right"></span> 
        </a> 
        <div class="ui-accordion-data">
          <ul>
            <li>
              <a href="<?php echo self::makeInternalUrl('edit-area.php', array('area' => $oArea->getId())); ?>">
                <?php echo get_vocab('edit'); ?>
              </a>
            </li>
            <li>
              <a href="<?php echo 
                self::makeYesNoUrl(sprintf(Lang::_('Do you want to delete the area \'%s\'?'), $oArea->getName()),
                  self::makeInternalUrl('del-area.php', array('area' => $oArea->getId())),
                  self::makeInternalUrl('administration.php', array('area' => $oArea->getId()))
                );
              ?>">
                <?php echo get_vocab('delete'); ?>
              </a>
            </li>
            <li>
              <a href="<?php echo self::makeInternalUrl('add-room.php', array('area' => $oArea->getId())); ?>">
                <?php echo get_vocab('addroom'); ?>
              </a>
            </li>
            <?php 
            $aRooms = Room::getRooms($oArea);
            foreach ($aRooms as $oRoom) {
            ?>
              <li>
              	<strong><?php echo $oRoom->getName(); ?></strong>(<?php echo $oRoom->getCapacity(); ?>): 
              	<a href="<?php echo self::makeInternalUrl('edit-room.php', array('area' => $oArea->getId(), 'room' => $oRoom->getId())); ?>">
              	  <?php echo get_vocab('edit'); ?>
              	</a>
              	<a href="<?php echo 
              	  self::makeYesNoUrl(sprintf(Lang::_('Do you want to delete the room \'%s\' in the area \'%s\'?'), $oRoom->getName(), $oArea->getName()),
              	    self::makeInternalUrl('del-room.php', array('area' => $oArea->getId(), 'room' => $oRoom->getId())),
              	    self::makeInternalUrl('administration.php', array('area' => $oArea->getId()))
              	  );
              	?>">
              	  <?php echo get_vocab('delete'); ?>
              	</a>
              </li>
            <?php } ?>
          </ul>
        </div> 
    </li>
<?php } ?> 
</ul>
