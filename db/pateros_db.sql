-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 09:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pateros_dbx`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `firstname`, `lastname`, `photo`, `created_on`) VALUES
(1, 'admin', '$2y$10$fCOiMky4n5hCJx3cpsG20Od4wHtlkCLKmO6VLobJNRIg9ooHTkgjK', 'Final', 'Boss', 'PaterosLogo.png', '2025-02-17');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `status` int(1) NOT NULL,
  `time_out` time NOT NULL,
  `num_hr` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `time_in`, `status`, `time_out`, `num_hr`, `created_at`, `picture`) VALUES
(141, 45, '2025-03-22', '01:12:26', 1, '00:00:00', 0, '2025-03-21 17:12:26', 'uploads/INH879460321_1742577146.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cashadvance`
--

CREATE TABLE `cashadvance` (
  `id` int(11) NOT NULL,
  `date_advance` date NOT NULL,
  `employee_id` varchar(15) NOT NULL,
  `amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cashadvance`
--

INSERT INTO `cashadvance` (`id`, `date_advance`, `employee_id`, `amount`) VALUES
(2, '2025-02-17', '1', 1000),
(3, '2025-02-18', '1', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `deductions`
--

INSERT INTO `deductions` (`id`, `description`, `amount`) VALUES
(1, 'SSS', 100),
(2, 'Pagibig', 150),
(3, 'PhilHealth', 150);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(15) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `birthdate` date NOT NULL,
  `contact_info` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `position_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `created_on` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_changed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `firstname`, `lastname`, `address`, `birthdate`, `contact_info`, `gender`, `position_id`, `schedule_id`, `photo`, `created_on`, `email`, `password`, `password_changed`) VALUES
(4, 'JIE625973480', 'Gemalyn', 'Cepe', 'Carmen, Bohol', '1995-10-02', '09468029840', 'Female', 2, 3, 'admin.png', '2025-02-19', 'was3@gmail.com', 'was3', 1),
(42, 'HCR315947208', 'oks1', 'nah1', 'dawss', '2024-12-24', '012312312312321', 'Male', 2, 1, 'ano2.jpg', '2024-12-08', 'oksnah1@gmail.com', 'qwert', 0),
(43, 'KAR047835261', 'oks2', 'nah2', 'daw', '2024-12-24', '012312312312321', 'Male', 1, 1, 'ano3.jpg', '2024-12-08', 'oksnah2@gmail.com', 'qwertyu', 1),
(45, 'INH879460321', 'oks4', 'nah4', 'daw', '2024-12-24', '012312312312321', 'Male', 1, 1, 'ano4.jpg', '2024-12-08', 'employee@gmail.com', 'password', 1),
(48, 'XLT095126483', 'gg', 'gg', 'gg', '2025-03-18', '22', 'Male', 1, 1, 'ano2.jpg', '2025-03-02', 'gg@gmail.com', 'gg', 0),
(49, 'CPA439256108', 'kkk', 'kkk', 'kkk', '2025-03-17', '012312312312321', 'Male', 1, 1, 'image6.jpeg', '2025-03-25', 'kkk@gmail.com', 'kkk1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employeeusers`
--

CREATE TABLE `employeeusers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeeusers`
--

INSERT INTO `employeeusers` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `created_at`) VALUES
(1, 'InnocentClay', '$2y$10$OL5gcxQVgVNxuZsituvuD.scn2YFey3ZIJEV1D.0rzlhIPCm.zVLu', 'InnoC@gmail.com', 'Innocent', 'Clay', '2025-02-17 23:44:53');

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE `overtime` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(15) NOT NULL,
  `hours` double NOT NULL,
  `rate` double NOT NULL,
  `date_overtime` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `description` varchar(150) NOT NULL,
  `rate` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `description`, `rate`) VALUES
(1, 'Programmer', 100),
(2, 'Writer', 50);

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `resident_id` varchar(15) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `age` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `birthdate` date NOT NULL,
  `contact_info` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `photo` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `resident_id`, `firstname`, `lastname`, `age`, `address`, `birthdate`, `contact_info`, `gender`, `photo`, `email`, `password`, `created_on`) VALUES
