<?php

/*
CREATE  TABLE IF NOT EXISTS `bingos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`date` DATE NOT NULL DEFAULT '0000-00-00' ,
	`player_id` INT UNSIGNED NOT NULL ,
  `word` varchar(15) NOT NULL DEFAULT '',
  `score` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) ,
  INDEX (`player_id`),
  INDEX (`date`) )
ENGINE = MyISAM
*/


class Bingo extends BaseModel {

	public static $timestamps = false;

	public $rules = array(
		'player_id' => 'exists:players,id',
		'date'      => 'required|date',
		'word'      => 'required|alpha|min:7|max:15',
		'score'     => 'integer|min:55',
	);

	public function save() {
		$this->word = strtolower($this->word);
		$this->valid = ValidWord::where('word','=',$this->word)->count();
		return parent::save();
	}

	public function word_and_score() {
		$r = strtoupper($this->word);
		if ($this->score) {
			$r .= ' (' . $this->score . ')';
		}
		return $r;
	}

}