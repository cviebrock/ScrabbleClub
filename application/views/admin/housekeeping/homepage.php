<div class="page-header">
	<h1>Home Page</h1>
</div>


<?php if ($homepage->has_errors()): ?>
<div class="alert alert-error">
	<strong>Oops!</strong> Please correct the errors below.
</div>
<?php endif; ?>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('text', 'title', 'Title',
	array($homepage->title, array('class'=>'span8 required')),
	array('error' => $homepage->error('title'))
);

echo Form::field('textarea', 'body', 'Body',
	array($homepage->body, array('class'=>'span8 required', 'style'=>'height: 10em')),
	array(
		'error' => $homepage->error('body'),
		'help' => '<a href="#" id="do-preview">Preview</a> or get help with ' .
							'<a href="https://en.wikipedia.org/wiki/Markdown" target="_blank">Markdown</a>'
	)
);

?>

<div id="preview" class="control-group">
	<label class="control-label">Preview</label>
	<div class="controls">
		<div id="preview-result"></div>
	</div>
</div>

<?php


echo Form::actions(array(
	Form::submit('Save', array('class' => 'btn-primary')),
	action_link_to_route('admin.housekeeping', 'Back to Housekeeping', array(), 'arrow-left')
));

echo Form::close();

?>

<script>
$(document).ready( function() {

	$('#do-preview').on('click', function(e) {
		e.preventDefault();
		$('#preview-result').load(
			'<?php echo URL::to_action('ajax@markdown'); ?>',
			{
				'title': $('#title').val(),
				'body': $('#body').val()
			},
			function() {
				$('#preview').fadeIn();
			}
		);

	});

	$('#preview').hide();
	$('#do-preview').click();


});
</script>
