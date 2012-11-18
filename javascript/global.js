$(document).ready(function(){
	// highlight parent row for form inputs
	$('#Content form .formRow input, #Content form .formRow select, #Content form .formRow button').focus(function(){
		$(this).parent('.formRow').addClass('focused');
	}).blur(function(){
		$(this).parent('.formRow').removeClass('focused');
	});
});