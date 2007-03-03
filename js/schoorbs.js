/**
 * Functions to make a nicer Schoorbs GUI
 * 
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
Schoorbs = Class.create();
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
	
	Element.addClassName(cell,'highlight');
}

/**
 * Unhighlight the given cell
 * @param {Object} cell
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
function UnHighlightCell(cell)
{
	Element.removeClassName(cell,'highlight')
	cell.isActive = false;
}