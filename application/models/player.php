<?php

/*
CREATE  TABLE IF NOT EXISTS `wsc`.`players` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`firstname` VARCHAR(64) NOT NULL ,
	`lastname` VARCHAR(64) NOT NULL ,
	`email` VARCHAR(64) NOT NULL ,
	`password` VARCHAR(64) NOT NULL ,
	`naspa_id` VARCHAR(16) NULL ,
	`naspa_rating` SMALLINT NULL DEFAULT NULL ,
	`created_at` DATETIME NULL DEFAULT '0000-00-00' ,
	`updated_at` DATETIME NULL DEFAULT '0000-00-00' ,
	PRIMARY KEY (`id`) ,
	INDEX `fullname` (`lastname`(8) ASC, `firstname`(8) ASC) )
ENGINE = MyISAM
*/


class Player extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'firstname'    => 'required|max:64',
		'lastname'     => 'required|max:64',
		'email'        => 'email|unique:players|max:64',
		'naspa_rating' => 'integer',
	);


	public function ratings()
	{
		return $this->has_many('Rating')->order_by('date','asc');
	}

	public function games()
	{
		return $this->has_many('Game');
	}

	public function bingos()
	{
		return $this->has_many('Bingo');
	}

	public function current_rating()
	{
		$ratings = $this->ratings;
		if (count($ratings)) {
			$r = array_pop($ratings);
			return $r->ending_rating;
		}
		return Config::get('scrabble.initial_rating');
	}

	public function rating_before_date($date)
	{
		$ratings = $this->ratings;
		$r = Config::get('scrabble.initial_rating');

		if (count($ratings)) {
			foreach($ratings as $rating) {
				if ($rating->date < $date) {
					$r = $rating->ending_rating;
				} else {
					break;
				}
			}
		}
		return $r;
	}

	public function get_fullname()
	{
		return $this->firstname . ' ' . $this->lastname;
	}


	public function complete_games()
	{
		return $this->games()->where('matching_game','!=',0);
	}


	public function games_won() {
		return $this->complete_games()->where('spread','>',0);
	}

	public function games_tied() {
		return $this->complete_games()->where('spread','=',0);
	}

	public function games_lost() {
		return $this->complete_games()->where('spread','<',0);
	}

	public function kfactor($date) {
		$games = $this->complete_games()
			->where('date','<',$date)
			->count();
		$rating = $this->rating_before_date($date);
		// http://www.poslarchive.com/math/software/tsh/lib/perl/Ratings/Elo.pm
		if ($games < 50) {
			$k = 15;
			if ($rating < 1800) {
				$k = 30;
			} else if ($rating < 2000) {
				$k = 24;
			}
		} else {
			$k = 10;
			if ($rating < 1800) {
				$k = 20;
			} else if ($rating < 2000) {
				$k = 16;
			}
		}
    return $k;
	}


	public function ratio()
	{
		if ($this->complete_games()->count()) {
			return $this->games_won_or_tied() / $this->complete_games()->count();
		} else {
			return -1;
		}
	}

	public function games_won_or_tied() {
		return $this->games_won()->count() + ($this->games_tied()->count() * 0.5);
	}


	public function __toString()
	{
		return $this->get_fullname();
	}

}
