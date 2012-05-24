<div class="page-header">
	<h1>Game Night Summary for <?php echo App::format_date($date); ?></h1>
</div>

<p>
	There were no games on <?php echo App::format_date($date); ?>
</p>

<?php echo App::action_link_to_route('home', 'Back to Homepage', array(), 'arrow-left'); ?>