/**
 * Handle all things loacted in the sidebar
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */

/** 
 * The filename of the loaded (X)HTML document
 * 
 * @type String
 */
var page = '';

/**
 * This is equal to the "directory name" of the file, just in HTTP
 *
 * @type String
 */
var documentRoot = '';

/**
 * The area in which we are at the moment
 *
 * @type String
 * @todo In future this should be an Int
 */
var area = null;

/**
 * The room in which we are at the moment
 *
 * @type String
 * @todo In future this should be an Int
 */
var room = null;

/**
 * The year in which we are displaying
 *
 * @type String
 * @todo In future this should be an Int
 */
var year = null;

/**
 * The month in which we are displaying
 *
 * @type String
 * @todo In future this should be an Int
 */
var month = null;

/**
 * The day in which we are displaying
 *
 * @type String
 * @todo In future this should be an Int
 */
var day = null;

/**
 * Handle the selection of a date in the CalendarPicker widget
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function calendarPickerHandleSelect(type,args,obj) {
	// Just get the selected date out of the arguments for this event
	var date = args[0][0];
	// Make up all URL-parameters for the selected date
	var params = ['month=' + date[1].toString(), 'day=' + date[2].toString(), 'year=' + date[0].toString()];
	// If an area was already set, so add it to the new URL
	if (area != null) {
		params.push('area=' + area);
	}
	// If an room was already set, so add it to the new URL
	if (room != null) {
		params.push('room=' + room);
	}
	// Build up the new URL and load the page
	location.href = documentRoot + page + '.php?' + params.join('&');
}

/**
 * Parse all needed parameters out of the url.
 *
 * The following parameters are parsed:
 *   -> The filename
 *   -> room
 *   -> area
 *   -> year
 *   -> day
 *   -> month
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function parseThingsOutOfUrl() {
	// Find the document root, we specify it as all characters in front of
	// a string which could contain everything expect a /. We assume that 
	// the filename ends on php and may have additional URL parameters.
	var regexp = /([^/]+)\.php(\?[^?]*)?$/i
	documentRoot = location.href.replace(regexp, '');
	// Get the page name, this is the filename *without* '.php'
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

