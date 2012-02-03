<?php

Router::register('GET /games', array( 'name'=>'games', function()
{

	$games = DB::query('SELECT
			COUNT(id) AS count, date
			FROM games
			GROUP BY date
			ORDER BY date DESC
	');

	$view = View::make('default')
		->with('title', 'Games')
		->nest('content', 'games.index', array(
			'games' => $games,
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
	Asset::add('tablesorter', 'css/tablesorter.css');
	Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

	return $view;

}));


Router::register('GET /games/new', array( 'name'=>'new_games', function()
{

	$gameform = new Gameform;

	if (Session::has('last_date')) {
		$gameform->date = Session::get('last_date');
	} else {
		$t = new DateTime('tomorrow');
		$gameform->date = $t->modify('last Thursday');			/* last Thurs, unless today is Thurs, in which case today */
	}


	$view = View::make('default')
		->with('title', 'New Games')
		->nest('content', 'games.form', array(
			'gameform' => $gameform,
		));

	Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
	Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
	Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');
	Asset::add('quickselect', 'css/quickselect.css');
	Asset::add('dateinput', 'css/dateinput.css');

	return $view;

}));


Router::register('POST /games/new', array( 'name'=>'create_games', 'before' => 'csrf', function()
{

	$gameform = new Gameform;

	$gameform->player = Input::get('player');
	$gameform->date = Input::get('date');
	$gameform->bingos = Input::get('bingos');
	$gameform->opponents = Input::get('opponent');
	$gameform->player_scores = Input::get('player_score');
	$gameform->opponent_scores = Input::get('opponent_score');
	$gameform->populate_games();

	print_r($gameform);

}));
