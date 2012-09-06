<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Timesheet</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Fabian Snaith">
	<base href="<?php echo $base_uri ?>"/>
	<!-- Date: 2012-08-16 -->
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/layout.css"/>
	<link rel="stylesheet" type="text/css" href="css/typography.css"/>
	
	<link rel="stylesheet" type="text/css" href="javascript/jquery-ui-1.8.23.custom/css/smoothness/jquery-ui-1.8.23.custom.css"/>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
	<script src="javascript/jquery-ui-1.8.23.custom/js/jquery-ui-1.8.23.custom.min.js"></script>
</head>
<body>
<div id="Header">
	<h1>Timesheet</h1>
	<ul id="MainNavigation" class="clearfix">
		<li<?php echo (basename($top_uri) == 'timelogs') ? ' class="current"' : '' ?>><a href="<?php echo dirname($top_uri) ?>/timelogs/">Timelogs</a></li>
		<li<?php echo (basename($top_uri) == 'projects') ? ' class="current"' : '' ?>><a href="<?php echo dirname($top_uri) ?>/projects/">Projects</a></li>
		<li<?php echo (basename($top_uri) == 'categories') ? ' class="current"' : '' ?>><a href="<?php echo dirname($top_uri) ?>/categories/">Categories</a></li>
	</ul>
</div>
<div id="Layout">
	<div class="table">
		<div class="table-row">
			<div id="LeftCol" class="table-cell relative">
					<?php echo $body ?>
			</div>
			<div id="RightCol" class="table-cell relative">
				<?php echo $current_timelog_form ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>
