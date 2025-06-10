-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 07:55 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cybertraining`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `pdf_material` varchar(255) DEFAULT NULL,
  `video_links` text DEFAULT NULL,
  `quiz_links` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `pdf_material`, `video_links`, `quiz_links`) VALUES
(1, 'sample course', 'saferf', 'vefvwerv', 'vefvverver'),
(2, 'sample course name', '1748894787_Interim Report.pdf', '[\"https:\\/\\/www.youtube.com\\/watch?v=9oHc6gWOiL4&list=RD9oHc6gWOiL4&start_radio=1\"]', '[\"https:\\/\\/www.youtube.com\\/watch?v=9oHc6gWOiL4&list=RD9oHc6gWOiL4&start_radio=1\"]'),
(3, 'Basic IT Security ', '1748896813_Project proposal (3).pdf', '[\"https:\\/\\/www.youtube.com\\/watch?v=LucTKPlRmjo\",\"https:\\/\\/www.youtube.com\\/watch?v=sAb1w_ad0y8\"]', '[]'),
(4, 'Basic Cyber Security ', '1748909714_ISEAssignment_Marking_RubricV1_2024_toStudents V3.pdf', '[\"https:\\/\\/www.youtube.com\\/watch?v=X-O1-l0gP5Q&pp=ygUmYmFzaWMgaW5mb3JtYXRpb24gYWJvdXQgY3liZXIgc2VjdXJpdHk%3D\"]', '[\"https:\\/\\/quizizz.com\\/embed\\/quiz\\/683777579d38d8c3c5c4cad5\"]'),
(5, 'HR management policy mobile security ', '1749483420_Mobile Security - IE3112.pdf', '[\"https:\\/\\/www.youtube.com\\/watch?v=Ni_tlxJuSis\"]', '[]'),
(6, 'HR management policy mobile security ', '1749483743_IE3112 -Mobile Security.pdf', '[\"https:\\/\\/www.youtube.com\\/watch?v=Ni_tlxJuSis\"]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `course_departments`
--

CREATE TABLE `course_departments` (
  `course_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_departments`
--

INSERT INTO `course_departments` (`course_id`, `department_id`) VALUES
(3, 1),
(3, 2),
(4, 2),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_code` varchar(100) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_code`, `department_name`) VALUES
(1, 'HR01', 'Human Resources'),
(2, 'HDG346', 'IT department'),
(3, 'dep12345', 'sample department');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `department_id`, `name`, `username`, `password`, `nic`, `contact_number`, `email`) VALUES
(1, 1, 'Hiranya Keshara', 'hira', '$2y$10$4XDtTKwJjvodlHuZiFHFneY4LNtiFQsh4LlPKJhrDUYrKkctgbcre', '2003545646', '0718532553', ''),
(2, 1, 'uwasara indumini', 'uwasara', '$2y$10$1e6jSMfPtO8C5.3zbXHz9OmPKz45ATWcErKqQTQtVIoDGJ1U.3HYK', '2000217', '089762412', 'uwasara45@gmail.com'),
(3, 1, 'saman kumara', 'saman', '$2y$10$7JJiRe7SNmFXkVByY3Oq9O.47IBW2b7BZyx4uOiW5JDwftEZwSP8W', '2000786544', '0714431055', 'saman@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_departments`
--
ALTER TABLE `course_departments`
  ADD PRIMARY KEY (`course_id`,`department_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_departments`
--
ALTER TABLE `course_departments`
  ADD CONSTRAINT `course_departments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
