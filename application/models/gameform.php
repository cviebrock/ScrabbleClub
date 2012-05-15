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

	public function error($field)
	{
		if (array_key_exists($field, $this->errors)) {
			return $this->errors[$field];
		}
		return '';
	}


	public function has_errors()
	{
		foreach($this->errors as $error) {
			if (is_array($error) || !empty($error)) {
				return true;
			}
		}
		return false;
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
			$lines = preg_split('/[,\n]+/', $this->bingo_list, null, PREG_SPLIT_NO_EMPTY);
			foreach($lines as $line) {
				$temp = preg_split('/[\s\-]+/', trim($line), 2, PREG_SPLIT_NO_EMPTY);
				$word = trim(array_shift($temp));
				if (empty($temp)) {
					$score = null;
				} else {
					$score = intval(array_shift($temp), 10);
				}

Log::info("$line -->  $word ($score)");

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
			unset($bingo->rules['player_id']);	/* don't validate this */
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


		if (empty($this->player_id)) {
			$this->errors['player_id'] = 'Required';
			$valid = false;
		} else {
			$player = Player::find($this->player_id);
			if (!$player->exists) {
				$this->errors['player_id'] = 'Player does not exist.';
				$valid = false;
			}
		}

		if (empty($this->date)) {
			$this->errors['date'] = 'Required';
			$valid = false;
		} else {
			$v = new Validator(null,array());
			if (!$v->validate_date(null,$this->date,null)) {
				$this->errors['date'] = 'Invalid date.';
				$valid = false;
			}
		}



		return $valid;

	}



	public function save()
	{

		Log::info('entering gameform::save');

		$success = true;

		foreach($this->games as $game) {

			Log::info('saving game:'.print_r($game->attributes,true));

			if ($game->save()) {
				Log::info('game saved, matching game');
				$game->match_game();
			} else {
				$success = false;
			}
		}

		foreach($this->bingos as $bingo) {
			if (!$bingo->save()) {
				$success = false;
			}
		}

		return $success;

	}


}