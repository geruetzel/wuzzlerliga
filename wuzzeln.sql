-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 26, 2015 at 05:38 PM
-- Server version: 5.5.40
-- PHP Version: 5.4.35-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wuzzeln-git`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `player1` text NOT NULL,
  `player2` text NOT NULL,
  `score_player1` int(11) NOT NULL,
  `score_player2` int(11) NOT NULL,
  `elo_player1` double NOT NULL,
  `elo_player2` double NOT NULL,
  `ace_set1` text NOT NULL COMMENT 'Spieler, der den ersten Satz zu Null gewonnen hat',
  `ace_set2` text NOT NULL COMMENT 'Spieler, der den zweiten Satz zu Null gewonnen hat',
  `ace_set3` text NOT NULL COMMENT 'Spieler, der den dritten Satz zu Null gewonnen hat',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `games_2014`
--

CREATE TABLE IF NOT EXISTS `games_2014` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `player1` text NOT NULL,
  `player2` text NOT NULL,
  `score_player1` int(11) NOT NULL,
  `score_player2` int(11) NOT NULL,
  `elo_player1` double NOT NULL,
  `elo_player2` double NOT NULL,
  `ace_set1` text NOT NULL COMMENT 'Spieler, der den ersten Satz zu Null gewonnen hat',
  `ace_set2` text NOT NULL COMMENT 'Spieler, der den zweiten Satz zu Null gewonnen hat',
  `ace_set3` text NOT NULL COMMENT 'Spieler, der den dritten Satz zu Null gewonnen hat',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `elo_current` double NOT NULL,
  `elo_last` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `player` text NOT NULL,
  `winrate` float NOT NULL,
  `elo` double NOT NULL,
  `games_played` int(11) NOT NULL,
  `games_won` int(11) NOT NULL,
  `sets_played` int(11) NOT NULL,
  `sets_won` int(11) NOT NULL,
  `zunulls` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
