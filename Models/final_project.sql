-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2022 at 12:22 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `bookName` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `year` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='טבלה של כל הספרים בספרייה';

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`bookName`, `author`, `year`) VALUES
('A Tale of Two Cities', 'Charles Dickens', 1859),
('And Then There Were None', 'Agatha Christie', 1939),
('Black Beauty', 'Anna Sewell', 1877),
('denis123', 'Denis sobolevski', -300),
('Dream of the Red Chamber', 'Cao Xueqin', 1800),
('gffgfg', 'test', 345),
('test1', 'test32', 1),
('test2', 'test43jk', 2),
('test4', 'test543', 4),
('test5', 'test5433', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `email` varchar(70) NOT NULL,
  `type` int(1) DEFAULT NULL,
  `firstName` varchar(40) NOT NULL,
  `lastName` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='a table containing all the Users';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`email`, `type`, `firstName`, `lastName`, `password`) VALUES
('Dronelarva@gmail.com', 1, 'Denis', 'Sobolevski', '$2y$10$OdIGeR5.6pRoPC/czjjeyObl2jNkHjDxw6CNOwvdFh3fTsJ4rLRo.'),
('stam@stam.com', 0, 'stamDenis', 'stamSobolevski', '$2y$10$wrDH/a9.2lnwSgY5STLxaeO/tZS89qJIdkJK77MlMG456Js.MT496'),
('test2@test2.com', 0, 'test2', 'test2', '$2y$10$Snq/bbLEO4ZKHKoPzlvLUefdMT8PJO94NvTIBB3021G6f7LZvPtQ2'),
('test@test.com', 0, 'test', 'test', '$2y$10$/jsNVyfljCjYXo1YzBcwPOFgbGY0Y7k8b/T0kGU396htkzJalMWGi');

-- --------------------------------------------------------

--
-- Table structure for table `user_book`
--

CREATE TABLE `user_book` (
  `email` varchar(70) NOT NULL,
  `bookName` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='טבלה של מבקרים בספרייה והספרים שהם משאילים';

--
-- Dumping data for table `user_book`
--

INSERT INTO `user_book` (`email`, `bookName`, `author`) VALUES
('test@test.com', 'And Then There Were None', 'Agatha Christie'),
('test@test.com', 'denis123', 'Denis sobolevski');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`bookName`,`author`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `user_book`
--
ALTER TABLE `user_book`
  ADD PRIMARY KEY (`bookName`,`author`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
