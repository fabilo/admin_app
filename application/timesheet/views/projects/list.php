<table class="list">
	<thead>
		<tr class="headings">
			<td>Project Name</td>
			<td>Department</td>
			<td>Team</td>
			<td>Archived</td>
			<td>Description</td>
			<td class="edit"></td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($projects AS $project): ?>
		<tr id="project-<?php echo $project->getId() ?>" class="clickable">
			<td><?php echo $project->getName() ?></td>
			<td><?php echo $project->getDepartmentName() ?></td>
			<td><?php echo $project->getTeamName() ?></td>
			<td><?php echo ($project->getArchived()) ? 'Archived' : '' ?></td>
			<td><?php echo $project->getDescription() ?></td>
			<td class="edit">
				<a href="<?php echo $top_uri ?>/edit/<?php echo $project->getId() ?>">Edit</a>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>
<script>
$(document).ready(function(){	
	// add click on edit binding to table row	
	$('.list tbody tr').each(function(){
		// add click binding
		$(this).bind('click', function(){
			document.location = '<?php echo $top_uri ?>/edit/'+$(this).attr('id').substring(8);
		});
	});
	
	// remove edit tds
	$('.list .edit').remove();
});
</script>