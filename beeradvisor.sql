-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2022 at 03:26 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beeradvisor`
--

-- --------------------------------------------------------

--
-- Table structure for table `beer`
--

CREATE TABLE `beer` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Alcohol` int(11) NOT NULL,
  `IBU` int(11) NOT NULL,
  `Aroma` int(11) NOT NULL,
  `Style` varchar(20) NOT NULL,
  `Color` varchar(20) NOT NULL,
  `Last_modified` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `beer`
--

INSERT INTO `beer` (`ID`, `Name`, `Alcohol`, `IBU`, `Aroma`, `Style`, `Color`, `Last_modified`) VALUES
(30, 'uwu', 12, 200, 0, 'Stout', 'Black', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Beer_ID` int(11) NOT NULL,
  `Text` varchar(300) NOT NULL,
  `Grade` int(5) NOT NULL,
  `Picture` blob NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `Followed_ID` int(11) NOT NULL,
  `Follower_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` text NOT NULL,
  `Admin` tinyint(1) NOT NULL,
  `Picture` blob NOT NULL,
  `Creation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beer`
--
ALTER TABLE `beer`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `Beer_ID` (`Beer_ID`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD KEY `Followed_ID` (`Followed_ID`),
  ADD KEY `Follower_ID` (`Follower_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beer`
--
ALTER TABLE `beer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`Beer_ID`) REFERENCES `beer` (`ID`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`Followed_ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`Follower_ID`) REFERENCES `user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
