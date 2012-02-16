<?php



class Game extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'date'           => 'required|date',
		'player_id'      => 'required|exists:players,id|different:opponent_id',
		'opponent_id'    => 'required|exists:players,id|different:player_id',
		'player_score'   => 'required|integer',
		'opponent_score' => 'required|integer',
	);

	public $messages = array(
		'required'	=> 'Required',
		'different' => 'Player can not play themselves.',
		'exists'		=> 'Player does not exist - add them first'
	);

	public function player()
	{
	 return $this->belongs_to('Player');
	}


	public function opponent()
	{
	 return $this->belongs_to('Player','opponent_id');
	}


	public function is_empty()
	{
		return ($this->opponent_id==0 && $this->player_score==0 && $this->opponent_score==0);
	}

	public function save()
	{
		$this->spread = $this->player_score - $this->opponent_score;
		return parent::save();
	}


	public function set_matching_game()
	{

		$other = DB::table('games')
			->where('date', '=', $this->date)
			->where('player_id', '=', $this->opponent_id)
			->where('opponent_id', '=', $this->player_id)
			->where('player_score', '=', $this->opponent_score)
			->where('opponent_score', '=', $this->player_score)
			->where('matching_game', '=', 0)
			->first();

		if (!is_null($other)) {
			$game = Game::find($other->id);
			$game->matching_game = $this->id;
			$this->matching_game = $game->id;
			$game->save();
			$this->save();

			return true;
		}

		return false;

	}


	public function swap_players()
	{
		$temp = $this->player_id;
		$this->player_id = $this->opponent_id;
		$this->opponent_id = $temp;

		$temp = $this->player_score;
		$this->player_score = $this->opponent_score;
		$this->opponent_score = $temp;

		$this->spread = -$this->spread;
	}


}