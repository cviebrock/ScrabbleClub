<div class="page-header">
	<h1>Clear Ratings for <?php echo $date; ?></h1>
</div>

<p>
	Are you sure you want to clear <strong>all</strong> player ratings
<?php if ($latest_date===$date): ?>
	for <?php echo $date; ?>?
<?php else: ?>
	from <?php echo $date; ?> to <?php echo $latest_date; ?>?
<?php endif; ?>
</p>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, clear ratings', 1, false)
);

echo Form::actions(array(
	Form::submit('Clear Ratings', array('class' => 'btn-primary')),
	action_link_to_route('admin.games@index', 'Back to Games List', array(), 'arrow-left')
));


echo Form::close();

?>
