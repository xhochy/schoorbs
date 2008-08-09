$(document).ready(function() {
	$("#schoorbs-administration-accordion").accordion({
		header: '.ui-accordion-link',
		autoHeight: false
	});
	$("#schoorbs-administration-add-area a.head").click(function() {
		$("#schoorbs-administration-add-area .form").slideToggle();
	});
});
