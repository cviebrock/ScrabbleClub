<div class="row">

	<div class="span8">

<?php echo $homepage; ?>

<?php if ( count($news) ): ?>

		<h3 class="pull-down latest-news">Latest News</h3>

<?php foreach($news as $k=>$item): ?>

	<div class="news-item">
	<h1>
		<?php echo $item->title; ?>
		<span class="subhead">
			<?php echo $item->formatted_date; ?> | <?php echo $item->author; ?>
			<?php if ( $item->fb_album ): ?>
				| <i class="icon-picture"></i>
			<?php endif; ?>
		</span>
	</h1>

	<?php echo $item->summary_with_link; ?>
	</div>

<?php endforeach; ?>
<?php endif; ?>



<?php if( $url = Config::get('facebook.url') ): ?>
	<hr class="on">
	<h2>Like Us on Facebook</h2>
	<p>
		Visit our Facebook page at <?php echo HTML::link($url, deprotofy($url), array('target'=>'_blank')); ?> and be sure to like us!
	</p>
	<div class="fb-like" data-href="<?php echo $url; ?>"
	 data-send="true" data-width="450" data-show-faces="true"></div>
<?php endif; ?>


	</div>

	<div class="span4 sidebar">

		<?php if ($date): ?>

		<h3>
			Last Week&rsquo;s Statistics
			<span class="subhead"><?php echo format_date($date, 'M j, Y'); ?></span>
		</h3>

		<table class="table table-condensed">
			<tbody>
				<tr>
					<th class="span1 horizontal-header">Players</th>
					<td class="span1 numeric"><?php echo (int)$sidebar['total_players']; ?></td>
				</tr>
				<tr>
					<th class="span1 horizontal-header">Games</th>
					<td class="span1 numeric"><?php echo (int)$sidebar['total_games']; ?></td>
				</tr>
				<tr>
					<th class="span1 horizontal-header">Average Score</th>
					<td class="span1 numeric"><?php echo round($sidebar['average_score']); ?></td>
				</tr>
			</tbody>
		</table>

		<h4>Bingos</h4>

		<table class="table table-condensed">
			<tbody>
			<?php foreach($sidebar['bingos'] as $bingo): ?>
				<tr>
					<td><?php echo $bingo->player->fullname; ?></td>
					<td>
						<i class="icon-<?php echo $bingo->valid ? 'ok' : 'remove'; ?> icon-fade"></i>
						<?php echo $bingo->word; ?>
					</td>
					<td class="numeric"><?php echo $bingo->score; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>


		<h4>High Scores</h4>

		<table class="table table-condensed">
			<tbody>
			<?php foreach($sidebar['high_scores'] as $game): ?>
				<tr>
					<td><?php echo $game->player->fullname; ?></td>
					<td class="numeric"><?php echo $game->player_score; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

<?php if ($sidebar['ratings']): ?>
		<h4>Best Performances</h4>

		<table class="table table-condensed">
			<tbody>
			<?php foreach($sidebar['ratings'] as $rating): ?>
			<?php $delta = $rating->ending_rating - $rating->starting_rating; ?>
				<tr>
					<td><?php echo $rating->player->fullname; ?></td>
					<td><?php printf('%.1f-%.1f', $rating->games_won, ($rating->games_played-$rating->games_won)); ?></td>
					<td class="numeric"><?php printf('%+d', $delta); ?></td>
					<td class="numeric"><span class="gray"><?php echo $rating->performance_rating; ?></span></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
<?php endif ?>

		<div class="actions cfx">
			<?php echo action_link_to_route('club@summary', 'Weekly Summaries', array($date), 'arrow-right|white', array('class'=>'btn btn-info btn-small pull-right')); ?>
		</div>

		<?php endif; ?>

	</div>



</div>
