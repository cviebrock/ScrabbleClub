<div class="page-header">
	<h1>Update Ratings for <?php echo $date; ?></h1>
</div>

<p>
	Are you sure you want to update the player club ratings for this date?
	This will erase any rating calculations made after this date (although there shouldn't be any).
</p>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, update ratings', 1, false)
);

echo Form::actions(array(
	Form::submit('Update Ratings', array('class' => 'btn-primary')),
	App::action_link_to_route('admin.games@index', 'Back to Games List', array(), 'arrow-left')
));


echo Form::close();

?>