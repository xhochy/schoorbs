<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php echo SchoorbsConfig::getOption('company'); ?> - Schoorbs</title>
  <?php SchoorbsTPL::includeCSS('contented6.css'); ?>
  <?php SchoorbsTPL::includeCSS('style.css'); ?>
  <?php SchoorbsTPL::includeCSS('yui-2.5.2/build/calendar/assets/skins/sam/calendar.css'); ?>
  <?php SchoorbsTPL::includeJS('jquery-1.2.6.pack.js'); ?>
  <?php SchoorbsTPL::includeJS('jquery-ui-personalized-1.5.2.packed.js'); ?>
</head>
<body class="yui-skin-sam">
  <?php SchoorbsTPL::render('header'); ?>
  <div id="content">
    <div id="maincontent">
      <?php SchoorbsTPL::renderBody(); ?>
    </div> <!-- end maincontent -->
    <?php if ((self::$sPage == 'week-view') || (self::$sPage == 'day-view') || (self::$sPage == 'month-view')) { ?>
      <div id="sidecontent">
        <?php SchoorbsTPL::render('sidebar'); ?>
      </div> <!-- end sidecontent -->
    <?php } ?>
  </div> <!-- end content -->
  <?php SchoorbsTPL::render('footer'); ?>
</body>
</html>
