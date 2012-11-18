
function initSidebarForm() {
	// setup submit buttons to update a hidden input since submit buttons aren't included in jQuery serialize
	$('#TimelogSidebarForm').prepend('<input id="SubmitHiddenInput" type="hidden" name="submit" value="" />');
	$('#TimelogSidebarForm input[type=submit]').each(function(){
		$(this).click(function(){
			$('#TimelogSidebarForm #SubmitHiddenInput').attr('name',this.name);
			$('#TimelogSidebarForm #SubmitHiddenInput').attr('value',this.value);
		});
	});

	// current timelog form in right hand side
	$('#TimelogSidebarForm').submit(function(e){
		// handle form submission
		// prevent the form posting the form
		e.preventDefault();

		// display saving feedback to user (if not displaying already).
		if ($('#RightCol .saving').length == 0) $($('#RightCol .block-container')).append('<p class="saving align-center">Saving.<blink>.</blink></p>');
		else $('#RightCol .saving').replaceWith('<p class="saving align-center">Saving.<blink>.</blink></p>');

		// capture post string before we disable the inputs
		var postString = $(this).serialize();

		// ajax submit form
		$.ajax({
			type:'POST', 
			url: $(this).attr('action'), 
			data: postString, 
			success: function(data) {
				// request complete - remove saving message
				$('#RightCol .saving').fadeOut(1500);
				
				if (data != 'success') {
					// returning html - update form in sidebar
					$('#TimelogSidebarForm').replaceWith(data);
					// reimplement form submit events
					initSidebarForm();
				}

				// check if the timelog list is available
				if ($('#TimelogList').length) {
					// now reload item in list
					refreshTimelog($('#DateInput').val());
				}
			}
		});
	});

	// setup the form to save on change 
	$('#TimelogSidebarForm input, #TimelogSidebarForm select, #TimelogSidebarForm textarea').change(function(){		
		// only post form if it's not a new timelog form
		if ($('#TimelogIdInput').length) {
			// submit button hasn't been pressed - so remove the hidden input place holder values			
			$('#TimelogSidebarForm #SubmitHiddenInput').attr('name','');
			$('#TimelogSidebarForm #SubmitHiddenInput').attr('value','');
			// form is valid, submit
			$('#TimelogSidebarForm').submit();
		}
	});
}

$(document).ready(function(){ 
	// setup datepicker 
	$(".dateInput").datepicker({
		dateFormat: 'yy-mm-dd',
	});
	// init sidebar form 
	initSidebarForm();
});