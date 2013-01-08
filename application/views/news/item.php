<div class="row">

	<div class="span8">

		<div class="page-header">
			<h1><?php echo $item->title ?></h1>
		</div>

		<?php echo $item->full_article; ?>

	</div>

	<div class="span4 news-summary">
		Published <?php echo $item->formatted_date; ?><br>
		by <?php echo $item->author; ?><br>
		<a href="javascript:history.back()">&larr; Back</a>
	</div>

</div>
