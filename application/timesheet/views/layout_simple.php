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
	
	<?php if (isset($javascript_includes)) foreach ($javascript_includes as $js_filename): ?>
		<script src="javascript/<?php echo $js_filename ?>.js"></script>
	<?php endforeach ?>
</head>
<body class="simpleLayout <?php echo implode(' ', $uri_segments) ?> <?php echo implode('-', $uri_segments) ?> <?php if (isset($demo)) echo 'demo' ?>">
<div id="Layout">
	<?php if (isset($heading)): ?><h2 id="Heading"><?php echo $heading ?></h2><?php endif ?>
	<div id="Content">

		<?php if (isset($message)): ?><p class="message"><?php echo $message ?></p><?php endif ?>
		<?php echo $body ?>
	</div>
</div>
</body>
</html>
