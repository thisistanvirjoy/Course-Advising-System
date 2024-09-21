-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2024 at 04:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `course_advising_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `department` varchar(10) NOT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `credit_hours` int(11) NOT NULL,
  `prerequisites` varchar(100) DEFAULT NULL,
  `teacher_id` varchar(10) DEFAULT NULL,
  `class_time` time NOT NULL,
  `class_days` varchar(20) NOT NULL,
  `seats_available` int(11) DEFAULT NULL,
  `room_number` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `course_name`, `department`, `semester`, `credit_hours`, `prerequisites`, `teacher_id`, `class_time`, `class_days`, `seats_available`, `room_number`) VALUES
(49, 'EEE 111', 'Electronics', 'CSE', 'Autumn 2024', 2, '', 'PB', '12:30:00', 'Mon/Wed', 30, '2404'),
(50, 'CSE340', 'Computer Networks', 'CSE', 'Autumn 2024', 3, 'N/A', 'MSM', '08:00:00', 'Mon/Wed', 40, '3411'),
(51, 'CSE340L', 'Computer Networks Lab', 'CSE', 'Autumn 2024', 1, 'N/A', 'MSM', '12:30:00', 'Sun/Tue', 30, '2401'),
(52, 'CSE317', 'Compute Organization and Architecture  ', 'CSE', 'Autumn 2024', 3, 'N/A', 'AI', '12:30:00', 'Thu', 30, '2401'),
(53, 'CSE417', 'Compiler design ', 'CSE', 'Autumn 2024', 3, 'N/A', 'AI', '09:30:00', 'Mon/Wed', 30, '2404'),
(54, 'MAT224', 'Artifical Inteligence ', 'CSE', 'Autumn 2024', 3, 'N/A', 'HR', '02:00:00', 'Sun/Tue', 30, '2404'),
(55, 'CSE435', 'Computer Graphics ', 'CSE', 'Autumn 2024', 3, 'N/A', 'AI', '08:00:00', 'Sun/Tue', 30, '2301');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_code` varchar(10) NOT NULL,
  `department_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_code`, `department_name`) VALUES
(1, 'CSE', 'Computer Science and Engineering'),
(2, 'EEE', 'Electrical and Electronics Engineering'),
(3, 'BBA', 'Bachelor of Business Administration');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `student_id` varchar(10) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `advisor_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `student_id`, `course_id`, `advisor_id`, `status`) VALUES
(53, '22202005', 49, 12, 'pending'),
(60, '22202006', 49, 12, 'pending'),
(61, '22202014', 49, 13, 'accepted'),
(62, '22202014', 51, 13, 'pending'),
(63, '22202014', 52, 13, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Student'),
(2, 'Teacher'),
(3, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `student_id` varchar(10) NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `profile_completed` tinyint(1) DEFAULT 0,
  `department` varchar(10) DEFAULT NULL,
  `advisor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_id`, `profile_photo`, `profile_completed`, `department`, `advisor_id`) VALUES
(2, 23, '22202015', '', 1, 'CSE', NULL),
(3, 24, '22202013', '', 1, 'CSE', NULL),
(4, 25, '22202016', '', 1, 'CSE', 1),
(5, 26, '22202017', '', 1, 'CSE', NULL),
(9, 37, '22202005', '', 1, 'CSE', 12),
(10, 38, '22202006', '', 1, 'CSE', 12),
(11, 40, '22202014', '', 1, 'CSE', 13);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `teacher_id` varchar(10) NOT NULL,
  `department` varchar(10) DEFAULT NULL,
  `profile_completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `teacher_id`, `department`, `profile_completed`) VALUES
