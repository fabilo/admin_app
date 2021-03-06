<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>
		Timesheet
		<?php if (isset($meta_title)): ?>- <?php echo $meta_title ?><?php endif ?>
	</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Fabian Snaith">
	<base href="<?php echo $base_uri ?>"/>
	<!-- Date: 2012-08-16 -->
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/layout.css"/>
	<link rel="stylesheet" type="text/css" href="css/typography.css"/>
	
	<link rel="stylesheet" type="text/css" href="javascript/jquery-ui-1.8.23.custom/css/smoothness/jquery-ui-1.8.23.custom.css"/>
	<script type="text/javascript" src="javascript/jquery-ui-1.8.23.custom/js/jquery-1.7.2.min.js"></script>
	<script src="javascript/jquery-ui-1.8.23.custom/js/jquery-ui-1.8.23.custom.min.js"></script>

	<!-- Superfish dropdown includes -->
	<script src="javascript/superfish-1.4.8/js/superfish.js"></script>	
	<link rel="stylesheet" media="screen" href="javascript/superfish-1.4.8/css/superfish.css" /> 
	<script>
		$(document).ready(function(){ 
	        $("#MainNavigation").superfish({'dropShadows': false }); 
	    }); 
	</script>

	<?php if (isset($javascript_includes)) foreach ($javascript_includes as $js_filename): ?>
		<script src="javascript/<?php echo $js_filename ?>.js"></script>
	<?php endforeach ?>
</head>
<body class="layout <?php echo implode(' ', $uri_segments) ?> <?php echo implode('-', $uri_segments) ?>">
<div id="Header">
	<h1>Timesheet</h1>
	<ul id="MainNavigation" class="clearfix">
		<li<?php echo (basename($top_uri) == 'timelogs') ? ' class="current"' : '' ?>><a href="<?php echo site_url('timelogs') ?>">Timelogs</a></li>
		<li<?php echo (basename($top_uri) == 'projects') ? ' class="current"' : '' ?>><a href="<?php echo site_url('projects') ?>">Projects</a></li>
		<li<?php echo (basename($top_uri) == 'categories') ? ' class="current"' : '' ?>><a href="<?php echo site_url('categories') ?>">Categories</a></li>
		<li<?php echo (basename($top_uri) == 'reports') ? ' class="current"' : '' ?>>
			<a href="<?php echo site_url('reports') ?>">Reports</a>
			<ul>
				<li><a href="<?php echo site_url('reports/category') ?>">Category Report</a></li>
				<li><a href="<?php echo site_url('reports/clarity') ?>">Clarity Report</a></li>
			</ul>
		</li>
		<li><a href="<?php echo site_url('users/logout') ?>">Logout</a></li>
	</ul>
</div>
<div id="Layout">
	<div class="table">
		<div class="table-row">
			<div id="LeftCol" class="table-cell relative">
				<?php if (isset($heading)): ?><h2 id="Heading"><?php echo $heading ?></h2><?php endif ?>
				<div id="Content">

					<?php if (isset($message)): ?><p class="message"><?php echo $message ?></p><?php endif ?>
					<?php echo $body ?>
				</div>
			</div>
			<div id="RightCol" class="table-cell relative">
				<div class="block-container">
					<?php echo $current_timelog_form ?>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
