<!doctype html>
<html>
<head>
	<title>WSC - <?php echo $title; ?></title>
	<?php echo Asset::container('head')->styles(); ?>
	<?php echo Asset::styles(); ?>
	<?php echo Asset::container('head')->scripts(); ?>
</head>
<body>
	<div id="container">
		<div id="header">
		<?php echo $header; ?>
		</div>
		<?php echo $flashes; ?>
		<div id="content">
		<?php echo $content; ?>
		</div>
		<div id="footer">
		<?php echo $footer; ?>
		</div>
	</div>
	<?php echo Asset::scripts(); ?>
	<?php // bl_debug(); ?>
</body>
</html>