-- phpMyAdmin SQL Dump
-- version 3.3.7deb3
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 04 2012 г., 14:14
-- Версия сервера: 5.1.61
-- Версия PHP: 5.4.0-3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `fm5`
--

-- --------------------------------------------------------

--
-- Структура таблицы `fm_dirs`
--

CREATE TABLE IF NOT EXISTS `fm_dirs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL,
  `close` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_dirs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_dirs_chmod`
--

CREATE TABLE IF NOT EXISTS `fm_dirs_chmod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `right` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_dirs_chmod`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_fs`
--

CREATE TABLE IF NOT EXISTS `fm_fs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(64) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `pdirid` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL,
  `close` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_fs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_fs_chmod`
--

CREATE TABLE IF NOT EXISTS `fm_fs_chmod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `right` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_fs_chmod`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_fs_history`
--

CREATE TABLE IF NOT EXISTS `fm_fs_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_fs_history`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_share`
--

CREATE TABLE IF NOT EXISTS `fm_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(64) NOT NULL,
  `desc` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_share`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_text`
--

CREATE TABLE IF NOT EXISTS `fm_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `text` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `fm_text`
--


-- --------------------------------------------------------

--
-- Структура таблицы `fm_users`
--

CREATE TABLE IF NOT EXISTS `fm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `quota` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `fm_users`
--

INSERT INTO `fm_users` (`id`, `login`, `pass`, `quota`) VALUES
(1, 'filemanager', '3609b8f2a7b5b478d1f11ef8ffebbb1a', 104857600);

-- --------------------------------------------------------

--
-- Структура таблицы `fm_users_group`
--

CREATE TABLE IF NOT EXISTS `fm_users_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `fm_users_group`
--

INSERT INTO `fm_users_group` (`id`, `name`) VALUES
(1, 'Users');

-- --------------------------------------------------------

--
-- Структура таблицы `fm_users_priv`
--

CREATE TABLE IF NOT EXISTS `fm_users_priv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `group` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `fm_users_priv`
--

INSERT INTO `fm_users_priv` (`id`, `admin`, `group`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `fm_users_subgroup`
--

CREATE TABLE IF NOT EXISTS `fm_users_subgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `fm_users_subgroup`
--

INSERT INTO `fm_users_subgroup` (`id`, `pid`, `name`) VALUES
(1, 1, 'Administrators');
