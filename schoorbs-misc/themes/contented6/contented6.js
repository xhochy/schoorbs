// Handle the special case february
function daysInFebruary (year) {
    // February has 28 days unless the year is divisible by four,
    // and if it is the turn of the century then the century year
    // must also be divisible by 400 when it has 29 days
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

// Calculate how many days the specified month in the specified year has
function DaysInMonth(WhichMonth, WhichYear) {
    var DaysInMonth = 31;
    if (WhichMonth == "4" || WhichMonth == "6" || WhichMonth == "9" || WhichMonth == "11")
        DaysInMonth = 30;
    if (WhichMonth == "2")
        DaysInMonth = daysInFebruary( WhichYear );
    return DaysInMonth;
}

$(document).ready(function() {
	/** Assign all DateSelector onChange-handlers **/
	$('select.schoorbstpl-dateselector-month, select.schoorbstpl-dateselector-year').each(function() {
		$(this).change(function() {
			// Get the prefix of this element group
			var groupPrefix = $(this).attr('id').replace(/(year)|(month)$/, '');
			
			// Get 3 elements of this group
			var dayObject = $('#'+ groupPrefix + 'day');
			var monthObject = $('#'+ groupPrefix + 'month');
			var yearObject = $('#'+ groupPrefix + 'year');
	
			var DaySelIdx = parseInt(dayObject.val());
			var Month = parseInt(monthObject.val());
			var Year = parseInt(yearObject.val());
			
			var DaysForThisSelection = DaysInMonth(Month, Year);
			var CurrentDaysInSelection = dayObject.children('option').length;
	
			// We have to many days, so remove some at the end
			if (CurrentDaysInSelection > DaysForThisSelection) {
				for (i = 0; i < (CurrentDaysInSelection - DaysForThisSelection); i++) {
					var children = dayObject.children('option');
					var lastIndex = dayObject.children('option').length - 1;
					var lastChild = children.get(lastIndex);
					// Since we cannot remove elements in all browser cleany, 
					// we will just hide it and assume, that a user will not
					// change the date that often that he memory will not last.
					// There might have to be about >10 000 changes to fill the 
					// memory, so we can ignore it at the moment.
					$(lastChild).remove();
				}
			}

			// We do not have enough days, so add some at the end
			if (DaysForThisSelection > CurrentDaysInSelection) {
        			for (i = 0; i < DaysForThisSelection; i++) {
					$('<option value="' + (i + 1) + '">' + (i + 1) + '</option>').appendTo(dayObject);
				}
			}
    
			if (DaySelIdx >= DaysForThisSelection) {
				// If a day was selected that now no longer
				// exists, selected the last day.
				dayObject.val(DaysForThisSelection);
    			} else {
    				// Restore the selected day
			        dayObject.val(DaySelIdx);
			}	
		});
		$(this).change();
	});
});
