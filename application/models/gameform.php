<?php

class Gameform {

	public $player;
	public $date;
	public $bingos;
	public $games = array();
	public $opponents = array();
	public $player_scores = array();
	public $opponent_scores = array();


	public $errors = array();
	public $help = array(
		'bingos'	=> 'Type the word and (optionally) score separated by a space; one entry per line or comma-separated.'
	);


	public function __construct() {
		$this->reset_games();
	}


	public function date($format='Y-m-d') {
		return $this->date->format($format);
	}


	public function reset_games()
	{
		$this->games = array();
	}


	public function populate_games()
	{

		$this->reset_games();

		$c = max( count($this->opponents), count($this->player_scores), count($this->opponent_scores) );


		for($i=0; $i<$c; $i++) {
			$game = new Game();
			$game->fill(array(
				'player_id'				=>$this->player,
				'opponent_id'    => $this->opponents[$i],
				'player_score'   => $this->player_scores[$i],
				'opponent_score' => $this->opponent_scores[$i],
			));
			if (!$game->is_empty()) {
				$this->games[] = $game;
			}
		}

	}



	public function is_valid()
	{
	}



}