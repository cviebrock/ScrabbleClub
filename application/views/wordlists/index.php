<div class="page-header">
	<h1>Word Lists</h1>
</div>

<h2>Common Six-Letter Stems to Bingos</h2>
<ul>
<?php foreach($stem67 as $word): ?>
	<li>
		<?php echo HTML::link_to_action('wordlists@stem', Str::upper($word), array($word) ); ?>
	</li>
<?php endforeach; ?>
</ul>

