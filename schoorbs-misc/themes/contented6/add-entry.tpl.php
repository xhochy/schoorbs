<div id="schoorbs-largecenteredbox">
  <span class="headline">
    <?php echo Lang::_('Add Entry'); ?>
  </span>
  <br /><br />
  <div class="form">
    <form method="post" action="<?php echo self::makeInternalUrl('add-entry.php'); ?>">
      <label for="main-name"><?php echo Lang::_('Brief Description:'); ?></label> <input type="text" id="main-name" name="name" value="" /><br />
      <label for="main-description" style="width: 400px;"><?php echo preg_replace('/\<br\s*\/?\>/i', '', Lang::_('Full Description:<br />&nbsp;&nbsp;(Number of people,<br />&nbsp;&nbsp;Internal/External etc)')); ?></label><br /> 
      <textarea id="main-description" name="description" rows="8" cols="40"></textarea><br />
      <label for="main-date"><?php echo Lang::_('Date:'); ?></label> <?php echo SchoorbsTPL::generateDateSelector('edit_', $referenceTime ); ?><br />
      <?php if (Entry::perioded()) { ?>
        <label for="main-period"><?php echo Lang::_('Period:'); ?></label> 
        <select name="period" id="main-period">
          <?php for ($i = 0; $i < count($GLOBALS['periods']); $i++) { ?>
            <option value="<?php echo $i ?>"<?php echo ($i == $referencePeriod ? ' selected="selected"' : ''); ?>><?php echo $GLOBALS['periods'][$i]; ?></option>
          <?php } ?>
        </select>
      <?php } else { ?>
        <label for="main-hour"><?php echo Lang::_('Time:'); ?></label>
        <input id="main-hour" name="hour" size="3" value="<?php echo date('G', $referenceTime); ?>" maxlength="2" />
        &nbsp;&nbsp;:&nbsp;&nbsp;
        <input id="main-minute" name="minute" size="3" value="<?php echo date('i', $referenceTime); ?>" maxlength="2" />
      <?php } ?> <br />
      <label for="main-duration"><?php echo Lang::_('Duration:'); ?></label>
      <?php 
      $sDuration = 1;
      $sDurationUnit = Lang::_(Entry::perioded() ? 'periods' : 'minutes');
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
      <label for="main-all-day"><?php echo Lang::_('All day'); ?>:</label> <input name="all_day" id="main-all-day" type="checkbox" value="yes" /><br />
      <label for="main-rooms"><?php echo Lang::_('Rooms'); ?>:</label><br />
      <select name="rooms[]" id="main-rooms" multiple="multiple" size="5" style="width: 300px;">
        <?php $nActiveRoom = input_Room(); ?>
        <?php foreach (Area::getAreas() as $oArea) { ?>
          <optgroup label="<?php echo $oArea->getName(); ?>">
            <?php foreach (Room::getRooms($oArea) as $oRoom) { ?>
              <option value="<?php $oRoom->getId(); ?>"<?php if ($oRoom->getId() == $nActiveRoom) echo ' selected="selected"'; ?>>&nbsp;&nbsp;<?php echo $oRoom->getName(); ?></option>
            <?php } ?>
          </optgroup>
        <?php } ?>
      </select><br />
      <label for="main-minute" style="width: 400px;"><?php echo Lang::_('Use Control-Click to select more than one type'); ?></label><br />
      <label for="main-type"><?php echo Lang::_('Type:'); ?></label> 
      <select id="main-type" name="type">
        <!-- Internal ('I') is the default type -->
        <?php foreach ($types as $aType) { ?>
          <option value="<?php echo $aType['c']; ?>"<?php echo ('I' == $aType['c'] ? ' selected="selected"' : ''); ?>><?php echo $aType['text']; ?></option>
        <?php } ?>
      </select><br />
      <label for="main-reptype"><?php echo Lang::_('Repeat Type:'); ?></label><br />
      <input name="reptype" type="radio" value="0" checked="checked" /> <?php echo Lang::_('None'); ?>
      <input name="reptype" type="radio" value="1" /> <?php echo Lang::_('Daily'); ?>
      <input name="reptype" type="radio" value="2" /> <?php echo Lang::_('Weekly'); ?>
      <input name="reptype" type="radio" value="3" /> <?php echo Lang::_('Monthly'); ?>
      <input name="reptype" type="radio" value="4" /> <?php echo Lang::_('Yearly'); ?><br />
      <input name="reptype" type="radio" value="5" /> <?php echo Lang::_('Monthly, corresponding day'); ?>
      <input name="reptype" type="radio" value="6" /> <?php echo Lang::_('n-Weekly'); ?><br />
      <label for="rep_end_day" style="width: 200px"><?php echo Lang::_('Repeat End Date:'); ?></label> <?php echo SchoorbsTPL::generateDateSelector('rep_end_', $referenceTime); ?><br />
      <label for="main-rep_day_1" style="width: 300px"><?php echo Lang::_('Repeat Day:'); ?> <?php echo Lang::_('(for (n-)weekly)'); ?></label><br />
      <input id="main-rep_day_1" name="rep_day[1]" type="checkbox" /> <?php echo Lang::_('Monday'); ?> 
      <input name="rep_day[2]" type="checkbox" /> <?php echo Lang::_('Tuesday'); ?>
      <input name="rep_day[3]" type="checkbox" /> <?php echo Lang::_('Wednesday'); ?>
      <input name="rep_day[4]" type="checkbox" /> <?php echo Lang::_('Thursday'); ?><br />
      <input name="rep_day[5]" type="checkbox" /> <?php echo Lang::_('Friday'); ?>
      <input name="rep_day[6]" type="checkbox" /> <?php echo Lang::_('Saturday'); ?>
      <input name="rep_day[0]" type="checkbox" /> <?php echo Lang::_('Sunday'); ?><br />
      <label for="main-rep-num-weeks" style="width: 250px"><?php echo Lang::_('Number of weeks'); ?> <?php echo Lang::_('(for n-weekly)'); ?></label>
      <input type="text" id="main-rep-num-weeks" name="rep_num_weeks" value="1" size="4" /><br /><br />
      <?php if (isset($_SERVER['HTTP_REFERER'])) { ?>
        <input type="hidden" name="returl" value="<?php echo htmlentities($_SERVER['HTTP_REFERER']); ?>" />
      <?php } ?>
      <input type="submit"value="<?php echo Lang::_('Save'); ?>" />
    </form>
  </div>
</div>
</form>
