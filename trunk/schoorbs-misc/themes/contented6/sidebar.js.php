<?php require_once dirname(__FILE__).'/../../../schoorbs-includes/lang.class.php'; ?>
$(document).ready(function() {
	// Display the calendarPicker in the sidebar
	var calendarPicker = new YAHOO.widget.Calendar("calendarPicker", "calendarPicker", { pagedate:"5/2007", selected:"5/13/2007-5/19/2007"});
	calendarPicker.cfg.setProperty("MONTHS_LONG", [
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
	]);
	calendarPicker.cfg.setProperty("LOCALE_MONTHS", "long");
	calendarPicker.render();
});

