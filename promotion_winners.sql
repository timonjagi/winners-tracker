-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2014 at 08:05 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `promotion_winners`
--

-- --------------------------------------------------------

--
-- Table structure for table `collected`
--

CREATE TABLE IF NOT EXISTS `collected` (
  `promodate` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `promotion` varchar(20) NOT NULL,
  `showname` varchar(20) NOT NULL,
  `presenter` varchar(20) NOT NULL,
  `prize` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `idno` varchar(255) DEFAULT NULL,
  `datecollected` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `collected`
--

INSERT INTO `collected` (`promodate`, `fname`, `lname`, `promotion`, `showname`, `presenter`, `prize`, `telephone`, `idno`, `datecollected`) VALUES
('30-01-2014', 'lupe', 'fiasco', 'eabl', 'classic breakfast', 'mutoko', '394850 ', '08399482', '878787km', '30-01-2014'),
('30-01-2014', 'tony', 'montana', 'kdooncno', 'iidnfo', 'dfonma', 'ijondo', '0839488923', 'kjkkk', '30-01-2014');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `email`) VALUES
(1, 'Admin', 'b4614c16ce803e5b31f1ba49a62a8a8e14365f11d6571da66ee3428c02b7566a', '25bb42ef18a1298b', 'admin@winnerstracker.com'),
(4, 'timothy', 'dac8e0cff96f530c5b02dc30c2544ec7fcc1d131cf835ff58ae6a69d8ff2471c', '1e7c4c9438e32a97', 'misterprime@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `winnerslist`
--

CREATE TABLE IF NOT EXISTS `winnerslist` (
  `promodate` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `promotion` varchar(20) NOT NULL,
  `showname` varchar(20) NOT NULL,
  `presenter` varchar(20) NOT NULL,
  `prize` varchar(20) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `idno` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `winnerslist`
--

INSERT INTO `winnerslist` (`promodate`, `fname`, `lname`, `promotion`, `showname`, `presenter`, `prize`, `telephone`, `idno`) VALUES
('30-01-2014', 'james', 'bond', 'mi6', 'rush hr', 'caroline', 'aston martin', '007007007', ''),
('30-01-2014', 'Tomq', 'musau', 'radioo 50', 'maisha', 'kinganu', '3000 shirt', '0721733864', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
