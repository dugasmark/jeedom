-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 04 Février 2014 à 16:06
-- Version du serveur: 5.5.33a-MariaDB-1~saucy-log
-- Version de PHP: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `jeedom`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(127) NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `lifetime` varchar(127) NOT NULL,
  `value` varchar(5119) DEFAULT NULL,
  `options` varchar(5119) DEFAULT NULL,
  PRIMARY KEY (`key`),
  UNIQUE KEY `key_UNIQUE` (`key`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `jeedom`.`cache_persist` (
  `key` VARCHAR(127) NOT NULL,
  `datetime` DATETIME NULL DEFAULT NULL,
  `lifetime` VARCHAR(127) NOT NULL,
  `value` VARCHAR(5119) NULL DEFAULT NULL,
  `options` VARCHAR(5119) NULL DEFAULT NULL,
  PRIMARY KEY (`key`),
  UNIQUE INDEX `key_UNIQUE` (`key` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `chatHistory`
--

CREATE TABLE IF NOT EXISTS `chatHistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `message` varchar(1023) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `cmd`
--

CREATE TABLE IF NOT EXISTS `cmd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eqLogic_id` int(11) NOT NULL,
  `order` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `configuration` text,
  `template` text,
  `isHistorized` varchar(45) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `subType` varchar(45) DEFAULT NULL,
  `cache` text,
  `unite` varchar(45) DEFAULT NULL,
  `eventOnly` tinyint(1) DEFAULT '0',
  `display` text,
  `collect` int(11) DEFAULT NULL,
  `isVisible` int(11) DEFAULT '1',
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `isHistorized` (`isHistorized`),
  KEY `type` (`type`),
  KEY `eventOnly` (`eventOnly`),
  KEY `name` (`name`),
  KEY `subtype` (`subType`),
  KEY `collect` (`collect`),
  KEY `eqLogic_id` (`eqLogic_id`),
  KEY `value` (`value`),
  KEY `order` (`order`),
  UNIQUE KEY `UNIQUE` (`eqLogic_id`, `name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `plugin` varchar(127) NOT NULL DEFAULT 'core',
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`key`,`plugin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cron`
--

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server` varchar(127) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `enable` int(11) DEFAULT NULL,
  `class` varchar(127) DEFAULT NULL,
  `function` varchar(127) NOT NULL,
  `lastrun` datetime DEFAULT NULL,
  `duration` varchar(127) DEFAULT NULL,
  `state` varchar(127) DEFAULT NULL,
  `schedule` varchar(127) DEFAULT NULL,
  `timeout` int(11) DEFAULT NULL,
  `deamon` int(11) DEFAULT '0',
  `deamonSleepTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`class`),
  KEY `logicalId_Type` (`class`)
UNIQUE KEY `class_function` (`class`, `function`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `dataStore`
--

CREATE TABLE IF NOT EXISTS `dataStore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(127) NOT NULL,
  `link_id` int(11) NOT NULL,
  `key` varchar(127) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`type`,`link_id`,`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `eqLogic`
--

CREATE TABLE IF NOT EXISTS `eqLogic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `logicalId` varchar(127) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `eqType_name` varchar(127) NOT NULL,
  `specificCapatibilities` text,
  `configuration` text,
  `isVisible` tinyint(1) DEFAULT NULL,
  `eqReal_id` int(11) DEFAULT NULL,
  `isEnable` tinyint(1) DEFAULT NULL,
  `status` text,
  `timeout` int(11) DEFAULT NULL,
  `category` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`,`object_id`),
  KEY `eqTypeName` (`eqType_name`),
  KEY `name` (`name`),
  KEY `logical_id` (`logicalId`),
  KEY `logica_id_eqTypeName` (`logicalId`,`eqType_name`),
  KEY `object_id` (`object_id`),
  KEY `eqReal_id` (`eqReal_id`),
  KEY `timeout` (`timeout`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `eqReal`
--

CREATE TABLE IF NOT EXISTS `eqReal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logicalId` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  `configuration` text,
  `cat` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `logicalId` (`logicalId`),
  KEY `type` (`type`),
  KEY `logicalId_Type` (`logicalId`,`type`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `history`
--

CREATE TABLE IF NOT EXISTS `history` (
  `cmd_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` float DEFAULT NULL,
  PRIMARY KEY (`cmd_id`,`datetime`),
  KEY `fk_history5min_commands1_idx` (`cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `historyArch`
--

CREATE TABLE IF NOT EXISTS `historyArch` (
  `cmd_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `value` float DEFAULT NULL,
  PRIMARY KEY (`cmd_id`,`datetime`),
  KEY `fk_history5min_commands1` (`cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `interactDef`
--

CREATE TABLE IF NOT EXISTS `interactDef` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enable` int(11) DEFAULT '1',
  `query` text,
  `reply` text,
  `link_type` varchar(127) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  `person` varchar(255) DEFAULT NULL,
  `options` text,
  `filtres` text,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `interactQuery`
--

CREATE TABLE IF NOT EXISTS `interactQuery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interactDef_id` int(11) NOT NULL,
  `enable` int(11) DEFAULT '1',
  `query` text,
  `link_type` varchar(127) DEFAULT NULL,
  `link_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sarahQuery_sarahDef1_idx` (`interactDef_id`),
  FULLTEXT KEY `query` (`query`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `internalEvent`
--

CREATE TABLE IF NOT EXISTS `internalEvent` (
  `datetime` datetime DEFAULT NULL,
  `event` varchar(127) DEFAULT NULL,
  `options` varchar(511) DEFAULT NULL
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `logicalId` varchar(127) DEFAULT NULL,
  `plugin` varchar(127) NOT NULL,
  `message` text,
  `action` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `object`
--

CREATE TABLE IF NOT EXISTS `object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `father_id` int(11) DEFAULT NULL,
  `isVisible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_object_object1_idx1` (`father_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenario`
--

CREATE TABLE IF NOT EXISTS `scenario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) DEFAULT NULL,
  `group` varchar(127) DEFAULT NULL,
  `state` varchar(127) DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT '1',
  `lastLaunch` datetime DEFAULT NULL,
  `mode` varchar(127) DEFAULT NULL,
  `schedule` text,
  `pid` int(11) DEFAULT NULL,
  `scenarioElement` text,
  `trigger` text,
  `log` text,
  `timeout` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `group` (`group`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioElement`
--

CREATE TABLE IF NOT EXISTS `scenarioElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioExpression`
--

CREATE TABLE IF NOT EXISTS `scenarioExpression` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioSubElement_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `subtype` varchar(127) DEFAULT NULL,
  `expression` text,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioExpression_scenarioSubElement1_idx` (`scenarioSubElement_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `scenarioSubElement`
--

CREATE TABLE IF NOT EXISTS `scenarioSubElement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order` int(11) DEFAULT NULL,
  `scenarioElement_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `subtype` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `options` text,
  `log` text,
  PRIMARY KEY (`id`),
  KEY `fk_scenarioSubElement_scenarioElement1_idx` (`scenarioElement_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `options` text,
  `hash` varchar(255) DEFAULT NULL,
  `rights` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `view`
--

CREATE TABLE IF NOT EXISTS `view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='<double-click to overwrite multiple objects>' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `viewData`
--

CREATE TABLE IF NOT EXISTS `viewData` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `viewZone_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `link_id` int(11) NOT NULL,
  `configuration` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`viewZone_id`,`link_id`,`type`),
  KEY `fk_data_zone1_idx` (`viewZone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='<double-click to overwrite multiple objects>' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `viewZone`
--

CREATE TABLE IF NOT EXISTS `viewZone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_id` int(11) NOT NULL,
  `type` varchar(127) DEFAULT NULL,
  `name` varchar(127) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `configuration` text,
  PRIMARY KEY (`id`),
  KEY `fk_zone_view1` (`view_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='<double-click to overwrite multiple objects>' AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cmd`
--
ALTER TABLE `cmd`
  ADD CONSTRAINT `fk_cmd_eqLogic1` FOREIGN KEY (`eqLogic_id`) REFERENCES `eqLogic` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `eqLogic`
--
ALTER TABLE `eqLogic`
  ADD CONSTRAINT `fk_eqLogic_jeenode1` FOREIGN KEY (`eqReal_id`) REFERENCES `eqReal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_eqLogic_object1` FOREIGN KEY (`object_id`) REFERENCES `object` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_history_cmd1` FOREIGN KEY (`cmd_id`) REFERENCES `cmd` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `historyArch`
--
ALTER TABLE `historyArch`
  ADD CONSTRAINT `fk_historyArch_cmd1` FOREIGN KEY (`cmd_id`) REFERENCES `cmd` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `object`
--
ALTER TABLE `object`
  ADD CONSTRAINT `fk_object_object1` FOREIGN KEY (`father_id`) REFERENCES `object` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `scenarioExpression`
--
ALTER TABLE `scenarioExpression`
  ADD CONSTRAINT `fk_scenarioExpression_scenarioSubElement1` FOREIGN KEY (`scenarioSubElement_id`) REFERENCES `scenarioSubElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `scenarioSubElement`
--
ALTER TABLE `scenarioSubElement`
  ADD CONSTRAINT `fk_scenarioSubElement_scenarioElement1` FOREIGN KEY (`scenarioElement_id`) REFERENCES `scenarioElement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `viewData`
--
ALTER TABLE `viewData`
  ADD CONSTRAINT `fk_data_zone1` FOREIGN KEY (`viewZone_id`) REFERENCES `viewZone` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `viewZone`
--
ALTER TABLE `viewZone`
  ADD CONSTRAINT `fk_zone_view1` FOREIGN KEY (`view_id`) REFERENCES `view` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
