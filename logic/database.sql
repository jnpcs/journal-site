-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2018 at 05:18 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `jnpcs`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `affiliation` text COLLATE utf8_bin NOT NULL,
  `passwd` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `email`, `name`, `affiliation`, `passwd`) VALUES
(2, 'ccc@ddd.com', 'aaa', 'bbb', ''),
(3, 'ccc2@ddd.com', 'aaa', 'bbb', ''),
(4, 'ccc3@ddd.com', 'aaa', 'bbb', '');

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `paper_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `submission_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `papers`
--

INSERT INTO `papers` (`paper_id`, `account_id`, `submission_ts`, `status`) VALUES
(1, 2, '2018-03-13 17:24:16', 1),
(2, 3, '2018-03-13 17:36:45', 1),
(3, 4, '2018-03-13 17:38:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `paper_statuses`
--

CREATE TABLE `paper_statuses` (
  `status_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `paper_statuses`
--

INSERT INTO `paper_statuses` (`status_id`, `name`) VALUES
(1, 'new');

-- --------------------------------------------------------

--
-- Table structure for table `paper_variants`
--

CREATE TABLE `paper_variants` (
  `paper_var_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `submission_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` mediumtext COLLATE utf8_bin NOT NULL,
  `paper_filename` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `paper_variants`
--

INSERT INTO `paper_variants` (`paper_var_id`, `paper_id`, `submission_ts`, `title`, `paper_filename`) VALUES
(1, 3, '2018-03-13 17:38:21', 'test', '1520962701.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`paper_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `paper_statuses`
--
ALTER TABLE `paper_statuses`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `paper_variants`
--
ALTER TABLE `paper_variants`
  ADD PRIMARY KEY (`paper_var_id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `paper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paper_statuses`
--
ALTER TABLE `paper_statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paper_variants`
--
ALTER TABLE `paper_variants`
  MODIFY `paper_var_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `papers`
--
ALTER TABLE `papers`
  ADD CONSTRAINT `papers_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `papers_ibfk_2` FOREIGN KEY (`status`) REFERENCES `paper_statuses` (`status_id`);
COMMIT;
