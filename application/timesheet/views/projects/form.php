
<?php if (isset($error)): ?>
<p class="error"><?php echo $error ?></p>
<?php endif ?>

<form method="post" action="<?php echo $top_uri ?>/save" style="width: 300px">
	
	<input type='hidden' name='project[id]' value='<?php echo $project->getId() ?>'/>
	
	<div class="inlineLabel margin">
		<label for="NameInput">Name:</label>
		<input id="NameInput" type="text" name="project[name]" maxlength="64" placeholder="Project Name" pattern="^(\w|\s){3}(\w|\s)*$" value="<?php echo $project->getName() ?>"/>			
	</div>
	
		<select name="project[department_id]" class="margin block">
			<option value="">- Select Department -</option>
			<?php foreach ($departments AS $department): ?>
			<option value="<?php echo $department->getId() ?>" <?php echo ($department->getId() == $project->getDepartmentId()) ? 'SELECTED' : '' ?>><?php echo $department->getName() ?></option>
			<?php endforeach ?>
		</select>
		
		<select name="project[team_id]" class="margin block">
			<option value="">All Teams</option>
			<?php foreach ($teams AS $team): ?>
			<option value="<?php echo $team->getId() ?>" <?php echo ($team->getId() == $project->getTeamId()) ? 'SELECTED' : '' ?>><?php echo $team->getName() ?></option>
			<?php endforeach ?>
		</select>
		
		<div class="margin">
			<input id="ArchivedCheckbox" type="checkbox" name="project[archived]" value="1" <?php echo ($project->archived) ? 'CHECKED' : '' ?>/>
			<label for="ArchivedCheckbox">Archived?</label>
		</div>
	
		<textarea name="project[description]" placeholder="Description" class="margin block"><?php echo $project->getDescription() ?></textarea>

	<div>	
			<input type="Submit" name="save" value="Save"> 
			<input type="Submit" name="save_and_done" value="Save & Done"> 
			<button id="Cancel" onclick="document.location='<?php echo $top_uri ?>'; return false;">Cancel</button>
	</div>

</form>