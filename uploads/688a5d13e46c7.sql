-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2025 at 03:19 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lapasan_voters`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE `voters` (
  `id` int(11) NOT NULL,
  `hhlsl` varchar(50) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `precinct_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `hhlsl`, `first_name`, `last_name`, `gender`, `birth_date`, `barangay`, `precinct_number`, `address`, `contact_number`, `created_at`, `profile_pic`) VALUES
(5, 'WK 00001', 'Daniela Jane', 'Tumilap', 'Female', '2003-02-15', 'Lapasan', '0599A', 'Western Kolambog', '09659206447', '2025-07-30 09:32:14', '6889e69eef585.jpg'),
(6, 'WK 00002', 'Ashley', 'Idago', 'Male', '2004-09-07', 'Lapasan', '0599B', '163 Western Kolambog', '09359726204', '2025-07-30 09:39:49', '6889e86506ee9.jpg'),
(7, 'SI 00001', 'Hannah Low', 'Cadalin', 'Female', '1993-06-14', 'Lapasan', '0558A', 'San Isidro Labrador', '09972618504', '2025-07-30 09:53:24', '6889edc989b43.jpg'),
(9, 'SR 00001', 'Colyn', 'Amantillo ', 'Male', '2001-04-08', 'Lapasan', '0588C', 'San Roque', '09552630857', '2025-07-30 09:58:26', '6889ecc21f35b.jpg'),
(10, 'L1 00001', 'Mary Joy', 'Tare', 'Female', '2001-07-01', 'Lapasan', '0564C', 'Lapaz Uno', '09657983214', '2025-07-30 10:06:44', '6889eeb4d4aa2.jpg'),
(11, 'LM 00001', 'John Denver', 'Lumasag', 'Male', '2005-09-16', 'Lapasan', '0585A', 'Lambago, Bayside', '09659206447', '2025-07-30 10:14:59', '6889f0a3e7177.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `voters`
--
ALTER TABLE `voters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hhlsl` (`hhlsl`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voters`
--
ALTER TABLE `voters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
