<div class="page-header">
	<h1>News Archive</h1>
</div>

<?php foreach($news->results as $item): ?>

	<h2 class="news-title">
		<?php echo $item->title; ?>
		<span class="subhead">
			<?php echo $item->formatted_date; ?> | <?php echo $item->author; ?>
		</span>
	</h2>

	<?php echo $item->summary_with_link; ?>

	<hr class="on">

<?php endforeach; ?>


<?php echo $news->links(); ?>
