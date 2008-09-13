<!-- BEGIN of Contented6 styled sidebar -->

<!-- include needed JavaScript -->
<?php SchoorbsTPL::includeJS('yui-2.5.2/build/yahoo-dom-event/yahoo-dom-event.js'); ?>
<?php SchoorbsTPL::includeJS('yui-2.5.2/build/calendar/calendar-min.js'); ?>
<?php SchoorbsTPL::includeJS('sidebar.js'); ?>

<script type="text/javascript">
// Include the translations for the full month names for the calendarPicker.
//
// We include this translation inside the PHP and not with an JS for several
// reasons:
//   -> If we include this translation into an existing javascript file, we
//      had to deliever it via PHP and can't commpressed it while packaging 
//      Schoorbs.
//   -> Making an extra javascript-file with only these translations would end
//      up in another HTTP-request.
// So: Including these translations in this PHP file saves us one HTTP-request 
//     and let's us compress all other javascript files without problems.
//
// Another thing which might confuses some users is the coding style in the
// following two arrays. We have an extra PHP command for each word which has
// to be translated. This might be a minimal impact on perfomance but make these
// arrays a lot better readable.
//
// Ideas for the future:
// @todo Outsource the translation in static language files which will be
//       out of template while packaging Schoorbs. This would enable compressing
//       on these and will lead us to less executed code since the translations
//       are done while packaging and not on demand.
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

// Include the 2-character translations of the week days for the calendarPicker.
//
// For translators: Please only translate them to 2 charaters, anymore 
// characters might make out a nasty overflow in the calendar in some browsers.
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

// Try to fetch the active room for this page. If there is none, $oActiveRoom
// will be null.
$oActiveRoom = Room::getById(input_Room());

if ($oActiveRoom == null) {
	// We have no active room, so try to determinate if we have an active
	// area. 
	$oActiveArea = Area::getById(input_Area());
} else {
	// We have an active room, so let's use its area as active area.
	$oActiveArea = $oActiveRoom->getArea();
}

if (count($aAreas) > 0) { ?>
<!-- Display a list of all available areas -->
<ul>
  <?php foreach($aAreas as $oArea) { ?>
    <li>
      <a <?php if ($oArea->getId() == $oActiveArea->getId()) echo 'style="color: red"'; ?> class="schoorbs" href="<?php echo self::makeInternalUrl('', array('area' => $oArea->getId(), 'room' => null)); ?>">
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
<!-- Displays a list of all available rooms in the active area. -->
<ul>
  <?php foreach ($aRooms as $oRoom) { ?>
    <li>
      <a <?php if ($oRoom->getId() == $oActiveRoom->getId()) echo 'style="color: red"'; ?> class="schoorbs" href="<?php echo self::makeInternalUrl('', array('room' => $oRoom->getId())); ?>">
        <?php echo $oRoom->getName(); ?>
      </a>
    </li>
  <?php } ?>
</ul>
<?php } else {
	echo get_vocab('norooms');
} ?>

<!-- seperator betweewn room listing and the calendar -->
<div>&nbsp;</div>

<!-- The following div-block is the container for YUI calendarPicker. -->
<div class="yui-skin-sam" id="calendarPicker"></div>

<!-- For design reasons we include here this empty div-block -->
<div id="nullblock"></div>

<!-- END of Contented6 styled sidebar -->
