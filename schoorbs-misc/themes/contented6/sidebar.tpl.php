<!-- include needed JavaScript -->
<?php SchoorbsTPL::includeCSS('yui-2.5.2/build/calendar/assets/skins/sam/calendar.css'); ?>
<?php SchoorbsTPL::includeJS('yui-2.5.2/build/yahoo-dom-event/yahoo-dom-event.js'); ?>
<?php SchoorbsTPL::includeJS('yui-2.5.2/build/calendar/calendar-min.js'); ?>
<?php SchoorbsTPL::includeJS('sidebar.js'); ?>

<h2><?php echo get_vocab('areas'); ?></h2>
<?php 
$aAreas = Area::getAreas(); 
$oActiveArea = Area::getById(input_Area());
$oActiveRoom = Room::getById(input_Room());

if (count($aAreas) > 0) { ?>
<ul>
  <?php foreach($aAreas as $oArea) { ?>
    <li>
      <a <?php if ($oArea->getId() == $oActiveArea->getId()) echo 'style="color: red"'; ?> class="schoorbs" href="#">
        <?php echo $oArea->getName(); ?>
      </a>
    </li>
  <?php } ?>
</ul>
<?php 
} else {
	echo get_vocab('noareas');
} ?>

<h2><?php echo get_vocab('rooms'); ?></h2>
<?php 
$aRooms = Room::getRooms($oActiveArea);
if (count($aRooms) > 0) { ?>
<ul>
  <?php foreach ($aRooms as $oRoom) { ?>
    <li>
      <a <?php if ($oRoom->getId() == $oActiveRoom->getId()) echo 'style="color: red"'; ?> class="schoorbs" href="#">
        <?php echo $oRoom->getName(); ?>
      </a>
    </li>
  <?php } ?>
</ul>
<?php } else {
	echo get_vocab('norooms');
} ?>

<div>&nbsp;<!-- seperator betweewn room listing and the calendar --></div>
<div class="yui-skin-sam" id="calendarPicker"></div>
<div id="nullblock"></div>
