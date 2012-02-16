<?php

Route::get('ajax/players', array( 'as'=>'ajax_players', 'before' => 'ajax', function()
{

	$players = Player::all();
	$data = array();

	foreach($players as $player) {
		$data[ $player->id ] = $player->fullname();
	}

	header('application/json');
	return json_encode($data);

}));
