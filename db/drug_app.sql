-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2023 at 06:21 PM
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
-- Database: `drug_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`admin_id`, `username`, `password`) VALUES
(1, 'joke', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `session_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `session_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drug_categories`
--

CREATE TABLE `drug_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_categories`
--

INSERT INTO `drug_categories` (`category_id`, `category_name`) VALUES
(1, 'CNS Depressants'),
(2, 'CNS Stimulants'),
(3, 'Hallucinogens'),
(4, 'Dissociative Anesthetics'),
(5, 'Narcotic Analgesics'),
(6, 'Inhalants');

-- --------------------------------------------------------

--
-- Table structure for table `drug_details`
--

CREATE TABLE `drug_details` (
  `drug_id` int(11) NOT NULL,
  `drug_name` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_details`
--

INSERT INTO `drug_details` (`drug_id`, `drug_name`, `category_id`, `description`, `image_url`) VALUES
(1, 'LSD (lysergic acid diethylamide)', 3, 'LSD belongs to a group of drugs known as psychedelics. When small doses are taken, it can produce mild changes in perception, mood and thought. Larger doses may produce visual hallucinations and distortions of space and time.', 'img/lsd.jpg'),
(2, 'Nicotine', 2, 'Nicotine is a stimulant drug that speeds up the messages travelling between the brain and body.1 It is the main psychoactive ingredient in tobacco products and so this Drug Facts page will focus on the effects of nicotine when consumed by using tobacco.', 'img/tobacco.jpg'),
(3, 'Cocaine', 2, 'Cocaine is a stimulant drug. They speed up messages travelling between the brain and body.\r\n\r\nCocaine comes from the leaves of the coca bush (Erythroxylum coca), native to South America.', 'img/cocaine.jpg'),
(5, 'Caffeine', 2, 'Caffeine is a natural chemical with stimulant effects. It is found in coffee, tea, cola, cocoa, guarana, yerba mate, and over 60 other products. Caffeine works by stimulating the central nervous system, heart, muscles, and the centers that control blood pressure.', 'img/caffeinee.jpg'),
(6, 'Ketamine', 4, 'an anesthetic used medically in both humans and animals as a short-acting painkiller. Ketamine can produce dissociative sensations, feelings of euphoria, and hallucinations, and it is popular as a “club drug” among teens and young adults at dance clubs and raves.', 'img/KETAMINE.jpg'),
(8, 'Alcohol', 1, 'Alcohol, sometimes referred to by the chemical name ethanol, is a depressant drug that is the active ingredient in drinks such as beer, wine, and distilled spirits (hard liquor).\r\nIt is one of the oldest and most commonly consumed recreational drugs, causing the characteristic effects of alcohol intoxication (\"drunkenness\").\r\nAmong other effects, alcohol produces happiness and euphoria, decreased anxiety, increased sociability, sedation, impairment of cognitive, memory, motor, and sensory function, and generalized depression of central nervous system (CNS) function.', 'img/alcohol.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `session_drug_details`
--

CREATE TABLE `session_drug_details` (
  `session_id` int(11) DEFAULT NULL,
  `drug_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `drug_categories`
--
ALTER TABLE `drug_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `drug_details`
--
ALTER TABLE `drug_details`
  ADD PRIMARY KEY (`drug_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `session_drug_details`
--
ALTER TABLE `session_drug_details`
  ADD KEY `session_id` (`session_id`),
  ADD KEY `drug_id` (`drug_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drug_categories`
--
ALTER TABLE `drug_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `drug_details`
--
ALTER TABLE `drug_details`
  MODIFY `drug_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD CONSTRAINT `admin_sessions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `administrators` (`admin_id`);

--
-- Constraints for table `drug_details`
--
ALTER TABLE `drug_details`
  ADD CONSTRAINT `drug_details_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `drug_categories` (`category_id`);

--
-- Constraints for table `session_drug_details`
--
ALTER TABLE `session_drug_details`
  ADD CONSTRAINT `session_drug_details_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `admin_sessions` (`session_id`),
  ADD CONSTRAINT `session_drug_details_ibfk_2` FOREIGN KEY (`drug_id`) REFERENCES `drug_details` (`drug_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
