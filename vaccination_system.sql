-- phpMyAdmin SQL Dump
-- Database: `vaccination_system`
-- Clean Dump optimized for InfinityFree & Localhost Deployment

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Disable foreign key checks for clean recreation
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `tbl_appointment`;
DROP TABLE IF EXISTS `tbl_feedback`;
DROP TABLE IF EXISTS `tbl_test`;
DROP TABLE IF EXISTS `tbl_hospital`;
DROP TABLE IF EXISTS `tbl_patient`;
DROP TABLE IF EXISTS `tbl_vaccine`;
DROP TABLE IF EXISTS `tbl_city`;
DROP TABLE IF EXISTS `tbl_admin`;

-- --------------------------------------------------------

CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_admin` (`id`, `name`, `email`, `password`, `image`) VALUES
(1, 'Syed Muhammad Taha', 'taha@gmail.com', 'taha1234', 'uploads/admin/vicky-hladynets-C8Ta0gwPbQg-unsplash.jpg');

-- --------------------------------------------------------

CREATE TABLE `tbl_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_city` (`id`, `name`) VALUES
(1, 'Karachi'),
(2, 'Lahore'),
(3, 'Multan'),
(4, 'Islamabad'),
(5, 'Faisalabad');

-- --------------------------------------------------------

CREATE TABLE `tbl_hospital` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `cid` int(11) NOT NULL DEFAULT 1,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(150) NOT NULL,
  `address` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'deactivate',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_hospital` (`id`, `name`, `contact`, `cid`, `email`, `password`, `image`, `address`, `status`) VALUES
(1, 'Ziauddin Hospital', '021354789', 2, 'ziauddin@gmail.com', 'zia123', 'uploads/hospitals/abc.webp', 'ABC Street Karachi', 'activate'),
(2, 'Jinnah Hospital', '0216547893', 1, 'jinnah@gmail.com', 'jinnah123', 'uploads/hospitals/jinnah.jpg', 'Rafiqui Shaheed Road Karachi', 'activate'),
(3, 'City Hospital', '0213654789', 5, 'city@gmail.com', 'city123', 'uploads/hospitals/hhh.jpg', 'Main Boulevard Faisalabad', 'activate');

-- --------------------------------------------------------

CREATE TABLE `tbl_patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `contact` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cid` int(11) NOT NULL DEFAULT 1,
  `address` varchar(150) NOT NULL DEFAULT '',
  `gender` varchar(15) NOT NULL DEFAULT '',
  `image` varchar(150) NOT NULL DEFAULT 'uploads/patients/user.jpg',
  `status` varchar(50) NOT NULL DEFAULT 'activate',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_patient` (`id`, `name`, `contact`, `email`, `password`, `cid`, `address`, `gender`, `image`, `status`) VALUES
(1, 'Ali', '0213654789', 'ali@gmail.com', 'ali123', 3, 'Street 12 Multan', 'male', 'uploads/patients/user.jpg', 'activate'),
(2, 'Zeeshan Ahmed', '0321654789', 'zeeshan@gmail.com', 'zeeshan123', 1, 'Block 4 Clifton Karachi', 'male', 'uploads/patients/abc.webp', 'activate'),
(3, 'Hammad Ali', '0321654789', 'hammad@gmail.com', 'hammad123', 2, 'Gulberg III Lahore', 'male', 'uploads/patients/lightbulb-3104355_1920.jpg', 'activate'),
(4, 'Hamza Khan', '0321645789', 'hamza@gmail.com', '123', 3, 'Cantt Area Multan', 'male', 'uploads/patients/unnamed.ico', 'activate'),
(5, 'Syed Muhammad Taha', '03032843872', 'taha@gmail.com', '123', 1, 'Gulshan-e-Iqbal Karachi', 'male', 'uploads/patients/vicky-hladynets-C8Ta0gwPbQg-unsplash.jpg', 'activate');

-- --------------------------------------------------------

CREATE TABLE `tbl_vaccine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'available',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_vaccine` (`id`, `name`, `status`) VALUES
(1, 'Sinovac', 'available'),
(2, 'Sinopharm', 'available'),
(3, 'Pfizer-BioNTech', 'available'),
(4, 'Moderna', 'available');

-- --------------------------------------------------------

CREATE TABLE `tbl_appointment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `h_id` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `v_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_appointment` (`id`, `p_id`, `h_id`, `date`, `time`, `v_id`, `status`) VALUES
(1, 5, 2, '2026-11-25', '1-3', 1, 'pending');

-- --------------------------------------------------------

CREATE TABLE `tbl_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `message` varchar(300) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'hide',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_feedback` (`id`, `p_id`, `message`, `status`) VALUES
(1, 2, 'The vaccination scheduling service was seamless and very well organized.', 'show'),
(2, 1, 'Received prompt appointment confirmation and quick testing service.', 'show'),
(3, 3, 'Very convenient portal for checking reports and hospital locations.', 'show');

-- --------------------------------------------------------

CREATE TABLE `tbl_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `h_id` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  `result` varchar(50) NOT NULL DEFAULT 'Process',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_test` (`id`, `p_id`, `h_id`, `date`, `result`) VALUES
(1, 1, 2, '2026-11-20', 'Negative');

-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
