-- phpMyAdmin SQL Dump
-- version 3.3.7deb3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2012 at 07:09 PM
-- Server version: 5.1.58
-- PHP Version: 5.3.9-1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fm`
--

-- --------------------------------------------------------

--
-- Table structure for table `fm_dirs`
--

CREATE TABLE IF NOT EXISTS `fm_dirs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL,
  `close` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `fm_dirs`
--

INSERT INTO `fm_dirs` (`id`, `uid`, `pid`, `name`, `close`) VALUES
(1, 1, 0, 'test', 1),
(2, 1, 0, 'Папка', 0),
(3, 2, 0, 'fd', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fm_dirs_chmod`
--

CREATE TABLE IF NOT EXISTS `fm_dirs_chmod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `right` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `fm_dirs_chmod`
--

INSERT INTO `fm_dirs_chmod` (`id`, `did`, `right`) VALUES
(1, 1, '{"frall":"true"}'),
(2, 2, '{"frall":"true"}'),
(3, 3, '{"frall":"false","fg8":"false","user1":"false","fg7":"true","user2":"false"}');

-- --------------------------------------------------------

--
-- Table structure for table `fm_fs`
--

CREATE TABLE IF NOT EXISTS `fm_fs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(64) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `pdirid` int(11) NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL,
  `close` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `fm_fs`
--

INSERT INTO `fm_fs` (`id`, `md5`, `filename`, `pdirid`, `size`, `close`) VALUES
(1, '7a26fb07adb6dd901630bfc47620e8af', 'yii-guide-ru.chm', 2, 401219, 0),
(2, 'a4150245f2515a74a810a00661f01a7a', '(13).jpg', 0, 28221, 1),
(3, '6ec552307c2d94a7241a75e9aac4c50b', '(44).jpg', 2, 471423, 0),
(4, '238a047573dee1ccd4a8482c8c3d27b0', '(69).jpg', 0, 398474, 0),
(5, 'a9fcb52c78c3af306317b1ed322d754d', '_15_.jpg', 2, 333563, 0),
(6, 'a2ead536277f84f44d866b6f5ad9ffb4', '_heart__by_blajano.jpg', 0, 612936, 0),
(7, 'e96f470162a2a43c68c3b3d09f8e029f', 'otms.zip', 0, 375129, 1),
(8, '391983e2fd34cf445d5fc5246b3cfc53', 'OTMS2.pdf', 0, 1000845, 0),
(9, '1eeb3fad3f48cdcf9099646a87a574a9', 'yii-cookbook-ru.chm.zip', 0, 51024, 1),
(10, '8a1d4ecffd5938d2427f08c41da17061', 'yii-blog-ru.chm.zip', 0, 52270, 1),
(11, '56f02e1ea53082301de57ed45d36aa25', 'yii-guide-ru.chm.zip', 0, 394462, 1),
(12, 'a5207b00058ec777b3a74b78e71ffbb8', 'yii-guide-ru.chm.zip', 0, 394462, 1),
(13, '9f7a8d9704fa9922cf4677a50f2aa1ff', 'yii-blog-ru.chm.zip', 0, 52270, 1),
(14, '71608b278c832cd8d145f19779c0583f', 'yii-cookbook-ru.chm.zip', 0, 51024, 1),
(15, '0c45294edd92bb0585cf6cb8d80326ce', 'yii-cookbook-ru.chm.zip', 0, 51024, 0),
(16, '8ee31028bc224f1e6aa762386ff92ff7', 'gavril_0.jpg', 0, 49937, 1),
(17, '381a2539960bf90366830aa6b7c237f6', 'gavril_01.jpg', 0, 80893, 0),
(18, '5fd71b7073fff661032104d3c84cf474', 'gavril_02.jpg', 0, 72414, 1),
(19, 'f49f2013c18d3331933dcb6badfc69ea', 'gavril_03.jpg', 0, 82014, 1),
(20, 'b5c535c1189881a4e5060472ee606b47', 'gavril_04.jpg', 0, 79682, 0),
(21, 'c6e10d0561a061b0ba259421afc4879f', 'gavril_05.jpg', 2, 76547, 0),
(22, 'a67ea6aea6aa671870a004d187c5c60e', 'gavril_06.jpg', 2, 90667, 0),
(23, 'f95696b8a6033c1d9327c516f1be7c93', 'gavril_07.jpg', 2, 84144, 0),
(24, '038de09d3cea2e87e6d65194aa4a1eb7', 'gavril_09.jpg', 0, 75661, 1),
(25, 'c86e44c2705dbb3826627970ba6aaa2d', 'gavril_10.jpg', 0, 91748, 1),
(26, '458242bb57e65befde4d4fb3cf989a8b', 'gavril_08.jpg', 0, 83758, 0),
(27, '7edc494bc3238ce0e28d13ee7729da7e', 'gavril_11.jpg', 2, 74109, 0),
(28, 'f4d80c822133ca9694a03629c195a2fa', 'yii-blog-ru.chm.zip', 0, 52270, 0),
(29, '4f9e1c7353fb602cafdec961df698040', 'IMAG0161.jpg', 0, 924669, 1),
(30, '4accbe499dfd4fdc4d08cc2a765c21d3', 'scroll.txt', 0, 53, 1),
(31, 'a3aec2e659eee75457d33bfc525fba76', 'scroll.txt', 0, 53, 0),
(32, 'c6ed35d39b0469b200c27eefcf05f503', '9guiLYgbzq.jpg', 0, 18583, 1),
(33, 'd81d81bf442c0351c2e503a3275872d3', 'xTbnSr4BQf.jpg', 0, 21324, 0),
(34, '5417b4ed36544f7d3132bcf1b243b4ad', 'spbgirls.htm', 0, 1087, 0),
(35, '8bcb172aa01d269eb946b1dd04750280', 'cdbxp_setup_4.3.8.2631.exe', 0, 5031888, 0),
(36, '4bd9f3fea92d1e648166036db50c0d32', 'cron.tar.bz2', 0, 4314, 0),
(37, '83f307f295927786fb0090bf8afc7ebe', 'Печать.xls', 0, 7680, 0),
(38, 'da6ca0b22e76eadf89e7bed40491468a', '02. Shiny Toy Guns - Le Disko.mp3', 0, 9724931, 0),
(39, '3a6a813b31f5ca8ad4deb38789b04582', 'ОфертаOTMS.doc', 0, 826368, 0);

-- --------------------------------------------------------

--
-- Table structure for table `fm_fs_chmod`
--

CREATE TABLE IF NOT EXISTS `fm_fs_chmod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `right` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `fm_fs_chmod`
--

INSERT INTO `fm_fs_chmod` (`id`, `fid`, `right`) VALUES
(1, 1, '{"frall":"true"}'),
(2, 2, '{"frall":"true"}'),
(3, 3, '{"frall":"true"}'),
(4, 4, '{"frall":"false","fg8":"false","user1":"false","fg7":"true","user2":"false"}'),
(5, 5, '{"frall":"true"}'),
(6, 6, '{"frall":"false","fg8":"true","user1":"false","fg7":"false","user2":"false"}'),
(7, 7, '{"frall":"true"}'),
(8, 8, '{"frall":"true"}'),
(9, 9, '{"frall":"true"}'),
(10, 10, '{"frall":"true"}'),
(11, 11, '{"frall":"true"}'),
(12, 16, '{"frall":"true"}'),
(13, 17, '{"frall":"true"}'),
(14, 18, '{"frall":"true"}'),
(15, 19, '{"frall":"true"}'),
(16, 20, '{"frall":"true"}'),
(17, 21, '{"frall":"true"}'),
(18, 22, '{"frall":"true"}'),
(19, 23, '{"frall":"true"}'),
(20, 24, '{"frall":"true"}'),
(21, 25, '{"frall":"true"}'),
(22, 26, '{"frall":"true"}'),
(23, 27, '{"frall":"true"}'),
(24, 29, '{"frall":"true"}'),
(25, 30, '{"frall":"true"}'),
(26, 32, '{"frall":"true"}'),
(27, 33, '{"frall":"true"}'),
(28, 34, '{"frall":"true"}'),
(29, 35, '{"frall":"true"}'),
(30, 36, '{"frall":"true"}'),
(31, 37, '{"frall":"true"}'),
(32, 38, '{"frall":"true"}'),
(33, 39, '{"frall":"true"}');

-- --------------------------------------------------------

--
-- Table structure for table `fm_fs_history`
--

CREATE TABLE IF NOT EXISTS `fm_fs_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `fm_fs_history`
--

INSERT INTO `fm_fs_history` (`id`, `fid`, `timestamp`, `uid`) VALUES
(1, 1, '2012-02-08 12:16:56', 1),
(2, 2, '2012-02-08 12:23:05', 1),
(3, 3, '2012-02-08 12:23:06', 1),
(4, 4, '2012-02-08 12:23:07', 1),
(5, 5, '2012-02-08 12:23:42', 1),
(6, 6, '2012-02-08 12:24:02', 1),
(7, 7, '2012-02-08 12:51:03', 1),
(8, 8, '2012-02-08 12:51:03', 1),
(9, 9, '2012-02-08 16:20:24', 2),
(10, 10, '2012-02-08 16:22:07', 2),
(11, 11, '2012-02-08 16:22:21', 2),
(12, 12, '2012-02-08 16:22:41', 2),
(13, 13, '2012-02-08 16:22:41', 2),
(14, 14, '2012-02-08 16:22:41', 2),
(15, 15, '2012-02-08 16:23:17', 2),
(16, 16, '2012-02-08 16:27:31', 2),
(17, 17, '2012-02-08 16:27:31', 2),
(18, 18, '2012-02-08 16:27:32', 2),
(19, 19, '2012-02-08 16:27:32', 2),
(20, 20, '2012-02-08 16:27:33', 2),
(21, 21, '2012-02-08 16:27:33', 2),
(22, 22, '2012-02-08 16:27:34', 2),
(23, 23, '2012-02-08 16:27:34', 2),
(24, 24, '2012-02-08 16:27:35', 2),
(25, 25, '2012-02-08 16:27:35', 2),
(26, 26, '2012-02-08 16:27:35', 2),
(27, 27, '2012-02-08 16:27:36', 2),
(28, 28, '2012-02-08 16:36:26', 2),
(29, 29, '2012-02-08 17:50:13', 1),
(30, 30, '2012-02-08 17:51:42', 1),
(31, 31, '2012-02-08 17:54:03', 1),
(32, 32, '2012-02-08 17:54:20', 1),
(33, 33, '2012-02-08 17:56:20', 1),
(34, 34, '2012-02-08 18:43:33', 1),
(35, 35, '2012-02-08 18:45:42', 1),
(36, 36, '2012-02-08 18:45:42', 1),
(37, 37, '2012-02-08 18:45:43', 1),
(38, 38, '2012-02-08 18:46:19', 1),
(39, 39, '2012-02-08 18:46:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fm_text`
--

CREATE TABLE IF NOT EXISTS `fm_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `text` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `fm_text`
--

INSERT INTO `fm_text` (`id`, `fid`, `uid`, `text`, `timestamp`) VALUES
(1, 15, 2, 'test', '2012-02-08 16:23:41'),
(2, 15, 2, 'gfd', '2012-02-08 16:23:55'),
(3, 8, 1, 'test', '2012-02-08 16:56:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `pass` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `pass`) VALUES
(1, 'admin', 'ada50a6be97dd0c700a3bb507fb6867b'),
(2, 'readonly', 'a18255c55f05ccc0a7a0bfe38ab8e8c5'),
(11, 'KRILLYA', '18d22566d9326fac9298a6a0427bf89c');

-- --------------------------------------------------------

--
-- Table structure for table `users_group`
--

CREATE TABLE IF NOT EXISTS `users_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users_group`
--

INSERT INTO `users_group` (`id`, `name`) VALUES
(3, 'Аутсорсинг'),
(1, 'Разработчик');

-- --------------------------------------------------------

--
-- Table structure for table `users_priv`
--

CREATE TABLE IF NOT EXISTS `users_priv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `group` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users_priv`
--

INSERT INTO `users_priv` (`id`, `uid`, `admin`, `group`) VALUES
(1, 1, 1, 8),
(2, 2, 0, 7),
(4, 5, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_subgroup`
--

CREATE TABLE IF NOT EXISTS `users_subgroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users_subgroup`
--

INSERT INTO `users_subgroup` (`id`, `pid`, `name`) VALUES
(1, 1, 'Разработчик'),
(2, 2, 'Рекламный отдел'),
(4, 2, 'другие OTMS'),
(7, 3, 'Программисты'),
(8, 1, 'Отдельно'),
(10, 4, 'twer');
