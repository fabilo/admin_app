function refreshTimelog(date) {
	// ajax and get the day & timelogs html 
	$.ajax({
	  url: '/admin/timesheet.php/timelogs/day/'+date,
	}).success(function(e){
		// check if this timelog's day already exists? 
		if ($('#'+date).length) {
			// remove old timelogs for this day
			$('.timelog-'+date).remove();
			// replace timelog element with new html
			$('#'+date).replaceWith(e);
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
	  url: '/admin/timesheet.php/timelogs/getNotes/'+id,
	}).success(function(e){
		alert(e);
	});
}

$(document).ready(function() {
	// day totals click
	$('.day-total').click(timelogListClick);
	// expand notes click
	$('.timelog .notes .truncated').click(timelogNotesClick);
});