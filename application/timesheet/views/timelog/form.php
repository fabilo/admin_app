<?php if (isset($error)): ?>
<p class="error"><?php echo $error ?></p>
<?php endif ?>

<form id="Timelog<?php echo $sidebar_form  ? 'Sidebar' : ($ajax ? 'Ajax' : '') ?>Form" class="timelogForm" method="post" action="<?php echo dirname($top_uri) ?>/timelogs/save">
	
	<?php if ($sidebar_form): ?>
		<?php if ($timelog->isNew()): ?>
		<input class="startStop" type="submit" name="submit" value="Start" />
		<?php else: ?>
		<input class="startStop" name="submit" type="Submit" value="Stop" class="clearfix"/>
		<?php endif ?>
	<?php endif ?>
	
	<?php if ($sidebar_form): ?>
	<input type="hidden" name="sidebar_form" value="1"/>
	<?php endif ?>
	<?php if (!$timelog->isNew()): ?>
	<input id="TimelogIdInput" type='hidden' name='timelog[id]' value='<?php echo $timelog->getId() ?>'/>
	<?php endif ?>
	
	<div class="inlineLabel margin">
		<label for="DateInput">Date:</label>
		<input id="DateInput" class="dateInput" type="text" name="timelog[date]" maxlength="10" placeholder="2012-08-21" pattern="^20[0-9]{2}-(0[1-9]|1[0-2])-(([0-2][0-9])|30|31)$" value="<?php echo $timelog->getDate() ?>"/>			
	</div>

	<div class="inlineLabel margin times clearfix">
		<div style="position: relative; float: left;">
			<label for="StartInput">Start:</label>
			<input id="StartInput" type="text" name="timelog[start_time]" maxlength="5" placeholder="9:30" patterm="^([0-2]?[0-9]):([0-5][1-9])$" value="<?php echo $timelog->getStartTimeNice() ?>"/>
		</div>
		<div class="" style="position: relative; float: right;">
			<label for="EndInput">End:</label>
			<input id="EndInput" type="text" name="timelog[end_time]" maxlength="5" placeholder="13:45" patterm="^([0-2]?[0-9]):([0-5][1-9])$" value="<?php echo $timelog->getEndTimeNice() ?>"/>
		</div>
	</div>
	
	<div style="clear: both"> </div>
	
		<select name="timelog[project_id]" class="margin block">
			<option value="">- Select Project -</option>
			<?php foreach ($projects AS $project): ?>
			<option value="<?php echo $project->getId() ?>" <?php echo ($project->getId() == $timelog->getProjectId()) ? 'SELECTED' : '' ?>><?php echo $project->getName() ?></option>
			<?php endforeach ?>
		</select>
	
		<select name="timelog[category_id]" class="margin block">
			<option value="">- Select Category -</option>
			<?php foreach ($categories AS $category): ?>
			<option value="<?php echo $category->getId() ?>" <?php echo ($category->getId() == $timelog->getCategoryId()) ? 'SELECTED' : '' ?>><?php echo $category->getName() ?></option>
			<?php endforeach ?>
		</select>
	
		<textarea name="timelog[notes]" placeholder="Notes/Comments" class="margin block"><?php echo $timelog->getNotes() ?></textarea>

	<div class="actionButtons">
			<input class="col2" name="submit" type="Submit" value="Cancel"/>
			<input class="col2" name="submit" type="Submit" value="Save"/>
			<?php if (!$ajax && !$sidebar_form): ?>
			<input class="block red" name="submit" onclick="return confirm('Are you sure you want to delete timelog?')" type="Submit" value="Delete"/>
			<?php endif ?>
	</div>
	
	<p class="loading hidden">Loading.<blink>.</blink></p>
</form>

<?php if ($sidebar_form): ?>
<script type="text/javascript" src="javascript/timelog_form.js"></script>
<?php endif ?>