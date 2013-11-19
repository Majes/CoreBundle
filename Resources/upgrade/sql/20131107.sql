ALTER TABLE  `role` ADD  `internal` INT NOT NULL DEFAULT  '0' AFTER  `bundle`;
ALTER TABLE  `role` CHANGE  `role`  `role` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 08 Novembre 2013 à 15:30
-- Version du serveur: 5.1.66
-- Version de PHP: 5.3.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `sf2`
--

-- --------------------------------------------------------

--
-- Structure de la table `cms_page_role`
--

CREATE TABLE IF NOT EXISTS `cms_page_role` (
  `page_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`page_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
