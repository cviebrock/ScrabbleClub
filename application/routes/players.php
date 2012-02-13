<?php

Route::get('players', array( 'name'=>'players', function()
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
	Asset::add('tablesorter', 'css/tablesorter.css');
	Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

	return $view;


}));
