<?php

Route::get('players', array( 'as'=>'players', function()
{


	$players = DB::query('SELECT
		p.id,
		CONCAT (p.firstname," ",p.lastname) AS fullname,
		COUNT(g.id) AS games_played,
		SUM(IF(g.spread>0,1,0)) AS wins,
		SUM(IF(g.spread=0,1,0)) AS ties,
		SUM(IF(g.spread<0,1,0)) AS losses,
		ROUND(AVG(g.player_score)) AS average_score,
		ROUND(AVG(g.opponent_score)) AS average_opponent_score,
		ROUND(AVG(g.spread)) AS average_spread,
		MAX(g.player_score) AS best_score,
		MAX(g.spread) AS best_spread
		FROM players p LEFT JOIN games g ON (p.id=g.player_id)
		GROUP BY p.id
		ORDER BY games_played DESC
	');


	$view = View::make('default')
		->with('title', 'Players')
		->nest('content', 'players.index', array(
			'players' => $players,
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

	return $view;


}));


Route::get('players/(:num)', array( 'as'=>'player_details', function($id)
{

	$player = Player::find($id);

	$temp = DB::query('SELECT
		COUNT(g.id) AS games_played,
		SUM(IF(g.spread>0,1,0)) AS wins,
		SUM(IF(g.spread=0,1,0)) AS ties,
		SUM(IF(g.spread<0,1,0)) AS losses,
		ROUND(AVG(g.player_score)) AS average_score,
		ROUND(AVG(g.opponent_score)) AS average_opponent_score,
		ROUND(AVG(g.spread)) AS average_spread
		FROM games g
		WHERE g.player_id = ?
	', array($id));

	$club_details = $temp[0];


	$best_wins = Game::where('player_id','=',$id)
		->order_by('spread','desc')
		->order_by('date','desc')
		->first();

	$worst_losses = Game::where('player_id','=',$id)
		->order_by('spread','asc')
		->order_by('date','desc')
		->first();

	$best_spreads = array( $best_wins, $worst_losses );

	$high_score = Game::where('player_id','=',$id)
		->order_by('player_score','desc')
		->order_by('date','desc')
		->first();

	$low_score = Game::where('player_id','=',$id)
		->order_by('player_score','asc')
		->order_by('date','desc')
		->first();

	$best_scores = array( $high_score, $low_score );


	$view = View::make('default')
		->with('title', $player->fullname())
		->nest('content', 'players.details', array(
			'player'       => $player,
			'club_details' => $club_details,
			'best_scores'  => $best_scores,
			'best_spreads' => $best_spreads,
		));


	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');

	return $view;


}));
