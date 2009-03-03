<?php
/**
 * @todo Support series
 */
?>
<div id="schoorbs-largecenteredbox">
  <span class="headline">
    <?php echo Lang::_('Edit Entry'); ?>
  </span>
  <br /><br />
  <div class="form">
    <form method="post" action="<?php echo self::makeInternalUrl('edit-entry.php'); ?>">
      <label for="main-name"><?php echo Lang::_('Brief Description:'); ?></label> <input type="text" id="main-name" name="name" value="<?php echo htmlentities($entry->getName()); ?>" /><br />
      <label for="main-description" style="width: 400px;"><?php echo preg_replace('/\<br\s*\/?\>/i', '', Lang::_('Full Description:<br />&nbsp;&nbsp;(Number of people,<br />&nbsp;&nbsp;Internal/External etc)')); ?></label><br /> 
      <textarea id="main-description" name="description" rows="8" cols="40"><?php echo htmlentities($entry->getDescription()); ?></textarea><br />
      <label for="main-date"><?php echo Lang::_('Date:'); ?></label> <?php echo SchoorbsTPL::generateDateSelector('edit_', $entry->getStartTime()); ?><br />
      <?php if (Entry::perioded()) { ?>
        <label for="main-period"><?php echo Lang::_('Period:'); ?></label> 
        <select name="period" id="main-period">
          <?php for ($i = 0; $i < count($GLOBALS['periods']); $i++) { ?>
            <option value="<?php echo $i ?>"<?php echo ($i == $entry->getStartPeriod() ? ' selected="selected"' : ''); ?>><?php echo $GLOBALS['periods'][$i]; ?></option>
          <?php } ?>
        </select>
      <?php } else { ?>
        <label for="main-hour"><?php echo Lang::_('Time:'); ?></label>
        <input id="main-hour" name="hour" size="3" value="<?php echo date('G', $entry->getStartTime()); ?>" maxlength="2" />
        &nbsp;&nbsp;:&nbsp;&nbsp;
        <input id="main-minute" name="minute" size="3" value="<?php echo date('i', $entry->getStartTime()); ?>" maxlength="2" />
      <?php } ?> <br />
      <label for="main-duration"><?php echo Lang::_('Duration:'); ?></label>
      <?php 
      if ($entry != null) {
        list($sDuration, $sDurationUnit) = explode(' ', $entry->getDurationString());
      } else {
        $sDuration = 1;
        $sDurationUnit = Lang::_(Entry::perioded() ? 'periods' : 'minutes');
      }
      ?>
      <input id="main-duration" name="duration" size="3" value="<?php echo $sDuration; ?>" />
      <select id="main-dur-units" name="dur_units" style="width: 100px">
        <?php if (Entry::perioded()) { ?>
          <option value="periods"<?php echo (Lang::_('periods') == $sDurationUnit ? ' selected="selected"' : ''); ?>><?php echo Lang::_('periods'); ?></option>
          <option value="days"<?php echo (Lang::_('days') == $sDurationUnit ? ' selected="selected"' : ''); ?>><?php echo Lang::_('days'); ?></option>
        <?php } else { ?>
          <option value="minutes"<?php echo (Lang::_('minutes') == $sDurationUnit ? ' selected="selected"' : '') ?>><?php echo Lang::_('minutes'); ?></option>
          <option value="hours"<?php echo (Lang::_('hours') == $sDurationUnit ? ' selected="selected"' : '') ?>><?php echo Lang::_('hours'); ?></option>
          <option value="days"<?php echo (Lang::_('days') == $sDurationUnit ? ' selected="selected"' : '') ?>><?php echo Lang::_('days'); ?></option>
          <option value="weeks"<?php echo (Lang::_('weeks') == $sDurationUnit ? ' selected="selected"' : '') ?>><?php echo Lang::_('weeks'); ?></option>
        <?php } ?>
      </select><br />
      <label for="main-rooms"><?php echo Lang::_('Room'); ?>:</label>
      <select name="room" id="main-rooms" style="width: 150px;">
        <?php $nActiveRoom = $entry->getRoom()->getId(); ?>
        <?php foreach (Area::getAreas() as $oArea) { ?>
          <optgroup label="<?php echo $oArea->getName(); ?>">
            <?php foreach (Room::getRooms($oArea) as $oRoom) { ?>
              <option value="<?php echo $oRoom->getId(); ?>"<?php if ($oRoom->getId() == $nActiveRoom) echo ' selected="selected"'; ?>>&nbsp;&nbsp;<?php echo $oRoom->getName(); ?></option>
            <?php } ?>
          </optgroup>
        <?php } ?>
      </select><br />
      <label for="main-type"><?php echo Lang::_('Type:'); ?></label> 
      <select id="main-type" name="type">
        <?php foreach ($types as $aType) { ?>
          <option value="<?php echo $aType['c']; ?>"<?php echo ($entry->getType() == $aType['c'] ? ' selected="selected"' : ''); ?>><?php echo $aType['text']; ?></option>
        <?php } ?>
      </select><br />
      <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
        <input type="hidden" name="returl" value="<?php echo htmlentities($_SERVER['HTTP_REFERER']); ?>" />
      <?php } ?>
      <input type="hidden" id="main-id" name="id" value="<?php echo $entry->getId(); ?>" />
      <input type="submit"value="<?php echo Lang::_('Save'); ?>" />
    </form>
  </div>
</div>
</form>
