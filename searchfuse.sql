-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2017 at 07:56 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `searchfuse`
--

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `taskID` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `task` text NOT NULL,
  `taskTime` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`taskID`, `uid`, `task`, `taskTime`) VALUES
(3, 2, 'Good job!!', '1489732080'),
(6, 2, 'AJA!!', '1489732704'),
(7, 2, 'AJA!!', '1489732735');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `upassword` varchar(50) NOT NULL,
  `uemail` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `uname`, `upassword`, `uemail`) VALUES
(1, 'ramilsilvano', '12b9fa2f999f61add0cc455df433c934', 'jonazodnem27@gmail.com'),
(2, 'jonazoidne', '25d55ad283aa400af464c76d713c07ad', 'jonazodnem26@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`taskID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `taskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
