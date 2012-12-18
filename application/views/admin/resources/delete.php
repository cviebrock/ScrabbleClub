<div class="page-header">
	<h1>Delete Resource</h1>
</div>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('labelled_checkbox', 'confirm', 'Are you sure?',
	array('Yes, delete '. $resource->title, 1, false)
);

echo Form::actions(array(
	Form::submit('Delete Resource', array('class' => 'btn-primary btn-warning')),
	action_link_to_route('admin.resources@index', 'Back to Resources List', array($resource->resourcegroup_id), 'arrow-left')
));

echo Form::close();

?>
