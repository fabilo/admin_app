$(document).ready(function(){ 
	// setup datepicker 
	$(".dateInput").datepicker({
		dateFormat: 'yy-mm-dd',
	
	});
	
	// setup submit buttons to update a hidden input since submit buttons aren't included in jQuery serialize
	$('#TimelogAjaxForm').prepend('<input id="SubmitHiddenInput" type="hidden" name="submit" value="" />');
	$('#TimelogAjaxForm input[type=submit]').each(function(){
		$(this).click(function(){
			$('#TimelogAjaxForm #SubmitHiddenInput').attr('name',this.name);
			$('#TimelogAjaxForm #SubmitHiddenInput').attr('value',this.value);
		});
	});

	// current timelog form in right hand side
	$('#TimelogAjaxForm').submit(function(e){
		// handle form submission
		// prevent the form posting the form
		e.preventDefault();
	
		// display loading icon
		$(this).append('<p class="loading">Loading.<blink>.</blink></p>');
	
		// capture post string before we disable the inputs
		var postString = $(this).serialize();
	
		// disable form inputs
		$('#TimelogAjaxForm input, #TimelogAjaxForm textarea, #TimelogAjaxForm select').attr('disabled','true');
	
		// ajax submit form
		$.ajax({
				type:'POST', 
				url: $(this).attr('action'), 
				data: postString, 
				success: function(data) {
		    	// update form in sidebar
					$('#RightCol').html(data);
					
					// check if the timelog list is available
					if ($('#TimelogList').length) {
						// now reload item in list
						refreshTimelog($('#DateInput').val());
					}
				}
		});
	});

	// setup the form to save on change 
	$('#TimelogAjaxForm input, #TimelogAjaxForm select, #TimelogAjaxForm textarea').change(function(){		
		// only post form if it's not a new timelog form
		if ($('#TimelogIdInput').length) {
			// form is valid, submit
			$('#TimelogAjaxForm').submit();
		}
	});
});