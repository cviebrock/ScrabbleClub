<div class="page-header">
	<h1><?php echo $title; ?></h1>
</div>

<?php if ($resource->has_errors()): ?>
<div class="alert alert-error">
	<strong>Oops!</strong> Please correct the errors below.
</div>
<?php endif; ?>


<?php

echo Form::open_for_files(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();
echo Form::hidden('resourcegroup_id', $resource->resourcegroup_id );

echo Form::field('text', 'title', 'Title',
	array($resource->title, array('class'=>'span6 required')),
	array('error' => $resource->error('title'))
);

echo Form::field('textarea', 'description', 'Description',
	array($resource->description, array('class'=>'span6', 'rows'=>3)),
	array('error' => $resource->error('description'))
);

?>

<h3>Choose One</h3>

<?php

if ($resource->is_file()) {

	echo Form::field('text', 'url_link', 'Link to URL',
		array('', array('class'=>'span6')),
		array('error' => $resource->error('url_link'))
	);

	echo Form::field('file', 'url_file', 'or Link to file',
	 	array(array('class'=>'span3')),
	 	array(
	 		'error' => $resource->error('url_file'),
	 		'help' => 'Current file: '.$resource->downloadlink,
	 	)
	);


} else {

	echo Form::field('text', 'url_link', 'Link to URL',
		array($resource->url, array('class'=>'span6')),
		array('error' => $resource->error('url_link'))
	);

	echo Form::field('file', 'url_file', 'or Link to file',
	 	array(array('class'=>'span3')),
	 	array('error' => $resource->error('url_file'))
	);



}

?>


<?php
echo Form::actions(array(
	Form::submit($submit_text, array('class' => 'btn-primary')),
	action_link_to_route('admin.resources', 'Back to Resource Groups List', array(), 'arrow-left')
));

echo Form::close();



