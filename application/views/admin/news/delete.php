<div class="page-header">
	<h1>Delete News Item</h1>
</div>


<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, delete '.$item->title, 1, false)
);

echo Form::actions(array(
	Form::submit('Delete News Item', array('class' => 'btn-primary btn-warning')),
	action_link_to_route('admin.news', 'Back to News List', array(), 'arrow-left')
));


echo Form::close();


?>
