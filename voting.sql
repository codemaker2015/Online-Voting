-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2016 at 08:58 PM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `leve` int(25) NOT NULL AUTO_INCREMENT,
  `password` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  PRIMARY KEY (`leve`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`leve`, `password`, `username`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE IF NOT EXISTS `candidate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `platform` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `votecount` int(11) NOT NULL DEFAULT '0',
  `sy` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`id`, `position`, `name`, `platform`, `picture`, `votecount`, `sy`) VALUES
(1, 'President', 'Rakesh', 'abc', 'RJ S. ANDRES.png', 0, '2015-2016'),
(3, 'Secretary', 'Kamlesh', 'xyz', 'Ehla.png', 1, '2015-2016'),
(4, 'Secretary', 'Rahul', 'abxcc', 'dfhdgfhgf.png', 0, '2015-2016'),
(6, 'Secretary', 'harjeet', 'abxzz', 'dfjkdskfksdjk.png', 0, '2015-2016'),
(7, 'President', 'Krishna', 'abnncc', 'majorie.png', 2, '2015-2016');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE IF NOT EXISTS `position` (
  `position` varchar(50) NOT NULL,
  `IDNo` int(11) NOT NULL AUTO_INCREMENT,
  `Limit` int(11) NOT NULL,
  PRIMARY KEY (`IDNo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`position`, `IDNo`, `Limit`) VALUES
('President', 1, 1),
('Vice President', 2, 1),
('Secretary', 3, 1),
('Sport Coordinator', 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `studid` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` varchar(10) NOT NULL,
  `sec` varchar(5) NOT NULL,
  `password` varchar(15) NOT NULL,
  `gov` varchar(10) NOT NULL,
  `vgov` varchar(10) NOT NULL,
  `congres` varchar(10) NOT NULL,
  `board` varchar(10) NOT NULL,
  `councilor` varchar(10) NOT NULL,
  `Mayor` varchar(10) NOT NULL,
  `Vice` varchar(10) NOT NULL,
  `leve` varchar(10) NOT NULL DEFAULT '2',
  `sy` varchar(15) NOT NULL,
  PRIMARY KEY (`studid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`studid`, `name`, `course`, `year`, `sec`, `password`, `gov`, `vgov`, `congres`, `board`, `councilor`, `Mayor`, `Vice`, `leve`, `sy`) VALUES
('1211', 'Krishna', 'B.tech', '4th', 'A', 'user', '', '', '', '', '', '', '', '2', '2015-2016'),
('1222', 'harjeet', 'B.tech', '4th', 'B', 'user', '', '', '', '', '', '', '', '2', '2015-2016'),
('1233', 'Dev', 'BCA', '3rd', 'D', 'user', '', '', '', '', '', '', '', '2', '2015-2016'),
('1234', 'Atul', 'B.tech', '3rd', 'A', 'user', '', '', '', '', '', '', '', '2', '2015-2016'),
('1244', 'Aryan', 'B.tech', '2nd', 'B', 'user', '', '', '', '', '', '', '', '2', '2015-2016'),
('1245', 'Rakesh', 'B.tech', '2nd', 'A', 'user', '', '', '', '', '', '', '', '2', '2015-2016'),
('1255', 'Paul', 'BCA', '3rd', 'A', 'user', '', '', '', '', '', '', '', '2', '2015-2016');

-- --------------------------------------------------------

--
-- Table structure for table `votecount`
--

CREATE TABLE IF NOT EXISTS `votecount` (
  `StudID` varchar(15) NOT NULL,
  `Position` varchar(50) NOT NULL,
  `Result` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votecount`
--

INSERT INTO `votecount` (`StudID`, `Position`, `Result`) VALUES
('1233', 'President', 1),
('1233', 'Secretary', 1),
('1245', 'President', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
