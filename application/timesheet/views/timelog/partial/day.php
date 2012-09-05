	<tr id="<?php echo $totals->getDate() ?>" class="day-total table-row expanded">
		<td class="date"><?php echo $totals->getDate() ?></td>
		<td class="start-time time"><?php echo $totals->getStartTimeNice() ?></td>
		<td class="end-time time"><?php echo $totals->getEndTimeNice() ?></td>
		<td class="hours align-right"><?php echo $totals->getHours() ?></td>
		<td class="project"></td>
		<td class="category"></td>
		<td class="notes overflow-hidden"></td>
	</tr>

	<?php $i=0; ?>
	<?php foreach ($timelogs AS $t): ?>
	<tr class="timelog table-row timelog-<?php echo $t->getDate() ?> <?php echo ($i++ == 0) ? 'first-child' : '' ?> <?php echo ($i == count($timelogs)) ? 'last-child': '' ?>">
		<td class="date align-right"><a class="hidden" href="<?php echo $timelog_edit_url.'/'.$t->getId() ?>"><img alt="edit" src="<?php echo $base_uri ?>/images/icons/pencil.png"/></a></td>
		<td class="start-time time"><?php echo $t->getStartTimeNice() ?></td>
		<td class="end-time time"><?php echo $t->getEndTimeNice() ?></td>
		<td class="hours align-right"><?php echo $t->getHours() ?></td>
		<td class="project"><?php echo $t->getProjectName() ?></td>
		<td class="category"><?php echo $t->getCategoryName() ?></td>
		<td class="notes"><?php echo $t->getNotesExtract() ?></td>
	</tr>
	<?php endforeach ?>