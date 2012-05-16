<?php

/*
CREATE TABLE `ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL,
  `date` datetime DEFAULT NULL,
  `starting_rating` smallint(5) unsigned DEFAULT NULL,
  `games_played` smallint(5) unsigned DEFAULT NULL,
  `games_won` decimal(4,2) DEFAULT NULL,
  `total_opp_ratings` smallint(5) unsigned DEFAULT NULL,
	`kfactor` smallint(5) unsigned DEFAULT NULL,
	`expected_wins` decimal(4,2) unsigned DEFAULT NULL,
  `performance_rating` smallint(5) unsigned DEFAULT NULL,
  `ending_rating` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ratings_players` (`player_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
*/


class Rating extends BaseModel {

	public static $timestamps = false;

	public $rules = array(
		'player_id'          => 'required|exists:players,id',
		'date'               => 'required|date',
		'starting_rating'    => 'required|integer',
		'games_played'       => 'required|integer',
		'games_won'          => 'required|numeric',
		'total_opp_ratings'  => 'required|integer',
		'kfactor'            => 'required|integer',
		'expected_wins'      => 'required|numeric',
		'performance_rating' => 'required|integer',
		'ending_rating'      => 'required|integer',
	);

	public function player()
	{
	 return $this->belongs_to('Player');
	}


	public function calculate_elo()
	{
		$avg_opp_rating = $this->total_opp_ratings / $this->games_played;
		$this->expected_wins = $this->games_played / ( 1 + ( pow( 10 , ( $avg_opp_rating - $this->starting_rating ) / 400 ) ) );
		$this->ending_rating = $this->starting_rating + ( $this->kfactor * ( $this->games_won - $this->expected_wins ) );

		$this->performance_rating = round($avg_opp_rating + 400 * ($this->plus_minus)/($this->games_played));

	}



	public function save() {
		$this->purge('plus_minus');
		parent::save();
	}



}