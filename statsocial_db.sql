-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 11, 2014 at 02:26 PM
-- Server version: 5.5.34
-- PHP Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `statsocial_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `user` int(11) NOT NULL,
  `ipaddress` varchar(50) NOT NULL,
  `attempts` tinyint(1) DEFAULT NULL,
  `last_attempt` int(10) DEFAULT NULL,
  PRIMARY KEY (`user`,`ipaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `last_login` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `last_login`) VALUES
(1, 'Ditmar Commandeur', 'ditmar.commandeur@gmail.com', 'aba9474fbc1ca2f60775dea1EM83x6DVTOuhTyNZatTDoHMKGg2H6', 1394540877),
(3, 'Floris Thijssen', 'masterflappie@gmail.com', 'c1b305c9aea14f5e69b5dOYyDWjWoeSetJ.5ejdLiNvigP6FMCRbm', 1394540609),
(4, 'Coen den Engelsman', 'coendenengelsman@gmail.com', 'c98340ab30eb403792c0auPAz3Fmr718DzJAVQEBA1T7ZuuNfbEv.', 1394540788),
(5, 'Gokhan Kacan', 'gokhankacan83@gmail.com', '97cdb01333a62d764f48bufb4JFE9u0YzBE/AY7SaWzfJzGyEmIUa', 123123);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
