-- MySQL dump 10.13  Distrib 5.5.19, for osx10.6 (i386)
--
-- Host: localhost    Database: scrabbleclub
-- ------------------------------------------------------
-- Server version	5.5.19-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bingos`
--

DROP TABLE IF EXISTS `bingos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bingos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `player_id` int(10) unsigned NOT NULL,
  `word` varchar(15) NOT NULL DEFAULT '',
  `score` smallint(5) unsigned DEFAULT NULL,
  `is_good` BIT(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `player_id` int(10) unsigned NOT NULL,
  `opponent_id` int(10) unsigned NOT NULL,
  `player_score` smallint(6) unsigned NOT NULL DEFAULT '0',
  `opponent_score` smallint(6) unsigned NOT NULL DEFAULT '0',
  `spread` smallint(6) NOT NULL DEFAULT '0',
  `matching_game` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `opponent_id` (`opponent_id`),
  KEY `matching_game` (`matching_game`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `naspa_id` varchar(16) DEFAULT NULL,
  `naspa_rating` smallint(6) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fullname` (`lastname`(8),`firstname`(8))
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rating` smallint(5) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`),
  KEY `fk_ratings_users` (`user_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-02-11 12:34:19
