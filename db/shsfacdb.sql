-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2024 at 02:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shsfacdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `day_sched`
--

CREATE TABLE `day_sched` (
  `day_id` int(11) NOT NULL,
  `day_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `day_sched`
--

INSERT INTO `day_sched` (`day_id`, `day_name`) VALUES
(1, 'Monday'),
(2, 'Tuesday'),
(3, 'Wednesday'),
(4, 'Thursday'),
(5, 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `facmembers`
--

CREATE TABLE `facmembers` (
  `mem_id` int(11) NOT NULL,
  `emp_no` varchar(255) NOT NULL,
  `emp_name` varchar(255) NOT NULL,
  `facpass` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `age` int(11) NOT NULL,
  `sex` varchar(255) NOT NULL,
  `cp` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL,
  `facImage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facmembers`
--

INSERT INTO `facmembers` (`mem_id`, `emp_no`, `emp_name`, `facpass`, `birthday`, `age`, `sex`, `cp`, `email`, `status`, `barangay`, `district`, `province`, `address`, `permission`, `facImage`) VALUES
(1, '123', 'M. Del Barrio', '14327e31bd98aa5ee009d375d22e83e6', '2024-10-22', 21, 'Male', '0912356789', 'delbarrio@gmail.com', 'Active', 'Sto. Tomas', '1', 'Metro Manila', 'Sto. Thomas Pasig City', 'User', 'jd.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `lvlsec`
--

CREATE TABLE `lvlsec` (
  `lvlsec_id` int(11) NOT NULL,
  `grade_lvl` varchar(255) NOT NULL,
  `grade_sec` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lvlsec`
--

INSERT INTO `lvlsec` (`lvlsec_id`, `grade_lvl`, `grade_sec`) VALUES
(1, '10', 'Argon'),
(2, '10', 'Barium'),
(3, '10', 'Copper'),
(4, '10', 'Dubnium');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `room_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_name`, `room_no`) VALUES
(1, 'RTE', 401),
(2, 'RTE', 402),
(3, 'RTE', 403),
(4, 'RTE', 301);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subj_id` int(11) NOT NULL,
  `subj_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subj_id`, `subj_name`) VALUES
(1, 'TLE'),
(2, 'VE'),
(3, 'AP'),
(4, 'Science'),
(5, 'Math'),
(6, 'English'),
(7, 'Filipino'),
(8, 'MAPEH'),
(9, 'Recess'),
(10, 'Async');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `admin_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `u_name` varchar(255) NOT NULL,
  `email_add` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `pword` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `u_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`admin_id`, `name`, `u_name`, `email_add`, `contact_no`, `pword`, `permission`, `status`, `u_image`) VALUES
(1, 'Hans Brian De Jesus', 'brnuuu', 'dejesus_hansbrian@plpasig.edu.ph', '0927556359', '65e62aac0c2b75d306003ecb0ff77716', 'Admin', 1, 'bri.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `time_sched`
--

CREATE TABLE `time_sched` (
  `time_id` int(11) NOT NULL,
  `time_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_sched`
--

INSERT INTO `time_sched` (`time_id`, `time_name`) VALUES
(1, '6:00 AM - 6:40 AM'),
(2, '6:40 AM - 7:20 AM'),
(3, '7:20 AM - 8:00 AM'),
(4, '8:00 AM - 8:40 AM'),
(5, '8:40 AM - 9:20 AM'),
(6, '9:20 AM - 9:50 AM'),
(7, '9:50 AM - 10:30 AM'),
(8, '10:30 AM - 11:10 AM'),
(9, '11:10 AM - 11:50 AM'),
(10, '11:50 AM - 12:30 PM'),
(11, '12:30 PM - 12:55 PM'),
(12, '12:55 PM - 1:20 PM');

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `log_id` int(11) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userip` binary(16) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `loginTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `logout` varchar(255) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlog`
--

