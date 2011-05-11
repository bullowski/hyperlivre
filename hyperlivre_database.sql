-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 11, 2011 at 06:15 
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

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(3) NOT NULL DEFAULT '0',
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
-- Table structure for table `books_concepts`
--

CREATE TABLE IF NOT EXISTS `books_concepts` (
  `book_id` int(11) NOT NULL,
  `concept_id` int(11) NOT NULL,
  UNIQUE KEY `book_concept` (`book_id`,`concept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books_concepts`
--


-- --------------------------------------------------------

--
-- Table structure for table `books_users`
--

CREATE TABLE IF NOT EXISTS `books_users` (
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  UNIQUE KEY `book_user` (`book_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET ascii NOT NULL,
  `super_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `super_id`) VALUES
(1, 'class', NULL),
(2, 'user', 1),
(3, 'book', 1),
(4, 'concept', 1),
(5, 'note', 1),
(6, 'comment', 5);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `title` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(3) NOT NULL DEFAULT '1',
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

CREATE TABLE IF NOT EXISTS `notes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) unsigned NOT NULL,
  `title` varchar(140) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Untitled',
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `notes`
--


-- --------------------------------------------------------

--
-- Table structure for table `notes_concepts`
--

CREATE TABLE IF NOT EXISTS `notes_concepts` (
  `note_id` int(11) NOT NULL,
  `concept_id` int(11) NOT NULL,
  UNIQUE KEY `note_concept` (`note_id`,`concept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes_concepts`
--


-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `super_id` int(11) DEFAULT '1',
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `name`, `super_id`, `description`) VALUES
(1, 'property', NULL, NULL),
(2, 'a', 1, 'The rdf:type property.'),
(3, 'part_of', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `statements`
--

CREATE TABLE IF NOT EXISTS `statements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `subject_type` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `object_type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `subject_type` (`subject_type`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `statements`
--

INSERT INTO `statements` (`id`, `subject_id`, `subject_type`, `property_id`, `object_id`, `object_type`) VALUES
(1, 1, 3, 2, 3, 1),
(2, 1, 5, 3, 1, 3),
(3, 1, 6, 3, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `profile_fields` text COLLATE utf8_unicode_ci,
  `group` int(11) NOT NULL,
  `last_login` bigint(20) DEFAULT NULL,
  `login_hash` tinytext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `profile_fields`, `group`, `last_login`, `login_hash`) VALUES
(1, 'admin', 'IkxbO8R1LtdD4DS2p50GjBZCPbnjsucz9bB4IKve51g=', 'admin@admin.com', 'a:0:{}', 100, 1305116432, 'd4af97807323d911989c1a3cb4c7643caa46c404'),
(4, 'user', 'IkxbO8R1LtdD4DS2p50GjBZCPbnjsucz9bB4IKve51g=', 'user@hyperlivre.ch', 'a:0:{}', 50, 1305116390, 'ffba1ea1a3ed48e493909c7032d5c3e5fe97a36e'),
(6, 'sam', 'IkxbO8R1LtdD4DS2p50GjBZCPbnjsucz9bB4IKve51g=', 'aasafdas@jxc.com', 'a:0:{}', -1, NULL, NULL),
(7, 'alex', 'IkxbO8R1LtdD4DS2p50GjBZCPbnjsucz9bB4IKve51g=', 'alex@essai.com', 'a:0:{}', 0, NULL, NULL),
(8, 'asdfada', 'YOK9Ek7wznmrCSYHA4cx/CbQ1YkC0tWq6NtV5cRy7vM=', 'afa@jdsfjsf.kld', 'a:0:{}', 50, NULL, NULL),
(9, 'space', 'vv8vK7/+mIJUK6VJYIhru208rNSmqcY3GuTLioS5omY=', 'sdfsf@dfsdf.com', 'a:0:{}', -1, NULL, NULL),
(10, 'test', 'He/nK3yxjrHiD4oM2GNJdyUTW6N2WeUy1DLUhCyRyvQ=', 'asdkbadf@kbdsfjhs.com', 'a:0:{}', 1, NULL, NULL);
