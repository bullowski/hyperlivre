-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 26, 2011 at 08:19 
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hyperlivre`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `books`
--


-- --------------------------------------------------------

--
-- Table structure for table `books_users`
--

DROP TABLE IF EXISTS `books_users`;
CREATE TABLE IF NOT EXISTS `books_users` (
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`book_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `books_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET ascii NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `super_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `description`, `super_id`) VALUES
(1, 'class', '', NULL),
(2, 'user', '', 1),
(3, 'book', '', 1),
(4, 'concept', '', 1),
(5, 'note', '', 1),
(6, 'comment', '', 5);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `title` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `concepts`
--

DROP TABLE IF EXISTS `concepts`;
CREATE TABLE IF NOT EXISTS `concepts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `concepts`
--


-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
CREATE TABLE IF NOT EXISTS `migration` (
  `current` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migration`
--


-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) unsigned NOT NULL,
  `book_id` int(11) NOT NULL,
  `title` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Untitled',
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `super_id` int(11) DEFAULT '1',
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `name`, `super_id`, `description`) VALUES
(1, 'property', NULL, NULL),
(2, 'a', 1, 'The rdf:type property.'),
(3, 'exemple', 1, NULL),
(4, 'definition', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `statements`
--

DROP TABLE IF EXISTS `statements`;
CREATE TABLE IF NOT EXISTS `statements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `subject_type` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `object_type` int(11) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `subject_type` (`subject_type`),
  KEY `property_id` (`property_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `statements`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `profile_fields` text COLLATE utf8_unicode_ci,
  `group` int(11) NOT NULL,
  `active_book_id` int(11) DEFAULT NULL,
  `last_login` bigint(20) DEFAULT NULL,
  `login_hash` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `profile_fields`, `group`, `active_book_id`, `last_login`, `login_hash`) VALUES
(1, 'admin', 'IkxbO8R1LtdD4DS2p50GjBZCPbnjsucz9bB4IKve51g=', 'admin@hyperlivre.ch', 'a:0:{}', 100, NULL, 1306422863, 'c44d647a3eb24d81271fda7c81a1700219493cf8'),
(2, 'user', 'IkxbO8R1LtdD4DS2p50GjBZCPbnjsucz9bB4IKve51g=', 'user@hyperlivre.ch', 'a:0:{}', 1, NULL, 1305839156, '60c6d2d405e11890da87ab1a433d7240d73077dd'),
(3, 'moderator', 'IkxbO8R1LtdD4DS2p50GjBZCPbnjsucz9bB4IKve51g=', 'moderator@hyperlivre.ch', 'a:0:{}', 50, NULL, 1306424478, '47bdd032b7e1d2da3beee6e75d228e10de9779d8');
