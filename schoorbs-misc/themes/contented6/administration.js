$(document).ready(function() {
	$("#schoorbs-administration-accordion").accordion({
		header: '.ui-accordion-link',
		autoHeight: false
	});
	$("#schoorbs-administration-add-area .form").hide();
	$("#schoorbs-administration-add-area").click(function() {
		$("#schoorbs-administration-add-area .form").slideToggle();
		//$(this).slideToggle('slow');
	});
});