INSERT INTO `userlog` (`log_id`, `userEmail`, `userip`, `status`, `username`, `name`, `loginTime`, `logout`) VALUES
(1, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 20:07:54', '0000-00-00 00:00:00'),
(2, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 20:08:47', '0000-00-00 00:00:00'),
(3, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 20:11:30', '0000-00-00 00:00:00'),
(4, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 20:13:10', '0000-00-00 00:00:00'),
(5, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 20:17:58', '0000-00-00 00:00:00'),
(6, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 20:21:55', '0000-00-00 00:00:00'),
(7, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 20:26:23', '2024-10-22 04:26:23'),
(8, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 21:04:04', '2024-10-22 05:04:04'),
(9, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnnnu', 'Potential Hacker', '2024-10-21 21:09:39', '2024-10-22 05:09:39'),
(10, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 21:11:33', '2024-10-22 05:11:33'),
(11, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-21 21:11:52', '2024-10-22 05:11:52'),
(12, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnnnu', 'Potential Hacker', '2024-10-21 21:15:07', '2024-10-22 05:15:07'),
(13, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnnnu', 'Potential Hacker', '2024-10-21 21:15:14', '2024-10-22 05:15:14'),
(14, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-21 21:18:46', '2024-10-22 05:18:46'),
(15, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-21 21:19:08', '2024-10-22 05:19:08'),
(16, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-21 21:20:49', '2024-10-22 05:20:49'),
(17, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 17:17:32', '23-10-2024 01:17:32 AM'),
(18, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 17:17:54', '23-10-2024 01:17:54 AM'),
(19, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 18:10:40', '2024-10-23 02:10:40'),
(20, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 18:24:14', '23-10-2024 02:24:14 AM'),
(21, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 18:24:24', '2024-10-23 02:24:24'),
(22, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 18:34:09', '2024-10-23 02:34:09'),
(23, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 19:00:55', '23-10-2024 03:00:55 AM'),
(24, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 19:13:51', '23-10-2024 03:13:51 AM'),
(25, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:14:13', '2024-10-23 03:14:13'),
(26, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:14:33', '2024-10-23 03:14:33'),
(27, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:17:58', '2024-10-23 03:17:58'),
(28, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:18:51', '2024-10-23 03:18:51'),
(29, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:19:19', '2024-10-23 03:19:19'),
(30, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:23:38', '2024-10-23 03:23:38'),
(31, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:24:58', '2024-10-23 03:24:58'),
(32, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:36:20', '23-10-2024 03:36:20 AM'),
(33, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 19:36:39', '23-10-2024 03:36:39 AM'),
(34, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, '123', 'Potential Hacker', '2024-10-22 19:36:45', '2024-10-23 03:36:45'),
(35, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:47:36', '23-10-2024 03:47:36 AM'),
(36, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:48:00', '23-10-2024 03:48:00 AM'),
(37, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:48:22', '23-10-2024 03:48:22 AM'),
(38, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:51:45', '23-10-2024 03:51:45 AM'),
(39, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:56:16', '23-10-2024 03:56:16 AM'),
(40, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:56:23', '2024-10-23 03:56:23'),
(41, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:56:48', '2024-10-23 03:56:48'),
(42, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 19:57:18', '2024-10-23 03:57:18'),
(43, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:05:22', '23-10-2024 04:05:22 AM'),
(44, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:05:43', '23-10-2024 04:05:43 AM'),
(45, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:09:05', '2024-10-23 04:09:05'),
(46, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:09:10', '2024-10-23 04:09:10'),
(47, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:09:13', '23-10-2024 04:09:13 AM'),
(48, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:10:51', '23-10-2024 04:10:51 AM'),
(49, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 20:11:06', '23-10-2024 04:11:06 AM'),
(50, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:13:45', '23-10-2024 04:13:45 AM'),
(51, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:13:55', '2024-10-23 04:13:55'),
(52, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:14:02', '23-10-2024 04:14:02 AM'),
(53, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 20:14:24', '23-10-2024 04:14:24 AM'),
(54, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:17:01', '23-10-2024 04:17:01 AM'),
(55, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:17:26', '23-10-2024 04:17:26 AM'),
(56, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:17:37', '23-10-2024 04:17:37 AM'),
(57, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 20:17:56', '23-10-2024 04:17:56 AM'),
(58, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-22 20:18:07', '2024-10-23 04:18:07'),
(59, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-22 20:18:18', '2024-10-23 04:18:18'),
(60, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-22 20:18:31', '2024-10-23 04:18:31'),
(61, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-22 20:18:51', '2024-10-23 04:18:51'),
(62, 'Not registered in system', 0x3a3a3100000000000000000000000000, 0, 'brnuuu', 'Potential Hacker', '2024-10-22 20:19:06', '2024-10-23 04:19:06'),
(63, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:20:00', '23-10-2024 04:20:00 AM'),
(64, 'delbarrio@gmail.com', 0x3a3a3100000000000000000000000000, 1, '123', 'M. Del Barrio', '2024-10-22 20:20:26', '23-10-2024 04:20:26 AM'),
(65, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 20:20:46', '23-10-2024 04:20:46 AM'),
(66, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-22 20:21:08', '23-10-2024 04:21:08 AM'),
(67, 'dejesus_hansbrian@plpasig.edu.ph', 0x3a3a3100000000000000000000000000, 1, 'brnuuu', 'Hans Brian De Jesus', '2024-10-23 08:22:12', '23-10-2024 04:22:12 PM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `day_sched`
--
ALTER TABLE `day_sched`
  ADD PRIMARY KEY (`day_id`);

--
-- Indexes for table `facmembers`
--
ALTER TABLE `facmembers`
  ADD PRIMARY KEY (`mem_id`);

--
-- Indexes for table `lvlsec`
--
ALTER TABLE `lvlsec`
  ADD PRIMARY KEY (`lvlsec_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subj_id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `time_sched`
--
ALTER TABLE `time_sched`
  ADD PRIMARY KEY (`time_id`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `day_sched`
--
ALTER TABLE `day_sched`
  MODIFY `day_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `facmembers`
--
ALTER TABLE `facmembers`
  MODIFY `mem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lvlsec`
--
ALTER TABLE `lvlsec`
  MODIFY `lvlsec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subj_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `admin_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `time_sched`
--
ALTER TABLE `time_sched`
  MODIFY `time_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
