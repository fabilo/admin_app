function refreshTimelog(date) {
	// ajax and get the day & timelogs html 
	$.ajax({
	  url: 'timesheet.php/timelogs/day/'+date,
	}).success(function(data){
		// check if this timelog's day already exists? 
		if ($('#'+date).length) {
			// remove old timelogs for this day
			$('.timelog-'+date).remove();
			// replace timelog element with new html
			$('#'+date).replaceWith(data);
		}
		else {
			// check that this timelog fits in list's current date range 
			if (date >= $('#startDate').val() && date <= $('#endDate').val()) {
				// timelog is in list's date range, reload page
				document.location.reload(true);
			}
		}
		// reapply click event handler
		$('#'+date).click(timelogListClick);
		// apply the show timelog notes click event
		$('.timelog-'+date+' .truncated').click(timelogNotesClick);
		// apply show timelog in sidebar click event
		$('.timelog-'+date+' .showInSidebar').click(showTimelogInSidebar);
		// 
		// $('.timelog-'+date+' .editTimelog').click(editTimelog);
	});
}

function timelogListClick() {
	if ($(this).hasClass('collapsed')) {
		refreshTimelog(this.id);
	}
	else {
		// remove timelogs after this daily total 
		$('.timelog-'+this.id).remove();
		$(this).removeClass('expanded').addClass('collapsed');
	}
}

function timelogNotesClick() {
	var id = $(this).attr('id').substring(6);
	$.ajax({
	  url: 'timesheet.php/timelogs/getNotes/'+id,
	}).success(function(data){
		// output notes to browser
		modal(data, 'Notes');
	});
}

function editTimelog(e) {
	e.preventDefault();
	$.ajax({
	  url: $(this).attr('href'),
	}).success(function(data){
		// output notes to browser
		modal(data, 'Edit Timelog');
	});
}

function modal(message, title) {
	$(document.body).prepend('<div id="dialog-message">'+message+'</div>');
	
	// pop modal
	$( "#dialog-message" ).dialog({
			modal: true,
			title: title,
			width: 500, 
			close: function(event, ui) { 
				$("#dialog-message").remove();
			}
		});	
}

/**
 *	Click event callback for displaying a timelog in the sidebar via Ajax
 */
function showTimelogInSidebar(e) {
	// cancel anchor click
	e.preventDefault();
	// get form html 
	$.ajax({
	  url: $(this).attr('href'),
	}).success(function(data){
		$('#RightCol').html(data);
	});
}

$(document).ready(function() {
	// day totals click
	$('.day-total').click(timelogListClick);
	// expand notes click
	$('.timelog .notes .truncated').click(timelogNotesClick);
	// display timelog in sidebar
	$('.timelog .showInSidebar').click(showTimelogInSidebar);
	// edit timelog modal 
	// $('.editTimelog').click(editTimelog);
});