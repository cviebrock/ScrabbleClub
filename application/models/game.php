<?php

/*
CREATE  TABLE IF NOT EXISTS `wsc`.`games` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`date` DATE NOT NULL DEFAULT '0000-00-00' ,
	`player_id` INT UNSIGNED NOT NULL ,
	`opponent_id` INT UNSIGNED NOT NULL ,
	`player_score` SMALLINT NOT NULL DEFAULT 0 ,
	`opponent_score` SMALLINT NOT NULL DEFAULT 0 ,
	`spread` SMALLINT NOT NULL DEFAULT 0 ,
	`created_at` DATETIME NULL DEFAULT '0000-00-00' ,
	`updated_at` DATETIME NULL DEFAULT '0000-00-00' ,
	PRIMARY KEY (`id`)
) ENGINE = MyISAM
*/


class Game extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'date'           => 'required|date',
		'player_id'      => 'required|not_same_as:opponent_id',
		'opponent_id'    => 'required|not_same_as:player_id',
		'player_score'   => 'required|integer',
		'opponent_score' => 'required|integer',
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


}