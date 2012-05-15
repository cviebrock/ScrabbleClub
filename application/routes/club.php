<?php

Route::get('club', array( 'as'=>'club', function()
{

	$lastgame = Game::order_by('date', 'desc')
		->take(1)
		->first();


	$overall = array();

	$temp = DB::query('SELECT
		COUNT(g.id)/2 AS total_games,
		COUNT(DISTINCT g.date)/2 AS total_dates,
		AVG(g.player_score) AS average_score
		FROM games g
	');

	$overall = array_merge($overall, (array)$temp[0]);

	$temp = DB::query('SELECT
		AVG(x.players) as players_per_date
		FROM (
			SELECT
			date, COUNT(DISTINCT player_id) AS players
			FROM games
			GROUP BY date
		) x
	');

	$overall = array_merge($overall, (array)$temp[0]);


	$attendance = DB::query('SELECT
		g.date AS date,
		COUNT(DISTINCT g.player_id) AS players
		FROM games g
		GROUP BY date
	');


	$high_scores = Game::order_by('player_score','desc')
		->order_by('opponent_score','desc')
		->order_by('date','desc')
		->take(5)
		->get();

	$closest_games = Game::where('spread','>',0)
		->order_by('spread','asc')
		->order_by('date','desc')
		->take(5)
		->get();

	$blowouts = Game::order_by('spread','desc')
		->order_by('date','desc')
		->take(5)
		->get();

	$combined = Game::where('player_id', '>', 'opponent_id')
		->order_by(DB::raw('`player_score`+`opponent_score`'),'desc')
		->order_by('date','desc')
		->take(5)
		->get();

	$view = View::make('default')
		->with('title', 'Club Statistics')
		->nest('content', 'club.index', array(
			'lastgame'      => $lastgame,
			'overall'       => $overall,
			'attendance'    => $attendance,
			'high_scores'   => $high_scores,
			'closest_games' => $closest_games,
			'blowouts'      => $blowouts,
			'combined'      => $combined,
		));


	// Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	// Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
	// Asset::add('string_score', 'js/string_score.min.js', 'jquery');
	// Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');

	return $view;


}));


Route::get('players/(:num)/bingos', array( 'as'=>'player_bingos', function($id)
{

	$player = Player::find($id);

	$bingos = DB::query('SELECT
		b.*,
		v.playability AS playability
		FROM bingos b LEFT JOIN validwords v USING (word)
		WHERE b.player_id = ?
	', array($id));

	$view = View::make('default')
		->with('title', $player->fullname())
		->nest('content', 'players.bingos', array(
			'player' => $player,
			'bingos' => $bingos,
		));


	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

	return $view;


}));
