$(document).ready(function() {
	// Display the calendarPicker in the sidebar
	var calendarPicker = new YAHOO.widget.Calendar("calendarPicker", "calendarPicker", { pagedate:"5/2007", selected:"5/13/2007-5/19/2007"});
	calendarPicker.cfg.setProperty("MONTHS_LONG", sidebarMonthsLong);
	calendarPicker.cfg.setProperty("LOCALE_MONTHS", "long");
	calendarPicker.cfg.setProperty("WEEKDAYS_SHORT", sidebarDaysShort);
	calendarPicker.cfg.setProperty("LOCALE_WEEKDAYS", "short");
	calendarPicker.render();
});

