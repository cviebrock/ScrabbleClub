<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo Config::get('scrabble.clubname') . TITLE_DELIM . $title; ?></title>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-G4QVFH8DW9"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-G4QVFH8DW9');
</script>
<!-- MailerLite Universal -->
<script>
    (function(w,d,e,u,f,l,n){w[f]=w[f]||function(){(w[f].q=w[f].q||[])
    .push(arguments);},l=d.createElement(e),l.async=1,l.src=u,
    n=d.getElementsByTagName(e)[0],n.parentNode.insertBefore(l,n);})
    (window,document,'script','https://assets.mailerlite.com/js/universal.js','ml');
    ml('account', '487047');
</script>
<!-- End MailerLite Universal -->
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
