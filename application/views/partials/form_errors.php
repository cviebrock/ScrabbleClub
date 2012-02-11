<div class="form_errors<?php echo $inline ? ' inline' : ''; ?>">
	<?php foreach($messages as $err): ?>
		<?php echo $err; ?><br/>
	<?php endforeach; ?>
</div>