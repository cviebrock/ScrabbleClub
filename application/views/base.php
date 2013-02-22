<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo Config::get('scrabble.clubname') . TITLE_DELIM . $title; ?></title>
	<?php echo Asset::container('head')->styles(); ?>
	<?php echo Asset::styles(); ?>
	<?php echo Asset::container('head')->scripts(); ?>
</head>
<body>
	<?php echo isset($fb) ? $fb : ''; ?>
	<?php echo $header; ?>
	<div class="container">
	<?php echo $flashes; ?>
	<?php echo $content; ?>
	<div class="push"></div>
	</div>
	<?php echo $footer; ?>
	<?php echo Asset::scripts(); ?>
</body>
</html>
