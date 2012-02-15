<?php

Route::get('admin/games', array( 'name'=>'admin_games', function()
{

	$games = DB::query('SELECT
			date,
			FLOOR(SUM(IF(matching_game=0,0,1))/2) AS complete_games,
			COUNT(DISTINCT player_id) AS players,
			SUM(IF(matching_game=0,1,0)) AS unmatched_games
			FROM games
			GROUP BY date
			ORDER BY date DESC
	');

	$view = View::make('default')
		->with('title', 'Games')
		->nest('content', 'admin.games.index', array(
			'games' => $games,
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
//	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
//	Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

	return $view;

}));


Route::get('admin/games/(\d{4}-\d{2}-\d{2})', array( 'name'=>'admin_games_list', function($date)
{

	$temp = DB::query('SELECT
		id, matching_game,
		IF( player_id<opponent_id,
			CONCAT(player_id,"-",opponent_id),
			CONCAT(opponent_id,"-",player_id)
		) AS pairing
		FROM games
		WHERE date = ?
		ORDER BY SIGN(matching_game) DESC, pairing DESC, spread DESC
	',
		array($date)
	);

	$matched_games = $unmatched_games = $seen = array();

	foreach ($temp as $k=>$game) {

		if ($game->matching_game) {
			if (!in_array($game->matching_game, $seen)) {
				$matched_games[] = Game::find($game->id);
				$seen[] = $game->id;
			}
		} else {
			$unmatched_games[] = Game::find($game->id);
		}
	}

	$fdate = App::format_date($date);

	$view = View::make('default')
		->with('title', 'Games for '.$fdate)
		->nest('content', 'admin.games.list', array(
			'fdate'           => $fdate,
			'date'            => $date,
			'matched_games'   => $matched_games,
			'unmatched_games' => $unmatched_games,
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
//	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
//	Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

	return $view;

}));


Route::get('admin/games/new/(:any?)', array( 'name'=>'admin_games_new', function($date)
{

	$gameform = new Gameform;

	if ($date) {
		$gameform->date = $date;
	} else if (Session::has('last_date')) {
		$gameform->date = Session::get('last_date');
	} else {
		$t = new DateTime('tomorrow');
		$gameform->date = $t->modify('last Thursday')->format('Y-m-d');			/* last Thurs, unless today is Thurs, in which case today */
	}


	$view = View::make('default')
		->with('title', 'New Games')
		->nest('content', 'admin.games.form', array(
			'gameform' => $gameform,
			'all_players'  => App::all_players(),
		));

	Asset::add('dateinput', 'js/jquery.tools.min.js', 'jquery');
	Asset::add('string_score', 'js/string_score.min.js', 'jquery');
	Asset::add('quickselect', 'js/jquery.quickselect.js', 'jquery');
//	Asset::add('quickselect', 'css/quickselect.css');
//	Asset::add('dateinput', 'css/dateinput.css');

	return $view;

}));


Route::post('admin/games/new', array( 'before' => 'csrf', function()
{

	$gameform = new Gameform;

	$gameform->player_id = Input::get('player_id');
	try {
		$temp = new DateTime( Input::get('date') );
		$gameform->date = $temp->format('Y-m-d');
	} catch ( Exception $e )  {
		$gameform->date = null;
	}
	$gameform->bingo_list = Input::get('bingo_list');
	$gameform->opponent_id = Input::get('opponent_id');
	$gameform->player_score = Input::get('player_score');
	$gameform->opponent_score = Input::get('opponent_score');


	if (!$gameform->is_valid()) {
		$view = View::make('default')
			->with('title', 'New Games')
			->nest('content', 'admin.games.form', array(
				'gameform' => $gameform,
				'all_players'  => App::all_players(),
			));

		Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');
//		Asset::add('quickselect', 'css/quickselect.css');
//		Asset::add('dateinput', 'css/dateinput.css');

		return $view;

	}

	Session::put('last_date', $gameform->date);

	$gameform->save();
	return Redirect::to_route('admin_games_new')
		->with('success', 'Games for "' . $gameform->player()->fullname() . '" on ' .
			App::format_date($gameform->date) . ' added.');


}));


Route::get('admin/games/edit/(:num)', array( 'name'=>'admin_game_edit', function($id)
{

	$game = Game::find($id);

	$view = View::make('default')
		->with('title', 'Edit Game')
		->nest('content', 'admin.games.edit', array(
			'game' => $game,
			'all_players'  => App::all_players(),
		));

	Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
	Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
	Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');
	Asset::add('quickselect', 'css/quickselect.css');
	Asset::add('dateinput', 'css/dateinput.css');

	return $view;

}));


Route::post('admin/games/edit/(:num)', array( 'before' => 'csrf', function($id)
{

	$game = Game::find($id);

	try {
		$temp = new DateTime( Input::get('date') );
		$game->date = $temp->format('Y-m-d');
	} catch ( Exception $e )  {
		$game->date = null;
	}
	$game->player_id = Input::get('player_id');
	$game->player_score = Input::get('player_score');
	$game->opponent_id = Input::get('opponent_id');
	$game->opponent_score = Input::get('opponent_score');

	if (!$game->is_valid()) {

		$view = View::make('default')
			->with('title', 'Edit Game')
			->nest('content', 'admin.games.edit', array(
				'game' => $game,
				'all_players'  => App::all_players(),
			));

		Asset::container('head')->add('dateinput', 'js/jquery.tools.min.js', 'jquery');
		Asset::container('head')->add('string_score', 'js/string_score.min.js', 'jquery');
		Asset::container('head')->add('quickselect', 'js/jquery.quickselect.js', 'jquery');

		return $view;

	}

	Session::put('last_date', $game->date);

	$game->save();
	if ($game->set_matching_game()) {
		return Redirect::to_route('admin_games_list', array($game->date) )
			->with('success', 'Game edited and matched.');
	} else {
		return Redirect::to_route('admin_game_edit', array($game->id) )
			->with('success', 'Game edited.');
	}



}));


Route::get('admin/games/delete/(:num)', array( 'name'=>'admin_game_delete', function($id)
{

	$game = Game::find($id);

	$view = View::make('default')
		->with('title', 'Delete Game')
		->nest('content', 'admin.games.delete', array(
			'game'				=> $game
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	Asset::add('tablesorter', 'css/tablesorter.css');

	return $view;

}));


Route::post('admin/games/delete/(:num)', array( 'before' => 'csrf', function($id)
{

	$game = Game::find($id);
	$date = $game->date;

	if ( !Input::get('confirm') ) {
		return Redirect::to_route('admin_game_delete', array($id))
			->with('warning', 'Game not deleted &mdash; confirmation not checked.');
	}


	$game->delete();
	return Redirect::to_route('admin_games_list', array($date))
		->with('success', 'Game deleted.');

}));



Route::get('admin/games/create_match/(:num)', array( 'name'=>'admin_game_create_match', function($id)
{

	$game = Game::find($id);
	if ($game->matching_game) {
		return Redirect::to_route('admin_games')
			->with('error','That game is already matched.');
	}

	$view = View::make('default')
		->with('title', 'Match Game')
		->nest('content', 'admin.games.match', array(
			'game' => $game
		));

	Asset::add('tablesorter', 'css/tablesorter.css');

	return $view;

}));


Route::post('admin/games/create_match/(:num)', array( 'before' => 'csrf', function($id)
{

	$game = Game::find($id);

	if ( !Input::get('confirm') ) {
		return Redirect::to_route('admin_game_create_match', array($id))
			->with('warning', 'Game not created &mdash; confirmation not checked.');
	}


	$game->swap_players();
	$game->exists = false;
	$game->id = null;
	$game->save();
	$game->set_matching_game();

	return Redirect::to_route('admin_games_list', array($game->date))
		->with('success', 'Matched game created.');

}));
