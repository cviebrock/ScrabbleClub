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
<?php if ($appid = Config::get('facebook.appId')): ?>
	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?php echo $appid; ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php endif; ?>
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
