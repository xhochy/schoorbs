/**
 * Adminstration GUI helpers
 *
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */

// Initiate the administration GUI when the page has loaded successfully
$(document).ready(function() {
	// Convert the areas into an accordion which resizes itself so, that the
	// rooms for an area fit perfectly in the space under the heading of the
	// current section and the next section. The souldn't be any blank space
	// which overlaps the headings of the next sections.
	$("#schoorbs-administration-accordion").accordion({
		header: '.ui-accordion-link',
		autoHeight: false
	});
	
	// Register a click event, so that if the user clicks on the "Add Area"
	// heading the form will be shown/hidden, so that, if needed, more space
	// is provided.
	$("#schoorbs-administration-add-area a.head").click(function() {
		$("#schoorbs-administration-add-area .form").slideToggle();
	});
});
