-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 13, 2015 at 03:55 PM
-- Server version: 5.6.17-log
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `system58_elegantgarage`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `userLevel` tinyint(2) NOT NULL DEFAULT '1',
  `employeeName` varchar(30) NOT NULL,
  `working` tinyint(1) NOT NULL DEFAULT '0',
  `busy` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `username`, `email`, `password`, `salt`, `userLevel`, `employeeName`, `working`, `busy`) VALUES
(3, 'technician_1', '', '1cf68ac3d33d3270a57348a39c4ee8dca473793cff9c7da49bd910e48a8a45cc65fe6beb7897cfe0bc239e496ef61276e6ad5a0855ac98eb04eefe0481d2154c', '2e4b99b208f2841e0366f77b5841683ff003f63102f149683971e69c2a9ceb183333c8a03240ecd3a8a69a45b977bb23c456a6c83e14cde01047da426a28f3e5', 1, 'Smith Jones', 0, 0),
(4, 'technician_2', '', 'b6bc7c06a675b2eafda52ecf1026f4649c7fa2d2a3e9ccacba7adf91fcfe1832721e554903b432cfc01c2f4d9e1eaf2d9d0cb0c50261d091b3b35c2662658a9d', '348d13bd21e2125aff0aa630d7b2b4703bcc4104ffce2a509e6b42bdcb8cd2b909bfa80423ca717f10bc4f18172cbb1cdc2aa694712a2189de5a5f6f9957df71', 1, 'Bob Billy', 0, 0),
(5, 'technician_3', '', '7e90c74b137f674c4798f5d18e9196d31b4fd47c337dae9b577dd6d9660c97d8c459f403270399026d0fbd3bb30817544d1126ca6f9ab8a324da426e07810b47', '3f1b755265f135bfbe81823e2815f95b95b312b543aff0c0d2828776410ded1bd0c4eaed32300c33b005508f3799c6b0cee28605dc07b91b2f3805a16b4aca85', 1, 'Frank Derrek', 1, 0),
(6, 'technician_4', 'tech4@email.com', 'dd71a137b61ce58f2940f26a81247f814526471bd8e41f52d6f6baf5a46d8980b428c4bcbf3183efa51f0830394d813a56b273df6692fb5a6de8a0ec2fd111ff', '065c3ab4324611807130516035fb6902ec6c82cd72cd49cad9a6057009a90c18e52ae913f8934775cbdd01bc794d6a37b0f572c68f9776ff0f757947a30acd04', 1, 'Dan Ramone', 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
