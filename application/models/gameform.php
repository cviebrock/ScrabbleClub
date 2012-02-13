<?php

class Gameform {

	public $player_id;
	public $date;
	public $bingo_list = '';
	public $bingos = array();
	public $games = array();
	public $opponent_id = array();
	public $player_score = array();
	public $opponent_score = array();


	public $errors = array();
	public $help = array(
		'bingos'	=> 'Type the word and (optionally) score separated by a space; one entry per line or comma-separated.'
	);


	public function __construct() {
		$this->reset();
	}

	public function player()
	{
		if ($this->player_id) {
			return Player::find($this->player_id);
		} else {
			return null;
		}

	}


	public function reset_games()
	{
		$this->games = array();
	}

	public function reset_bingos()
	{
		$this->bingos = array();
	}

	public function reset() {
		$this->reset_games();
		$this->reset_bingos();
	}


	public function populate_games()
	{

		$this->reset_games();

		$c = max( count($this->opponent_id), count($this->player_score), count($this->opponent_score) );


		for($i=0; $i<$c; $i++) {
			$game = new Game();
			$game->fill(array(
				'player_id'      => $this->player_id,
				'date'           => $this->date,
				'opponent_id'    => $this->opponent_id[$i],
				'player_score'   => $this->player_score[$i],
				'opponent_score' => $this->opponent_score[$i],
			));
			if (!$game->is_empty()) {
				$this->games[] = $game;
			}
		}

	}


	public function populate_bingos()
	{

		$this->reset_bingos();

		if (!empty($this->bingo_list)) {
			$temp = preg_split('/[,\n]+/', $this->bingo_list, null, PREG_SPLIT_NO_EMPTY);
			foreach($temp as $b) {
				list($word,$score) = preg_split('/[\s\-]+/', trim($b), 2, PREG_SPLIT_NO_EMPTY);
				if ($score) {
					$score = intval($score, 10);
				} else {
					$score = null;
				}
				$bingo = new Bingo;
				$bingo->fill(array(
					'date'   		=> $this->date,
					'player_id' => $this->player_id,
					'word'      => trim($word),
					'score'     => $score,
				));
				$this->bingos[] = $bingo;
			}
		}
	}


	public function populate() {
		$this->populate_games();
		$this->populate_bingos();
	}


	public function is_valid()
	{

		$valid = true;
		$this->errors = array();


		$this->populate();

		foreach($this->games as $game) {
			if (!$game->is_valid()) {
				$valid = false;

				if (array_key_exists('date', $game->errors)) {
					$this->errors['date'] = $game->errors['date'];
				}

			}
		}

		foreach($this->bingos as $bingo) {
			if (!$bingo->is_valid()) {
				$valid = false;
				if (!array_key_exists('bingo_list', $this->errors)) {
					$this->errors['bingo_list'] = array();
				}
				if (count($bingo->errors) > 1) {
					$this->errors['bingo_list'][] = '"' . $bingo->word . ' ' . $bingo->score . '" is not a valid bingo.';
				} else {
					$msg = join(' ', current($bingo->errors));
					$this->errors['bingo_list'][] = '"' . $bingo->word . '" - ' . $msg;
				}
			}
		}

		$player = Player::find($this->player_id);
		if (!$player->exists) {
			$this->erorrs['player_id'] = 'Player does not exist.';
			$valid = false;
		}

		return $valid;

	}



	public function save()
	{

		$success = true;

		foreach($this->games as $game) {
			$success &= $game->save();
			$game->set_matching_game();
		}

		foreach($this->bingos as $bingo) {
			$success &= $bingo->save();
		}

		return $success;

	}


}