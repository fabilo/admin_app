<p><a href="<?php echo $top_uri ?>/add">Add new</a></p>

<div class="table">
	<div class="headings table-row">
		<span class="name table-cell"></span>
		<span class="name table-cell">Project Name</span>
		<span class="name table-cell">Department</span>
		<span class="name table-cell">Team</span>
	</div>

	<?php foreach ($projects AS $project): ?>

	<div class="table-row collapsed">
		<span class="name table-cell">
			<a href="<?php echo $top_uri ?>/edit/<?php echo $project->getId() ?>">Edit</a>
		</span>
		<span class="name table-cell"><?php echo $project->getName() ?></span>
		<span class="name table-cell"><?php echo $project->getDepartmentName() ?></span>
		<span class="name table-cell"><?php echo $project->getTeamName() ?></span>
	</div>

	<?php endforeach ?>
</div>