<div class="row">

	<div class="span8">
		<h1>
			<?php echo $item->title ?>
		</h1>

		<?php echo $item->full_article; ?>


		<div class="news-summary">
			Published by <?php echo $item->author; ?> on <?php echo $item->formatted_date; ?>.
		</div>

	</div>

</div>
