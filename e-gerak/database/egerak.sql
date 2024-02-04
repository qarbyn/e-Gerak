-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 04, 2024 at 04:58 AM
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
-- Database: `egerak`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `admin_id` int NOT NULL,
  `AdminUsername` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `AdminPassword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`admin_id`, `AdminUsername`, `AdminPassword`) VALUES
(4, 'ADMIN', '$2y$10$zDnXzGeoaSDltTEZFJG8s.Dp78AlpfyBTPJuiMNPEvMUsxD.yrzlC');

-- --------------------------------------------------------

--
-- Table structure for table `tblmovement`
--

CREATE TABLE `tblmovement` (
  `move_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `StaffFullName` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `StaffUnit` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StartDate` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `EndDate` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Program` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `MovementPicture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `SubmitDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tblmovement`
--

INSERT INTO `tblmovement` (`move_id`, `staff_id`, `StaffFullName`, `StaffUnit`, `StartDate`, `EndDate`, `Place`, `Program`, `MovementPicture`, `SubmitDate`) VALUES
(10, 12, 'NURUL IZYANI', NULL, '2024-02-15', '2024-02-16', 'PPDBP', 'Bengkel', 'uploads/DSC00699.jpg', '2024-02-04 02:52:08');

-- --------------------------------------------------------

--
-- Table structure for table `tblstaff`
--

CREATE TABLE `tblstaff` (
  `staff_id` int NOT NULL,
  `StaffFullName` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffNickname` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffEmail` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffPassword` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffGender` varchar(1100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffDOB` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffPhoneNo` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `StaffPicProfile` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tblstaff`
--

INSERT INTO `tblstaff` (`staff_id`, `StaffFullName`, `StaffNickname`, `StaffEmail`, `StaffPassword`, `StaffGender`, `StaffDOB`, `StaffAddress`, `StaffPhoneNo`, `StaffPicProfile`) VALUES
(9, 'QARBYN MUSYRIF', 'QARBYN', 'qarbyn@moe.gov.my', '$2y$10$hPWLoFIE3zfRjDNPMpV9mejgNtSfNzKL3nnibCtVlzxKikGn1ktu6', 'MALE', '2003-04-30', 'KUALA LUMPUR', '1234567', NULL),
(12, 'NURUL IZYANI', 'IZYANI', 'izyani@moe.gov.my', '$2y$10$uUbwHOog1Qp2L7PyGfNIzulKzsmzC9dvs.i2HobX2tcd9tnOfj0jK', 'FEMALE', '2003-12-06', 'KOTA TINGGI', '123456', 'staffpictureprofile/01.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tblmovement`
--
ALTER TABLE `tblmovement`
  ADD PRIMARY KEY (`move_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `tblstaff`
--
ALTER TABLE `tblstaff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `StaffFullName` (`StaffFullName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblmovement`
--
ALTER TABLE `tblmovement`
  MODIFY `move_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblstaff`
--
ALTER TABLE `tblstaff`
  MODIFY `staff_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
