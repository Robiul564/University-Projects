-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 25, 2025 at 06:42 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `organet`
--

-- --------------------------------------------------------

--
-- Table structure for table `assigned_dev`
--

CREATE TABLE `assigned_dev` (
  `Project_ID` bigint NOT NULL,
  `User_ID` bigint NOT NULL,
  `assigned_as` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `assigned_dev`
--

INSERT INTO `assigned_dev` (`Project_ID`, `User_ID`, `assigned_as`) VALUES
(23, 25, 'Project Manager'),
(23, 26, 'Developer'),
(23, 27, 'Developer'),
(24, 28, 'Project Manager'),
(24, 29, 'Developer'),
(24, 30, 'Developer'),
(25, 25, 'Developer'),
(25, 26, 'Project Manager'),
(25, 29, 'Developer'),
(25, 30, 'Developer'),
(25, 33, 'Developer'),
(25, 34, 'Developer'),
(26, 25, 'Developer'),
(26, 26, 'Developer'),
(26, 28, 'Developer'),
(26, 29, 'Project Manager'),
(26, 30, 'Developer'),
(27, 31, 'Project Manager'),
(27, 32, 'Developer'),
(27, 33, 'Developer'),
(27, 34, 'Developer'),
(28, 25, 'Project Manager'),
(28, 26, 'Developer'),
(28, 27, 'Developer'),
(28, 28, 'Developer'),
(28, 29, 'Developer'),
(28, 30, 'Developer'),
(28, 31, 'Developer'),
(28, 32, 'Developer'),
(28, 33, 'Developer'),
(28, 34, 'Developer'),
(29, 25, 'Developer'),
(29, 26, 'Developer'),
(29, 27, 'Project Manager'),
(30, 25, 'Developer'),
(30, 26, 'Developer'),
(30, 27, 'Project Manager'),
(30, 28, 'Developer'),
(30, 29, 'Developer'),
(31, 25, 'Project Manager'),
(31, 26, 'Developer'),
(31, 27, 'Developer'),
(31, 28, 'Developer');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `notifiable_id` bigint DEFAULT NULL,
  `data` varchar(255) NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` bigint NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Start_Date` date DEFAULT NULL,
  `End_Date` date DEFAULT NULL,
  `Priority` int DEFAULT NULL,
  `Project_Description` varchar(400) NOT NULL,
  `is_Active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `Name`, `Start_Date`, `End_Date`, `Priority`, `Project_Description`, `is_Active`) VALUES
(23, '1st project', '2025-01-15', '2025-04-15', 3, 'test                121', 0),
(24, '2nd project', '2025-02-15', '2025-03-15', 2, 'test 2', 0),
(25, '3rd project ..', '2024-12-18', '2025-07-31', 1, 'test 3 on going', 1),
(26, 'project 4', '2024-12-16', '2025-01-17', 1, 'test 4 has done', 0),
(27, 'web programming is ongoing', '2025-01-06', '2025-03-28', 2, 'bla bla bla bla bla.//....', 0),
(28, 'cody the dog', '2024-12-26', '2025-07-31', 3, 'cody is a good doggo', 0),
(29, 'medium project ', '2025-01-07', '2025-01-31', 2, 'do do do do do do do', 1),
(30, 'Test project 1', '2025-01-12', '2025-04-17', 2, '100%', 1),
(31, 'project show', '2025-01-05', '2025-08-01', 2, 'test one two', 0);

-- --------------------------------------------------------

--
-- Table structure for table `referal`
--

CREATE TABLE `referal` (
  `user_id` bigint NOT NULL,
  `referral_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `User_ID` bigint NOT NULL,
  `Assigned` bigint DEFAULT NULL,
  `Role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subtask`
--

CREATE TABLE `subtask` (
  `id` bigint NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Assigned_Dev` bigint DEFAULT NULL,
  `Start_Date` date DEFAULT NULL,
  `End_Date` date DEFAULT NULL,
  `Timeline_Missed` tinyint(1) DEFAULT NULL,
  `Complete_Date` date DEFAULT NULL,
  `Flag` tinyint(1) DEFAULT NULL,
  `Tag` varchar(255) DEFAULT NULL,
  `Project_ID` bigint NOT NULL,
  `Task_ID` bigint NOT NULL,
  `comment` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `Name` varchar(255) NOT NULL,
  `Color` varchar(255) DEFAULT NULL,
  `Priority_Level` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` bigint NOT NULL,
  `Project_ID` int NOT NULL,
  `Task_Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `task_done` tinyint(1) DEFAULT NULL,
  `Start_Date` date DEFAULT NULL,
  `Complete_Date` date DEFAULT NULL,
  `Description` text,
  `Assigned_user` int NOT NULL,
  `comment` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `Project_ID`, `Task_Name`, `task_done`, `Start_Date`, `Complete_Date`, `Description`, `Assigned_user`, `comment`) VALUES
(29, 23, '25% project update', 0, '2025-01-15', '2025-01-19', 'user login and reg and crud operations', 25, '[null, {\"comment\": \"nigga nigaa        \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"      fgfg  \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"     dfdf   \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"        \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"        \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"        \", \"user_id\": 25, \"project_id\": 23}]'),
(30, 23, '25% project update', 0, '2025-01-15', '2025-01-19', 'user login and reg and crud operations', 26, 'null'),
(31, 23, '25% project update', 0, '2025-01-15', '2025-01-19', 'user login and reg and crud operations', 27, 'null'),
(32, 23, 'database table design', 2, '2025-01-20', '2025-01-31', 'create all the table in the database', 25, '[null, {\"comment\": \"hey hey hey\", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"        \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"heyyyyyyyyyyyyyyyyyyy       \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"        \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"        \", \"user_id\": 25, \"project_id\": 23}, {\"comment\": \"dsfsd\", \"user_id\": 25, \"project_id\": 23}]'),
(33, 23, 'design the frontend', 0, '2025-01-23', '2025-01-24', 'use react others', 26, 'null'),
(34, 23, 'merge all', 0, '2025-02-14', '2025-02-18', 'merge all the tasks', 27, 'null'),
(35, 26, '50% working progress', 0, '2025-01-15', '2025-01-17', 'do the database design , schema, and front end ,&amp;nbsp; you can use all the available tools', 30, 'null'),
(36, 26, '50% working progress', 1, '2025-01-15', '2025-01-17', 'do the database design , schema, and front end ,&amp;nbsp; you can use all the available tools', 26, 'null'),
(37, 26, '50% working progress', 0, '2025-01-15', '2025-01-17', 'do the database design , schema, and front end ,&amp;nbsp; you can use all the available tools', 28, 'null'),
(38, 26, '50% working progress', 1, '2025-01-15', '2025-01-17', 'do the database design , schema, and front end ,&amp;nbsp; you can use all the available tools', 25, '[null, {\"comment\": \"the project is done\", \"user_id\": 25, \"project_id\": 26}]'),
(39, 26, 'test task ', 0, '2025-01-14', '2025-01-17', 'test', 26, 'null'),
(40, 26, 'test task ', 1, '2025-01-14', '2025-01-17', 'test', 25, '[null, {\"comment\": \"sadfasdfdsafdasf\", \"user_id\": 25, \"project_id\": 26}, {\"comment\": \"        \", \"user_id\": 25, \"project_id\": 26}]'),
(41, 25, 'task 2 test', 0, '2025-01-06', '2025-01-31', '...........//........', 27, 'null'),
(42, 25, 'task 2 test', 0, '2025-01-06', '2025-01-31', '...........//....bla bla bla....', 29, 'null'),
(43, 25, 'task 2', 0, '2025-01-07', '2025-01-23', '........//////...........lorem bla bla', 28, 'null'),
(44, 25, 'task 2', 0, '2025-01-07', '2025-01-23', '........//////...........', 30, 'null'),
(45, 28, '25% should be done within the deadline', 0, '2025-01-09', '2025-03-20', 'divide the work with all', 29, 'null'),
(46, 28, '25% should be done within the deadline', 0, '2025-01-09', '2025-03-20', 'divide the work with all', 32, 'null'),
(47, 28, '25% should be done within the deadline', 0, '2025-01-09', '2025-03-20', 'divide the work with all', 30, 'null'),
(48, 28, '25% should be done within the deadline', 0, '2025-01-09', '2025-03-20', 'divide the work with all', 31, 'null'),
(49, 28, '25% should be done within the deadline', 0, '2025-01-09', '2025-03-20', 'divide the work with all', 34, 'null'),
(50, 30, 'test11', 0, '2025-01-20', '2025-01-30', '1111', 28, NULL),
(51, 30, 'test11', 2, '2025-01-20', '2025-01-30', '1111', 25, '[{\"comment\": \"        sds\", \"user_id\": 25, \"project_id\": 30}]'),
(52, 30, 'another task', 0, '2025-01-15', '2025-01-29', '&lt;font color=&quot;#ffffff&quot;&gt;&lt;span style=&quot;background-color: rgb(0, 0, 0);&quot;&gt;the new task&lt;/span&gt;&lt;/font&gt;', 29, NULL),
(53, 30, 'another task', 0, '2025-01-15', '2025-01-29', '&lt;font color=&quot;#ffffff&quot;&gt;&lt;span style=&quot;background-color: rgb(0, 0, 0);&quot;&gt;the new task&lt;/span&gt;&lt;/font&gt;', 26, NULL),
(54, 30, 'another task', 0, '2025-01-15', '2025-01-29', '&lt;font color=&quot;#ffffff&quot;&gt;&lt;span style=&quot;background-color: rgb(0, 0, 0);&quot;&gt;the new task&lt;/span&gt;&lt;/font&gt;', 28, NULL),
(55, 30, 'another task', 0, '2025-01-15', '2025-01-29', '&lt;font color=&quot;#ffffff&quot;&gt;&lt;span style=&quot;background-color: rgb(0, 0, 0);&quot;&gt;the new task&lt;/span&gt;&lt;/font&gt;', 25, NULL),
(56, 31, 'test case 11', 2, '2025-01-13', '2025-03-12', '1111111sdf', 25, '[{\"comment\": \"now its inprogress\", \"user_id\": 25, \"project_id\": 31}]'),
(57, 31, 'test case 11', 0, '2025-01-13', '2025-03-12', '1111111sdf', 26, NULL),
(58, 31, 'test case 11', 0, '2025-01-13', '2025-03-12', '1111111sdf', 27, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` bigint NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`id`, `Name`, `Email`, `Password`, `Picture`) VALUES
(19, 'admin', 'admin@g.com', '$2y$10$CceJKZUVRhNuRTGlx.Qque1Z/qGkmpIlmr.R2aDF9p8oCYpSf.6D2', 'assets/img/admin.png'),
(25, 'mark', 'mark@g.com', '$2y$10$3tJLEL9n8piPTp5Jpbehyewj.6yXhvbGQuWsxuZ.DNA3Vu7y7fcEW', 'assets/img/coding.png'),
(26, 'arko', 'arko@g.com', '$2y$10$3NybacQOwshdiCwJuxjPde3KWpbf.7VBYG8EtE/sQT1h4YvFhu9ay', 'assets/img/coding.png'),
(27, 'bro', 'bro@g.com', '$2y$10$LrddXvjqkGcz05iYUObB4.8heeEA3jQw0g47FeiFQrm/mR0NXAVIO', 'assets/img/coding.png'),
(28, 'khalid', 'khalid@g.com', '$2y$10$94NFvCAt/mPDEVslyFr4BemQtkL0PzBE5KRLabHaAVK5G3xX1yO7G', 'assets/img/coding.png'),
(29, 'alif', 'alif@g.com', '$2y$10$b0bWnFe0O6oNTmqwCOP8NOXkIt9GJF65.F1hGpuMncM/AZXke361u', 'assets/img/coding.png'),
(30, 'mac', 'mac@g.com', '$2y$10$RUQotFApwty9zJHXyP5BB.LH8zl23jhgFd7CHXj.pJRNGJ5xMqgJu', 'assets/img/coding.png'),
(31, 'saida', 'saida@g.com', '$2y$10$ySCsZ2C7xsWV1xB8dpEhs.E2ClvRqA6T.6GenRlU13E8vCeEiTJs.', 'assets/img/coding.png'),
(32, 'anika', 'anika@g.com', '$2y$10$lAmHa/U276EuwjsjLOzcnO/ZFLcERwAeY3QNAXs30sKXIvnVb5be6', 'assets/img/coding.png'),
(33, 'iffat', 'iffat@g.com', '$2y$10$jrgAU/7a0Oq1gQohCzAa0uG7X4AHgIofmsvnaOURD314xYUOK.u8y', 'assets/img/coding.png'),
(34, 'shuvo', 'shuvo@g.com', '$2y$10$WXwRfxx8fqEAuE4oaJaMTu4MQEzzroWGlaW6d6pNxyhb3iuo57Oou', 'assets/img/coding.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assigned_dev`
--
ALTER TABLE `assigned_dev`
  ADD PRIMARY KEY (`Project_ID`,`User_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referal`
--
ALTER TABLE `referal`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `referral_id` (`referral_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `subtask`
--
ALTER TABLE `subtask`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Assigned_Dev` (`Assigned_Dev`),
  ADD KEY `Tag` (`Tag`),
  ADD KEY `Project_ID` (`Project_ID`),
  ADD KEY `Task_ID` (`Task_ID`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`Name`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `subtask`
--
ALTER TABLE `subtask`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_dev`
--
ALTER TABLE `assigned_dev`
  ADD CONSTRAINT `assigned_dev_ibfk_1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `assigned_dev_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `user_type` (`id`);

--
-- Constraints for table `referal`
--
ALTER TABLE `referal`
  ADD CONSTRAINT `referal_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_type` (`id`),
  ADD CONSTRAINT `referal_ibfk_2` FOREIGN KEY (`referral_id`) REFERENCES `user_type` (`id`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `user_type` (`id`);

--
-- Constraints for table `subtask`
--
ALTER TABLE `subtask`
  ADD CONSTRAINT `subtask_ibfk_1` FOREIGN KEY (`Assigned_Dev`) REFERENCES `user_type` (`id`),
  ADD CONSTRAINT `subtask_ibfk_2` FOREIGN KEY (`Tag`) REFERENCES `tag` (`Name`),
  ADD CONSTRAINT `subtask_ibfk_3` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `subtask_ibfk_4` FOREIGN KEY (`Task_ID`) REFERENCES `task` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
