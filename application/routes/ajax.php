<?php

Route::get('ajax/players', array( 'as'=>'ajax_players', 'before' => 'ajax', function()
{

	$players = Player::all();
	$data = array();

	foreach($players as $player) {
		$data[ $player->id ] = $player->fullname;
	}

	header('application/json');
	return json_encode($data);

}));

Route::get('ajax/games/(:num)/vs/(:num?)', array( 'as'=>'ajax_one_on_one', 'before' => 'ajax', function($player_id,$opponent_id=null)
{

// the second arg isn't really optional, but if it isn't specified this way,
// then you can't use URL::to_route() without giving both args ... and
// we use jQuery to generate that second arg.

	usleep(500000);

	$games = Game::where('player_id','=',$player_id)
		->where('opponent_id','=',$opponent_id)
		->order_by('date','desc')
		->get();

	if (count($games)) {

		echo View::make('partials.game_listing')
			->with('games', $games)
			->with('id', 'ooo_games')
			->with('mark_winners', true)
			->with('small_head', true)
			->render();

	} else {

		echo '<p class="ooo_no_history">No matching games.</p>';

	}

}));