(12, 36, 'PB', 'EEE', 1),
(13, 39, 'HR', 'CSE', 1),
(14, 41, 'MSM', 'CSE', 1),
(15, 42, 'AI', 'CSE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`) VALUES
(2, 'Teacher1', 'Teacher1@gmail.com', '$2y$10$bYC/Ik4pUn6sJyKv8SmtWu4FeFL0ka6UPdpVDTUVAUeYZZNGQo9sC', 2),
(3, 'Manager1', 'Manager1@gmaill.com', '$2y$10$7nwKf3/QndCQK1X7nr5BZewqvYlA0fztI39LcZcVPOItuJc8/d.Oa', 3),
(9, 'Demo Student', 'demo@gmail.com', '$2y$10$QFpmGLy4Iv9Uzr3ndXlkvuENkN8u1.ohIdCI3DM6lHwDXK8InhxlO', 1),
(10, 'demo two', 'demo2@gmail.com', '$2y$10$54AXo9OYfaNB5y.BjmIa2eMc12mFBfnNMs6OSZiwjNiLsOJWb3mKq', 1),
(13, 'demo three', 'demo3@gmail.com', '$2y$10$XvUUceeDobCq/Mp/ceFj.uDKwvm.12cyFkwuqvDrpGqUzkcCC8o2O', 1),
(20, 'student', 'student@gmail.com', '$2y$10$Qk.o7V3bQJmbU/Koh/3SqOCJ9WW1Tp65fzUi/2xMBcbVaslOhFc6m', 1),
(21, 'student two', 'student2@gmail.com', '$2y$10$zEkQcVwMEnarc9GcIiHwjuAdktoy4WN/ow6ZTPlKhLVhMhyux.uAa', 1),
(22, 'Tanvir', 'tanvir@gmail.com', '$2y$10$Px4c9knWoMBX/hatR4t3uuvGDgB9MHD8aePa6TIkUmhgU86i7MaAe', 1),
(23, 'joy', 'joy@gmail.com', '$2y$10$QMFPSAvqV4qIG/K5l7e6deNQtwOK8kfmPBPTj8f25yUg6vM.vFdta', 1),
(24, 'khondaker', 'khondaker@gmail.com', '$2y$10$Sg5nR4N9v4oXSOeNJE5H/ur8h5ldwTELmaJ1u63qVTXo6IuV8KUCe', 1),
(25, 'alam', 'alam@gmail.com', '$2y$10$dEppiDhZzSXAn0fMaYHZ8.hR.v0AMj3694lPdR1unMqr5.Aa5gfLa', 1),
(26, 'Eram', 'eram@gmail.com', '$2y$10$g2zQ.loGdAqYupUkVORdr.q8nywEQ4yygemZ1NQhfTtWl1KepzK4e', 1),
(36, 'Punam ', 'punam@gmail.com', '$2y$10$yhWIMwmlIpYviCuGezJtiuycklYQ2JRGMZDs3VXhjAusl6BjFfrXe', 2),
(37, 'samah', 'samah@gmail.com', '$2y$10$s./7ONHp6HzmPMLyUBjCrufrq0YVLHvGDE6teifeAU0qzVoui5Xym', 1),
(38, 'feroz', 'feroz@gmail.com', '$2y$10$mwVBZE2K/KqiCoUmsUArmOa8iPzeODtPLT2VEXrMg1onZ6DYDSZgS', 1),
(39, 'Habibur Rahman', 'Habibur@gmail.com', '$2y$10$7sPRzu9/JiNXiwqt4nUJCeCPO.Eab3B65TxLaZ.rOhJpj3v8uZA4m', 2),
(40, 'binth', 'binth@gmail.com', '$2y$10$YwbvMfiVF1c7OsGNHUcO2.iyWKZiiIlSwXtopzLC6n6adWAT8W.6e', 1),
(41, 'Samia Muntaha', 'samia@example.com', '$2y$10$go5/IDYBjA6Tp65gNJFykOoQoEF/aJ/bG8d8MC/ZN/rQop/tv46qK', 2),
(42, 'Aseef Iqbal', 'aseef@example.com', '$2y$10$8lLk1201pGhBLutRUpQlCupdDRmukfbkCo.WzxDmDWYru9MF7LwDu', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_teacher` (`teacher_id`),
  ADD KEY `FK_course_department` (`department`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_id` (`course_id`),
  ADD KEY `fk_advisor_id` (`advisor_id`),
  ADD KEY `fk_student_id` (`student_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `FK_department` (`department`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`),
  ADD KEY `FK_department` (`department`),
  ADD KEY `fk_teachers_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `FK_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`);

--
-- Constraints for table `registration`
--
ALTER TABLE `registration`
  ADD CONSTRAINT `fk_advisor_id` FOREIGN KEY (`advisor_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `FK_department` FOREIGN KEY (`department`) REFERENCES `departments` (`department_code`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_teachers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
