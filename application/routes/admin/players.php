<?php

Route::get('admin/players', array( 'as'=>'admin_players', function()
{

	$view = View::make('default')
		->with('title', 'Players')
		->nest('content', 'admin.players.index', array(
			'players' => Player::all(),
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
//	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
//	Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

	return $view;

}));


Route::get('admin/players/new', array( 'as'=>'admin_player_new', function()
{

	$player = new Player;

	return View::make('default')
		->with('title', 'New Player')
		->nest('content', 'admin.players.form', array(
			'player'      => $player,
			'title'       => 'New Player',
			'submit_text' => 'Add Player',
		));
}));


Route::post('admin/players/new', array( 'before' => 'csrf', function()
{

	$player = new Player;

	$player->fill(array(
		'firstname'    => Input::get('firstname'),
		'lastname'     => Input::get('lastname'),
		'email'        => Input::get('email'),
		'naspa_id'     => Input::get('naspa_id'),
		'naspa_rating' => Input::get('naspa_rating'),
	));

	if (!$player->is_valid()) {
		return View::make('default')
			->with('title', 'New Player')
			->nest('content', 'admin.players.form', array(
				'player'      => $player,
				'title'       => 'New Player',
				'submit_text' => 'Add Player',
			));
	}

	$player->save();
	return Redirect::to_route('admin_players')
		->with('success', 'Player "' . $player->fullname() . '" added.');

}));


Route::get('admin/players/(:num)', array( 'as'=>'admin_player_view', function($id)
{

	$player = Player::find($id);

	return View::make('default')
		->with('title', $player->fullname() )
		->nest('content', 'admin.players.show', array(
			'player'	=> $player,
		));
}));


Route::get('admin/players/edit/(:num)', array( 'as'=>'admin_player_edit', function($id)
{

	$player = Player::find($id);

	return View::make('default')
		->with('title', 'Edit Player')
		->nest('content', 'admin.players.form', array(
			'player'				=> $player,
			'title'				=> 'Edit Player',
			'submit_text'	=> 'Edit Player',
		));
}));


Route::post('admin/players/edit/(:num)', array( 'before' => 'csrf', function($id)
{

	$player = Player::find($id);

	$player->fill(array(
		'firstname'    => Input::get('firstname'),
		'lastname'     => Input::get('lastname'),
		'email'        => Input::get('email'),
		'naspa_id'     => Input::get('naspa_id'),
		'naspa_rating' => Input::get('naspa_rating'),
	));


	if (!$player->is_valid()) {
		return View::make('default')
			->with('title', 'Edit Player')
			->nest('content', 'admin.players.form', array(
				'player'        => $player,
				'title'       => 'Edit Player',
				'submit_text' => 'Edit Player',
			));
	}

	$player->save();
	return Redirect::to_route('admin_player_edit', array($id))
		->with('success', 'Player "' . $player->fullname() . '" edited.');
}));


Route::get('admin/players/delete/(:num)', array( 'as'=>'admin_player_delete', function($id)
{

	$player = Player::find($id);

	return View::make('default')
		->with('title', 'Delete Player')
		->nest('content', 'admin.players.delete', array(
			'player'				=> $player,
		));
}));


Route::post('admin/players/delete/(:num)', array( 'before' => 'csrf', function($id)
{

	$player = Player::find($id);

	if ( !Input::get('confirm') ) {
		return Redirect::to_route('admin_player_delete', array($id))
			->with('warning', 'Player not deleted &mdash; confirmation not checked.');
	}

	$player->delete();
	return Redirect::to_route('admin_players',array($id))
		->with('success', 'Player "' . $player->fullname() . '" deleted.');

}));
