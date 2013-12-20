-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 20, 2013 at 02:24 AM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `main`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `message` text NOT NULL,
  `brings` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user`, `message`, `brings`, `date`) VALUES
(1, 3, 'This is a test', '{"food":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"drinks":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"stuff":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"friends":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}]}', '2013-12-20 08:10:28'),
(2, 3, 'This is a test', '{"food":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"drinks":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"stuff":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"friends":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}]}', '2013-12-20 08:10:28'),
(3, 3, 'This is a test', '{"food":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"drinks":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"stuff":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}],"friends":[{"qt": 5, "desc": "Something"},{"qt": 5, "desc": "Something"}]}', '2013-12-20 08:10:28');
