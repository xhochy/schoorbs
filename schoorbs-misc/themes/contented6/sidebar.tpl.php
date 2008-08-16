<!-- include needed JavaScript -->
<?php SchoorbsTPL::includeJS('yui-2.5.2/build/yahoo-dom-event/yahoo-dom-event.js'); ?>
<?php SchoorbsTPL::includeJS('yui-2.5.2/build/calendar/calendar-min.js'); ?>
<?php SchoorbsTPL::includeJS('sidebar.js'); ?>

<script type="text/javascript">
var sidebarMonthsLong = [
	"<?php echo Lang::_('January'); ?>",
	"<?php echo Lang::_('February'); ?>",
	"<?php echo Lang::_('March'); ?>",
	"<?php echo Lang::_('April'); ?>",
	"<?php echo Lang::_('May'); ?>",
	"<?php echo Lang::_('June'); ?>",
	"<?php echo Lang::_('July'); ?>",
	"<?php echo Lang::_('August'); ?>",
	"<?php echo Lang::_('September'); ?>",
	"<?php echo Lang::_('October'); ?>",
	"<?php echo Lang::_('November'); ?>",
	"<?php echo Lang::_('December'); ?>"
];

var sidebarDaysShort = [
	"<?php echo Lang::_('Su'); ?>",
	"<?php echo Lang::_('Mo'); ?>",
	"<?php echo Lang::_('Tu'); ?>",
	"<?php echo Lang::_('We'); ?>",
	"<?php echo Lang::_('Th'); ?>", 
	"<?php echo Lang::_('Fr'); ?>",
	"<?php echo Lang::_('Sa'); ?>"
];
</script>

<h2><?php echo get_vocab('areas'); ?></h2>
<?php 
$aAreas = Area::getAreas(); 
$oActiveRoom = Room::getById(input_Room());
$oActiveArea = $oActiveRoom->getArea();

if (count($aAreas) > 0) { ?>
<ul>
  <?php foreach($aAreas as $oArea) { ?>
    <li>
      <a <?php if ($oArea->getId() == $oActiveArea->getId()) echo 'style="color: red"'; ?> class="schoorbs" href="?area=<?php echo $oArea->getId(); ?>">
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
      <a <?php if ($oRoom->getId() == $oActiveRoom->getId()) echo 'style="color: red"'; ?> class="schoorbs" href="?room=<?php echo $oRoom->getId(); ?>">
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
