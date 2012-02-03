<?php

Router::register('GET /players', array( 'name'=>'players', function()
{

	$view = View::make('default')
		->with('title', 'Players')
		->nest('content', 'players.index', array(
			'players' => Player::all(),
		));

	Asset::add('tablesorter', 'js/jquery.tablesorter.min.js', 'jquery');
	Asset::add('tablesorter-pager', 'js/jquery.tablesorter.pager.js', 'jquery');
	Asset::add('tablesorter', 'css/tablesorter.css');
	Asset::add('tablesorter-pager', 'css/tablesorter-pager.css');

	return $view;

}));


Router::register('GET /players/new', array( 'name'=>'new_player', function()
{

	$player = new Player;

	return View::make('default')
		->with('title', 'New Player')
		->nest('content', 'players.form', array(
			'player'				=> $player,
			'title'				=> 'New Player',
			'submit_text'	=> 'Add Player',
		));
}));


Router::register('POST /players/new', array( 'name'=>'create_player', 'before' => 'csrf', function()
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
			->nest('content', 'players.form', array(
				'player'        => $player,
				'title'       => 'New Player',
				'submit_text' => 'Add Player',
			));
	}

	$player->save();
	return Redirect::to_route('create_player')
		->with('success', 'Player "' . $player->fullname() . '" added.');

}));


Router::register('GET /players/(:num)', array( 'name'=>'show_player', function($id)
{

	$player = Player::find($id);

	return View::make('default')
		->with('title', $player->fullname() )
		->nest('content', 'players.show', array(
			'player'	=> $player,
		));
}));


Router::register('GET /players/(:num)/edit', array( 'name'=>'edit_player', function($id)
{

	$player = Player::find($id);

	return View::make('default')
		->with('title', 'Edit Player')
		->nest('content', 'players.form', array(
			'player'				=> $player,
			'title'				=> 'Edit Player',
			'submit_text'	=> 'Edit Player',
		));
}));


Router::register('POST /players/(:num)/edit', array( 'name'=>'update_player', 'before' => 'csrf', function($id)
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
			->nest('content', 'players.form', array(
				'player'        => $player,
				'title'       => 'Edit Player',
				'submit_text' => 'Edit Player',
			));
	}

	$player->save();
	return Redirect::to_route('edit_player', array($id))
		->with('success', 'Player "' . $player->fullname() . '" edited.');
}));


Router::register('GET /players/(:num)/delete', array( 'name'=>'delete_player', function($id)
{

	$player = Player::find($id);

	return View::make('default')
		->with('title', 'Delete Player')
		->nest('content', 'players.form_delete', array(
			'player'				=> $player,
			'title'				=> 'Delete Player',
			'submit_text'	=> 'Delete Player',
		));
}));


Router::register('POST /players/(:num)/delete', array( 'name'=>'remove_player', 'before' => 'csrf', function($id)
{

	$player = Player::find($id);

	if ( Input::get('confirm') !== 'yes' ) {
		return Redirect::to_route('players', array($id))
			->with('notice', 'Player "' . $player->fullname() . '" not deleted.');
	}

	$player->delete();
	return Redirect::to_route('players',array($id))
		->with('success', 'Player "' . $player->fullname() . '" deleted.');

}));
