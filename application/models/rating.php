<?php

/*
CREATE  TABLE IF NOT EXISTS `wsc`.`ratings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `rating` SMALLINT UNSIGNED NULL ,
  `player_id` INT UNSIGNED NOT NULL ,
  `date` DATETIME NULL ,
  PRIMARY KEY (`id`, `user_id`) ,
  INDEX `fk_ratings_users` (`user_id` ASC) ,
  INDEX `date` (`date` DESC) )
ENGINE = MyISAM
*/


class Ratings extends Eloquent {

}