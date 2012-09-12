<!-- <p><a href="<?php echo $top_uri ?>/add" class="button addButton"><span>Add new</span></a></p> -->

<input id="startDate" name="start_date" type="hidden" value="<?php echo $startDate ?>"/>
<input id="endDate" name="end_date" type="hidden" value="<?php echo $endDate ?>"/>

<?php if ($message): ?>
<p><?php echo $message ?></p>
<?php endif ?>

<table id="TimelogList" class="table list">
	<thead>
	<tr class="headings">
		<th class="date">Date</th>
		<th class="start-time time">Start</th>
		<th class="end-time time">End</th>
		<th class="hours">Hours</th>
		<th class="project">Project</th>
		<th class="category">Category</th>
		<th class="notes">Notes</th>
	</tr>
	</thead>
	<tbody>
	<?php if (isset($first_expaned_row_html)): ?>
	<?php echo $first_expaned_row_html ?>
	<?php endif ?>
	
	<?php if (count($timelogs)): ?>
		<?php foreach ($timelogs AS $timelog): ?>
		<tr id="<?php echo $timelog->getDate() ?>" class="day-total collapsed">
			<td class="date"><?php echo $timelog->getDate() ?></td>
			<td class="start-time time"><?php echo $timelog->getStartTimeNice() ?></td>
			<td class="end-time time"><?php echo $timelog->getEndTimeNice() ?></td>
			<td class="hours align-right"><?php echo $timelog->getHours() ?></td>
			<td class="project"></td>
			<td class="category"></td>
			<td class="notes"></td>
		</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<?php else: ?>
			</tbody>
		</table>
		<p>No timelogs found for date range.</p>
	<?php endif ?>
	<script type="text/javascript" src="javascript/timelog_list.js"></script>