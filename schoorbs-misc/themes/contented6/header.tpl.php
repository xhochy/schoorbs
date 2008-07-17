<div id="header">
  <div id="title">
    <div id="schoorbs-loginbox">
      <strong>*TODO*</strong>
      Username: <input type="text" size="5" /> 
      Password: <input type="password" size="5" />
      <input type="submit" />
    </div>
    <?php echo SchoorbsConfig::getOption('company'); ?>
  </div>
  <ul id="nav">
    <li>
      <a <?php if (self::$sPage == 'search') { ?>class="selected"<?php } ?> href="<?php echo SchoorbsTPL::getSearchUrl(); ?>">
        <?php echo get_vocab('search'); ?>
      </a>
    </li>
    <li>
      <a <?php if (self::$sPage == 'help') { ?>class="selected"<?php } ?> href="<?php echo SchoorbsTPL::getHelpUrl(); ?>">
        <?php echo get_vocab('help'); ?>
      </a>
    </li>
    <li>
      <a <?php if (self::$sPage == 'administration') { ?>class="selected"<?php } ?> href="<?php echo SchoorbsTPL::getAdminUrl(); ?>">
        <?php echo get_vocab('admin'); ?>
      </a>
    </li>
    <li>
      <a <?php if (self::$sPage == 'month-view') { ?>class="selected"<?php } ?> href="<?php echo SchoorbsTPL::getMonthViewUrl(); ?>">
        <?php echo get_vocab('viewmonth'); ?>
      </a>
    </li>
    <li>
      <a <?php if (self::$sPage == 'week-view') { ?>class="selected"<?php } ?> href="<?php echo SchoorbsTPL::getWeekViewUrl(); ?>">
        <?php echo get_vocab('viewweek'); ?>
      </a>
    </li>
    <li>
      <a <?php if (self::$sPage == 'day-view') { ?>class="selected"<?php } ?> href="<?php echo SchoorbsTPL::getDayViewUrl(); ?>">
        <?php echo get_vocab('viewday'); ?>
      </a>
    </li>
  </ul>
</div>
