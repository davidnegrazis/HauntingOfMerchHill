-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 16, 2015 at 01:31 AM
-- Server version: 5.5.39
-- PHP Version: 5.4.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `game`
--

-- --------------------------------------------------------

--
-- Table structure for table `game_data`
--

CREATE TABLE `game_data` (
`game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location` varchar(33) NOT NULL,
  `equipment` varchar(1000) NOT NULL,
  `sanity` varchar(1000) NOT NULL,
  `skill` varchar(20) NOT NULL,
  `current_objective` varchar(100) NOT NULL,
  `completed_objectives` varchar(1000) NOT NULL,
  `one_time_events` varchar(1000) NOT NULL,
  `companion` varchar(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
`id` int(11) NOT NULL,
  `username` varchar(33) NOT NULL,
  `password` varchar(33) NOT NULL,
  `email` varchar(33) NOT NULL,
  `d_o_r` datetime NOT NULL,
  `img_url` varchar(10000) NOT NULL,
  `hold` int(11) NOT NULL,
  `hold2` int(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `game_data`
--
ALTER TABLE `game_data`
 ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `game_data`
--
ALTER TABLE `game_data`
MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
