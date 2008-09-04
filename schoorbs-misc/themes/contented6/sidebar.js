var page = '';
var documentRoot = '';
var area = null;
var room = null;
var year = null, month = null, day = null;

function calendarPickerHandleSelect(type,args,obj) {
	var dates = args[0]; 
	var date = dates[0];
	var cPyear = date[0], cPmonth = date[1], cPday = date[2];
	var params = ['month=' + cPmonth.toString(), 'day=' + cPday.toString(), 'year=' + cPyear.toString()];
	if (area != null) {
		params.push('area=' + area);
	}
	if (room != null) {
		params.push('room=' + room);
	}
	location.href = documentRoot + page + '.php?' + params.join('&');
}

function parseThingsOutOfUrl() {
	var regexp = /([^/]+)\.php\?[^?]*$/i
	documentRoot = location.href.replace(regexp, '');
	page = regexp.exec(location.href)[1];
	regexp = /[&?]room=([\d]+)/i
	room = regexp.exec(location.href);
	if (room !== null) {
		room = room[1];
	}
	regexp = /[&?]area=([\d]+)/i
	area = regexp.exec(location.href);
	if (area !== null) {
		area = area[1];
	}
	regexp = /[&?]year=([\d]+)/i
	year = regexp.exec(location.href);
	if (year !== null) {
		year = year[1];
	} else {
		year = (new Date()).getFullYear();
	}
	regexp = /[&?]month=([\d]+)/i
	month = regexp.exec(location.href);
	if (month !== null) {
		month = month[1];
	} else {
		month = (new Date()).getMonth();
	}
	regexp = /[&?]day=([\d]+)/i
	day = regexp.exec(location.href);
	if (day !== null) {
		day = day[1];
	} else {
		day = (new Date()).getDate();
	}
}

$(document).ready(function() {
	parseThingsOutOfUrl();
	var cPselected
	if (page == 'week-view') {
		// Get sunday before
		sunday = new Date();
		sunday.setDate(day);
		sunday.setMonth(month - 1);
		sunday.setYear(year);
		sunday.setDate(sunday.getDate() - sunday.getDay());
		// Get saturday after
		saturday = new Date();
		saturday.setDate(day);
		saturday.setMonth(month - 1);
		saturday.setYear(year);
		saturday.setDate(saturday.getDate() + (6 - saturday.getDay()));
		cPselected = (sunday.getMonth() + 1) + '/' + sunday.getDate() + '/'
			+ sunday.getFullYear() + '-' + (saturday.getMonth() + 1) + '/'
			+ saturday.getDate() + '/' + saturday.getFullYear();
	} else {
		cPselected = month.toString() + '/' + day.toString() + '/' + year.toString();
	}
	// Display the calendarPicker in the sidebar
	var calendarPickerOptions = { 
		navigator: true, 
		pagedate: month.toString() + '/' + year.toString(), 
		selected: cPselected
	};
	var calendarPicker = new YAHOO.widget.Calendar("calendarPicker", "calendarPicker", calendarPickerOptions);
	calendarPicker.cfg.setProperty("MONTHS_LONG", sidebarMonthsLong);
	calendarPicker.cfg.setProperty("LOCALE_MONTHS", "long");
	calendarPicker.cfg.setProperty("WEEKDAYS_SHORT", sidebarDaysShort);
	calendarPicker.cfg.setProperty("LOCALE_WEEKDAYS", "short");
	calendarPicker.selectEvent.subscribe(calendarPickerHandleSelect, calendarPicker, true);
	calendarPicker.render();
	
});

