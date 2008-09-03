var page = '';
var documentRoot = '';
var area = null;
var room = null;

function calendarPickerHandleSelect(type,args,obj) {
	var dates = args[0]; 
	var date = dates[0];
	var year = date[0], month = date[1], day = date[2];
	var params = ['month=' + month.toString(), 'day=' + day.toString(), 'year=' + year.toString()];
	if (area != null) {
		params.push('area=' + area);
	}
	if (room != null) {
		params.push('room=' + room);
	}
	location.href = documentRoot + page + '.php?' + params.join('&');
}

$(document).ready(function() {
	// Display the calendarPicker in the sidebar
	var calendarPicker = new YAHOO.widget.Calendar("calendarPicker", "calendarPicker", { navigator: true, pagedate:"5/2007", selected:"5/13/2007-5/19/2007"});
	calendarPicker.cfg.setProperty("MONTHS_LONG", sidebarMonthsLong);
	calendarPicker.cfg.setProperty("LOCALE_MONTHS", "long");
	calendarPicker.cfg.setProperty("WEEKDAYS_SHORT", sidebarDaysShort);
	calendarPicker.cfg.setProperty("LOCALE_WEEKDAYS", "short");
	calendarPicker.selectEvent.subscribe(calendarPickerHandleSelect, calendarPicker, true);
	calendarPicker.render();
	
	regexp = /([^/]+)\.php\?[^?]*$/i
	documentRoot = location.href.replace(regexp, '');
	page = regexp.exec(location.href)[1];
	regexp = /([&?]room=[\d]+)/i
	room = regexp.exec(location.href);
	if (room !== null) {
		room = room[1];
	}
	regexp = /([&?]area=[\d]+)/i
	area = regexp.exec(location.href);
	if (area !== null) {
		area = area[1];
	}
});

