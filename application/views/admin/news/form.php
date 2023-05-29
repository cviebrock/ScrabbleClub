<div class="page-header">
	<h1><?php echo $title; ?></h1>
</div>

<?php if ($item->has_errors()): ?>
<div class="alert alert-error">
	<strong>Oops!</strong> Please correct the errors below.
</div>
<?php endif; ?>

<?php

echo Form::open(null,'post', array('class'=>Form::TYPE_HORIZONTAL));
echo Form::token();

echo Form::field('select', 'author_id', 'Author',
	array($all_players, $item->author_id, array('class'=>'span4 required qselect')),
	array('error' => $item->error('author_id'))
);

echo Form::field('date', 'date', 'Date',
	array($item->date, array('class'=>'span2 required')),
	array('error' => $item->error('date'))
);

echo Form::field('text', 'title', 'Title',
	array($item->title, array('class'=>'span8 required')),
	array('error' => $item->error('title'))
);

echo Form::field('textarea', 'body', 'Body',
	array($item->body, array('class'=>'span8 required', 'style'=>'height: 10em')),
	array(
		'error' => $item->error('body'),
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

echo Form::field('select', 'fb_album', 'Facebook Album',
	array($albums, $item->fb_album, array('class'=>'span4')),
	array(
		'error' => $item->error('fb_album'),
		'help' => 'Albums may take up to 10 minutes to appear after being added to Facebook'
	)
);

echo Form::field('labelled_checkbox', 'active', 'Published?',
	array('Yes', 1, $item->active)
);

echo Form::actions(array(
	Form::submit($submit_text, array('class' => 'btn-primary')),
	action_link_to_route('admin.news', 'Back to News List', array(), 'arrow-left')
));

echo Form::close();

?>

<script>
$(document).ready( function() {

	$('.qselect').quickselect({
		minChars: 1,
		delay: 10,
		autoSelectFirst: true
	});


	$("#date").dateinput({
		format: 'dd-mmm-yyyy',
		value: '<?php echo $item->date; ?>'
	});

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
<?php if ($mode=='edit'): ?>
	$('#do-preview').click();
<?php endif; ?>


});
</script>
