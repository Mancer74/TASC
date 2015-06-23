-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2015 at 07:56 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tascdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `designs`
--

CREATE TABLE IF NOT EXISTS `designs` (
  `PollID` int(11) NOT NULL,
  `DesignID` int(11) NOT NULL,
  `DesignName` varchar(45) NOT NULL,
  `DesignDescription` mediumtext,
  `InnovationTOT` float NOT NULL DEFAULT '0',
  `FeasibilityTOT` float NOT NULL DEFAULT '0',
  `WordList` mediumtext NOT NULL,
  `FileName` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
`PollID` int(11) NOT NULL,
  `AdminID` varchar(10) NOT NULL,
  `UserID` varchar(10) NOT NULL,
  `DateOfLA` date NOT NULL,
  `Title` varchar(45) NOT NULL,
  `Description` mediumtext,
  `AdminEmail` varchar(45) NOT NULL,
  `AdminName` varchar(45) NOT NULL,
  `NumPart` int(11) NOT NULL DEFAULT '0',
  `PartNames` mediumtext,
  `Closed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`EntryID` int(11) NOT NULL,
  `PollID` int(11) NOT NULL,
  `DesignID` int(11) NOT NULL,
  `UserName` varchar(45) DEFAULT NULL,
  `Words` mediumtext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE IF NOT EXISTS `words` (
  `WordID` int(11) NOT NULL,
  `InnovationS` float NOT NULL,
  `FeasabilityS` float NOT NULL,
  `StrRep` char(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `words`
--

INSERT INTO `words` (`WordID`, `InnovationS`, `FeasabilityS`, `StrRep`) VALUES
(1, 0.33, 0.395, 'Accessible'),
(2, 0.463, 0.31, 'Advanced'),
(3, -0.251, -0.273, 'Busy'),
(4, 0.298, 0.294, 'Clean'),
(5, 0.406, 0.436, 'Clear'),
(6, 0.305, 0.262, 'Compatible'),
(7, -0.399, -0.327, 'Complex'),
(8, 0.499, 0.296, 'Comprehnesive'),
(9, -0.387, -0.444, 'Confusing'),
(10, 0.133, 0.188, 'Connected'),
(11, 0.435, 0.464, 'Convienient'),
(12, 0.566, 0.321, 'Creative'),
(13, -0.394, -0.514, 'Difficult'),
(14, 0.444, 0.434, 'Effective'),
(15, 0.511, 0.461, 'Efficient'),
(16, 0.435, 0.328, 'Exciting'),
(17, -0.189, -0.294, 'Expected'),
(18, -0.453, -0.396, 'Familiar'),
(19, -0.362, -0.389, 'Fragile'),
(20, 0.215, 0.202, 'Fun'),
(21, 0.35, 0.418, 'Helpful'),
(22, -0.38, -0.45, 'Inconsistant'),
(23, -0.298, -0.446, 'Ineffective'),
(24, 1, 0.367, 'Innovative'),
(25, 0.089, 0.075, 'Inviting'),
(26, -0.284, -0.462, 'Irrelevant'),
(27, -0.307, -0.267, 'Ordinary'),
(28, 0.381, 0.315, 'Powerful'),
(29, -0.406, -0.452, 'Predictable'),
(30, 0.472, 0.429, 'Relevant'),
(31, 0.504, 0.47, 'Reliable'),
(32, 0.309, 0.37, 'Satisfying'),
(33, 0.573, 0.324, 'Unconventional'),
(34, -0.341, -0.367, 'Undesireable'),
(35, 0.334, 0.383, 'Usable'),
(36, 0.514, 0.495, 'Useful');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `designs`
--
ALTER TABLE `designs`
 ADD PRIMARY KEY (`PollID`,`DesignID`), ADD KEY `PollID_idx` (`PollID`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
 ADD PRIMARY KEY (`PollID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`EntryID`), ADD KEY `UPollFK` (`PollID`);

--
-- Indexes for table `words`
--
ALTER TABLE `words`
 ADD PRIMARY KEY (`WordID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
MODIFY `PollID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `EntryID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `designs`
--
ALTER TABLE `designs`
ADD CONSTRAINT `PollFK` FOREIGN KEY (`PollID`) REFERENCES `polls` (`PollID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `UPollFK` FOREIGN KEY (`PollID`) REFERENCES `polls` (`PollID`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