(4, 'IGK216473805', 'cc', 'cc', '22', 'cc', '2025-03-05', '22', 'Female', 'ano2.jpg', 'cc@gmail.com', 'cc', '2025-03-01 16:01:22'),
(5, 'DZC267051394', 'ccss', 'ccss', '22', 'ccss', '2025-03-05', '22', 'Female', 'ano3.jpg', 'cc@gmail.com', 'cc', '2025-03-01 16:01:22'),
(9, 'SVD793184256', 'xxss', 'xxss', '1111', 'xxss', '2025-03-11', '1111', 'Female', 'ano4.jpg', 'resident@gmail.com', 'password', '2025-03-01 16:01:22'),
(11, 'JAN594722893', 'June Neil', 'Magnanao', '22', 'Pio Del Pilar', '2025-03-22', '09389771409', 'male', 'image6.jpeg', 'magnanaojuneneil@gmail.com', 'june123', '2025-03-22 05:36:11');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `time_in`, `time_out`) VALUES
(1, '07:00:00', '16:00:00'),
(2, '08:00:00', '17:00:00'),
(3, '09:00:00', '18:00:00'),
(4, '10:00:00', '19:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `resident_id` varchar(15) NOT NULL,
  `category` varchar(50) NOT NULL,
  `sub_category` varchar(50) NOT NULL,
  `reason_for_inquiry` varchar(255) NOT NULL,
  `voters_certificate` varchar(20) NOT NULL,
  `asesor_office` varchar(20) NOT NULL,
  `business_office` varchar(20) NOT NULL,
  `health_office` varchar(20) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `job_opportunities` varchar(20) NOT NULL,
  `police_clearance` varchar(20) NOT NULL,
  `dswd` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `booking_date` datetime DEFAULT NULL,
  `remarks` varchar(255) NOT NULL DEFAULT 'not serve'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id`, `resident_id`, `category`, `sub_category`, `reason_for_inquiry`, `voters_certificate`, `asesor_office`, `business_office`, `health_office`, `cedula`, `job_opportunities`, `police_clearance`, `dswd`, `created_at`, `booking_date`, `remarks`) VALUES
(1, 'BFA538906274', 'Civil Registry', 'Birth Service', 'asdasd', '', '', '', '', '', '', '', '', '2025-03-01 14:13:43', NULL, 'Not Serve'),
(2, 'IGK216473805', 'Civil Registry', '4PS', 'wew', '', '', '', '', '', '', '', '', '2025-03-01 14:45:13', NULL, 'Serve'),
(3, 'IGK216473805', 'Job Opportunities', 'Birth Service', 'swew', '', '', '', '', '', '', '', '', '2025-03-01 15:03:31', NULL, 'Serve'),
(4, 'SVD793184256', 'Civil Registry', 'Cedula', 'xx', '', '', '', '', '', '', '', '', '2025-03-01 15:58:00', NULL, 'Not Serve'),
(5, 'SVD793184256', 'DSWD', 'Voters Certificate', 'ss', '', '', '', '', '', '', '', '', '2025-03-01 17:31:26', NULL, 'Not Serve'),
(6, 'HJK226789116', 'Civil Registry', 'Feeding Program', 'wew', '', '', '', '', '', '', '', '', '2025-03-21 01:14:50', NULL, 'Serve'),
(7, 'HJK226789116', 'Civil Registry', 'Feeding Program', 'aa', '', '', '', '', '', '', '', '', '2025-03-21 01:20:59', '2025-03-21 00:00:00', 'Serve'),
(8, 'HJK226789116', 'Business Registration', 'Birth Service', 'aaa', '', '', '', '', '', '', '', '', '2025-03-21 01:21:13', '2025-03-29 00:00:00', 'Serve'),
(9, 'HJK226789116', 'Business Registration', 'Birth Service', 'ads', '', '', '', '', '', '', '', '', '2025-03-21 02:59:51', '2025-03-26 00:00:00', 'Serve'),
(10, 'HJK226789116', 'Civil Registry', 'Family Planning', 'asd', '', '', '', '', '', '', '', '', '2025-03-21 03:00:00', '2025-03-19 00:00:00', 'Serve'),
(11, 'HJK226789116', 'Civil Registry', 'Cedula', 'ads', '', '', '', '', '', '', '', '', '2025-03-21 03:00:09', '2025-03-27 00:00:00', 'Serve'),
(12, 'HJK226789116', 'Civil Registry', '4PS', 'hello po', '', '', '', '', '', '', '', '', '2025-03-21 14:21:53', '2025-03-24 00:00:00', 'Not Serve'),
(13, 'JAN594722893', 'Civil Registry', 'Tupad', 'sdsa', '', '', '', '', '', '', '', '', '2025-03-22 05:37:42', '2025-03-22 00:00:00', 'Not Serve'),
(14, 'JAN594722893', 'Civil Registry', 'Voters Certificate', 'aaff', '', '', '', '', '', '', '', '', '2025-03-22 05:41:31', '2025-03-28 17:45:00', 'Serve'),
(15, 'HJK226789116', 'DSWD', 'Tupad', 'asd', '', '', '', '', '', '', '', '', '2025-03-25 05:46:50', '2025-03-22 16:46:00', 'Serve'),
(16, 'HJK226789116', 'Civil Registry', '4PS', 'aaaaa', '', '', '', '', '', '', '', '', '2025-03-25 05:48:18', '2025-03-26 17:48:00', 'Serve'),
(17, 'HJK226789116', 'Civil Registry', 'Feeding Program', 'tr', '', '', '', '', '', '', '', '', '2025-03-25 06:21:13', '2025-03-29 16:20:00', 'Serve'),
(18, 'HJK226789116', 'Civil Registry', '4PS', 'sds', '', '', '', '', '', '', '', '', '2025-03-25 06:47:46', '2025-03-26 16:47:00', 'Not Serve');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee_id` (`employee_id`);

--
-- Indexes for table `cashadvance`
--
ALTER TABLE `cashadvance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employeeusers`
--
ALTER TABLE `employeeusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `cashadvance`
--
ALTER TABLE `cashadvance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deductions`
--
ALTER TABLE `deductions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `employeeusers`
--
ALTER TABLE `employeeusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
