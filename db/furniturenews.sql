-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2016 at 11:49 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `furniturenews`
--

-- --------------------------------------------------------

--
-- Table structure for table `catalogvisit`
--

CREATE TABLE `catalogvisit` (
  `Id` int(11) NOT NULL,
  `VisitDateTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `IPAddress` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `catalogvisit`
--

INSERT INTO `catalogvisit` (`Id`, `VisitDateTime`, `IPAddress`) VALUES
(30, '2016-12-13 06:25:53', '180.191.118.105'),
(31, '2016-12-13 06:38:16', '180.191.118.105'),
(32, '2016-12-13 06:42:43', '180.191.118.105'),
(33, '2016-12-13 06:43:54', '180.191.118.105');

--
-- Triggers `catalogvisit`
--
DELIMITER $$
CREATE TRIGGER `catalogvisit_insert_visitdatetime` BEFORE INSERT ON `catalogvisit` FOR EACH ROW SET NEW.VisitDateTime = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pagevisit`
--

CREATE TABLE `pagevisit` (
  `Id` int(11) NOT NULL,
  `PageNumber` int(11) NOT NULL,
  `VisitDateTime` datetime DEFAULT CURRENT_TIMESTAMP,
  `CatVisitId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pagevisit`
--

INSERT INTO `pagevisit` (`Id`, `PageNumber`, `VisitDateTime`, `CatVisitId`) VALUES
(49, 1, '2016-12-13 06:25:50', 0),
(50, 2, '2016-12-13 06:25:50', 0),
(51, 1, '2016-12-13 06:25:53', 30),
(52, 2, '2016-12-13 06:25:53', 30),
(53, 3, '2016-12-13 06:26:10', 0),
(54, 4, '2016-12-13 06:26:10', 0),
(55, 1, '2016-12-13 06:38:14', 0),
(56, 2, '2016-12-13 06:38:14', 0),
(57, 1, '2016-12-13 06:38:16', 1),
(58, 2, '2016-12-13 06:38:16', 1),
(59, 3, '2016-12-13 06:39:30', 1),
(60, 4, '2016-12-13 06:39:30', 1),
(61, 5, '2016-12-13 06:39:33', 1),
(62, 6, '2016-12-13 06:39:33', 1),
(63, 1, '2016-12-13 06:42:41', 1),
(64, 2, '2016-12-13 06:42:41', 1),
(65, 1, '2016-12-13 06:42:43', 1),
(66, 2, '2016-12-13 06:42:43', 1),
(67, 1, '2016-12-13 06:43:53', 32),
(68, 2, '2016-12-13 06:43:53', 32),
(69, 1, '2016-12-13 06:43:54', 33),
(70, 2, '2016-12-13 06:43:54', 33);

--
-- Triggers `pagevisit`
--
DELIMITER $$
CREATE TRIGGER `pagevisit_insert_visitdatetime` BEFORE INSERT ON `pagevisit` FOR EACH ROW SET NEW.VisitDateTime = NOW()
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catalogvisit`
--
ALTER TABLE `catalogvisit`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `pagevisit`
--
ALTER TABLE `pagevisit`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `CatVisitId` (`CatVisitId`),
  ADD KEY `CatVisitId_2` (`CatVisitId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catalogvisit`
--
ALTER TABLE `catalogvisit`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `pagevisit`
--
ALTER TABLE `pagevisit`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
