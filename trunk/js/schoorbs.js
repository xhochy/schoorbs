/**
 * Functions to make a nicer Schoorbs GUI
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
Schoorbs = {};
Schoorbs.showRightSide = false;

/**
 * Activates the Cell-Highlighting
 * 
 * @param {bool} showRightSide
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function InitCellManagement(showRightSide) 
{
	Schoorbs.showRightSide = showRightSide;
}

/**
 * Highlights the given cell
 * 
 * @param {Object} cell
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function HighlightCell(cell)
{
	if(cell.isActive) return;
	cell.isActive = true;
	
	$(cell).addClass('highlight');
}

/**
 * Unhighlight the given cell
 * @param {Object} cell
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function UnHighlightCell(cell)
{
	$(cell).removeClass('highlight');
	cell.isActive = false;
}

function daysInFebruary (year)
{
    // February has 28 days unless the year is divisible by four,
    // and if it is the turn of the century then the century year
    // must also be divisible by 400 when it has 29 days
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

//function for returning how many days there are in a month including leap years
function DaysInMonth(WhichMonth, WhichYear)
{
    var DaysInMonth = 31;
    if (WhichMonth == "4" || WhichMonth == "6" || WhichMonth == "9" || WhichMonth == "11")
        DaysInMonth = 30;
    if (WhichMonth == "2")
        DaysInMonth = daysInFebruary( WhichYear );
    return DaysInMonth;
}
 
//function to change the available days in a months
function ChangeOptionDays(prefix)
{
	var DaysObject = $('#'+ prefix + 'day');
	var MonthObject = $('#'+ prefix + 'month');
	var YearObject = $('#'+ prefix + 'year');
	
	var DaySelIdx = parseInt(DaysObject.val());
	var Month = parseInt(MonthObject.val());
	var Year = parseInt(YearObject.val());
	
	//alert("Day="+(DaySelIdx)+" Month="+Month+" Year="+Year);
	
    var DaysForThisSelection = DaysInMonth(Month, Year);
    var CurrentDaysInSelection = DaysObject.children('option').length;
	
	if (CurrentDaysInSelection > DaysForThisSelection) {
        for (i=0; i < (CurrentDaysInSelection - DaysForThisSelection); i++) {
			var children = DaysObject.children('option');
			var lastIndex = DaysObject.children('option').length - 1;
			var lastChild = children.get(lastIndex);
			$(lastChild).hide();
        }
    }
	
    if (DaysForThisSelection > CurrentDaysInSelection) {
        for (i=0; i<DaysForThisSelection; i++) {
			$('<option value="' + (i + 1) + '">' + (i + 1) + '</option>').appendTo(DaysObject);
        }
    }
    
    if (DaySelIdx >= DaysForThisSelection) {
        DaysObject.val(DaysForThisSelection - 1);
    } else {
        DaysObject.val(DaySelIdx);
	}	
}