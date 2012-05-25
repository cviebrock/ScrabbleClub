<div class="row">

	<div class="span8">

		<h1><?php echo Config::get('application.clubname'); ?></h1>

		<p>
			This is the homepage.
		</p>
	</div>



	<div class="span4 sidebar">

		<?php if ($date): ?>

		<h3>Last Week&rsquo;s Statistics</h3>

		<h4><?php echo App::format_date($date, 'M j, Y'); ?></h4>

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
					<td><?php echo $bingo->player->fullname(); ?></td>
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
					<td><?php echo $game->player->fullname(); ?></td>
					<td class="numeric"><?php echo $game->player_score; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>


		<h4>Best Performances</h4>

		<table class="table table-condensed">
			<tbody>
			<?php foreach($sidebar['ratings'] as $rating): ?>
			<?php $delta = $rating->ending_rating - $rating->starting_rating; ?>
				<tr>
					<td><?php echo $rating->player->fullname(); ?></td>
					<td><?php printf('%.1f-%.1f', $rating->games_won, ($rating->games_played-$rating->games_won)); ?></td>
					<td class="numeric"><?php printf('%+d', $delta); ?></td>
					<td class="numeric"><span class="gray"><?php echo $rating->performance_rating; ?></span></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<div class="actions pull-right">
			<?php echo App::action_link_to_route('club@summary', 'Full Summary', array($date), 'arrow-right|white', array('class'=>'btn btn-info btn-small')); ?>
		</div>

		<?php endif; ?>

	</div>




</div>
