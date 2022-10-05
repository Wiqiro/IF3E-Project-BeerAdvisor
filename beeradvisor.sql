-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2022 at 03:30 PM
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
  `Price` int(11) NOT NULL,
  `Grade` int(11) NOT NULL,
  `Taste` varchar(20) NOT NULL,
  `Style` varchar(20) NOT NULL,
  `Clarity` varchar(20) NOT NULL,
  `Last_modified` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `Nickname` varchar(20) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Admin` tinyint(1) NOT NULL,
  `Picture` blob NOT NULL,
  `Biography` varchar(300) NOT NULL,
  `Creation_date` date NOT NULL,
  `Test_count` int(11) NOT NULL
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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beer`
--
ALTER TABLE `beer`
  ADD CONSTRAINT `beer_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `comment` (`Beer_ID`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`Followed_ID`) REFERENCES `user` (`ID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `follows` (`Follower_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
