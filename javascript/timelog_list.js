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
		
		// now update week table row for date (if applicable)
		rel = $('#'+date).attr('rel');
		// check if that week row exists 
		if ($('#'+rel).length) {
			// now update the hours column of the week row
			$.ajax({
			  url: 'timesheet.php/timelogs/weekHours/'+rel.substring(4),
			}).success(function(data){
				// update hours column
				$('#'+rel+' .hours').html(data);
			});
		}
	});
}

function timelogListClick() {
	if ($(this).hasClass('collapsed')) {
		refreshTimelog(this.id);
		$(this).removeClass('collapsed').addClass('expanded');
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
		initSidebarForm();
	});
	
	// if "Start New" button pressed, refresh today's timelog row in table
	if ($(this).attr('id') == 'StartNewButton') refreshTimelog($('#TimelogSidebarForm #DateInput').val());
}

function refreshWeek(year, week) {
	console.log('yip');
}

$(document).ready(function() {
	// day totals click
	$('.day-total').click(timelogListClick);
	// expand notes click
	$('.timelog .notes .truncated').click(timelogNotesClick);
	// display timelog in sidebar
	$('.showInSidebar').click(showTimelogInSidebar);
	// edit timelog modal 
	// $('.editTimelog').click(editTimelog);
	// bw icon rollover toggle 
	$('img.bw-toggle').hover(function(){
		// on hover in - remove -bw from image src
		colouredIconSrc = $(this).attr('src').replace("-bw", "");
		$(this).attr('src', colouredIconSrc);
	}, 
	function(){
		// on hover out - add -bw back into image src
		src = $(this).attr('src');
		bwIconSrc = src.substring(0, (src.length-4))
			+ '-bw'
			+ src.substring((src.length-4));
		$(this).attr('src', bwIconSrc);
	});
});