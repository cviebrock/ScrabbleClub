<?php

/*
CREATE TABLE `ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL,
  `date` datetime DEFAULT NULL,
  `performance_rating` smallint(5) unsigned DEFAULT NULL,
  `rating` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ratings_players` (`player_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
*/


class Ratings extends BaseModel {

	public static $timestamps = false;

	public $rules = array(
		'date'               => 'required|date',
		'player_id'          => 'required|exists:players,id',
		'performance_rating' => 'integer',
		'rating'             => 'integer',
	);

	public function player()
	{
	 return $this->belongs_to('Player');
	}

}