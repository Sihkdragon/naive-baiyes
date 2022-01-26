-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 23, 2020 at 11:03 PM
-- Server version: 5.7.28-0ubuntu0.18.04.4
-- PHP Version: 7.2.24-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `naive-bayes`
--
CREATE DATABASE IF NOT EXISTS `naive-bayes` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `naive-bayes`;

-- --------------------------------------------------------

--
-- Table structure for table `datatraining`
--

DROP TABLE IF EXISTS `datatraining`;
CREATE TABLE `datatraining` (
  `jumlah_pegawai` varchar(20) NOT NULL,
  `luas_gedung` varchar(20) NOT NULL,
  `luas_parkir` varchar(20) NOT NULL,
  `daya_listrik` varchar(20) NOT NULL,
  `perlengkapan_yang_dimiliki` varchar(20) NOT NULL,
  `kemungkinan_disewa` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `datatraining`
--

INSERT INTO `datatraining` (`jumlah_pegawai`, `luas_gedung`, `luas_parkir`, `daya_listrik`, `perlengkapan_yang_dimiliki`, `kemungkinan_disewa`) VALUES
('sedang', 'kecil', 'kecil', 'sedang', 'sedang', 'rendah'),
('banyak', 'standar', 'besar', 'tinggi', 'tinggi', 'sedang'),
('banyak', 'kecil', 'sedang', 'tinggi', 'rendah', 'tinggi'),
('banyak', 'standar', 'sedang', 'sedang', 'tinggi', 'tinggi'),
('sedang', 'besar', 'besar', 'sedang', 'rendah', 'tinggi'),
('banyak', 'besar', 'sedang', 'sedang', 'tinggi', 'tinggi'),
('banyak', 'standar', 'besar', 'tinggi', 'sedang', 'sedang'),
('banyak', 'standar', 'besar', 'tinggi', 'sedang', 'tinggi'),
('banyak', 'besar', 'sedang', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'besar', 'sedang', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'besar', 'besar', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'kecil', 'kecil', 'tinggi', 'sedang', 'sedang'),
('banyak', 'standar', 'besar', 'tinggi', 'tinggi', 'tinggi'),
('sedikit', 'besar', 'kecil', 'tinggi', 'tinggi', 'tinggi'),
('sedang', 'standar', 'sedang', 'tinggi', 'sedang', 'tinggi'),
('banyak', 'kecil', 'sedang', 'tinggi', 'tinggi', 'sedang'),
('banyak', 'standar', 'sedang', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'standar', 'besar', 'tinggi', 'sedang', 'rendah'),
('sedang', 'standar', 'sedang', 'tinggi', 'tinggi', 'tinggi'),
('sedang', 'besar', 'besar', 'tinggi', 'sedang', 'sedang'),
('banyak', 'besar', 'besar', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'standar', 'sedang', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'besar', 'besar', 'tinggi', 'tinggi', 'tinggi'),
('sedikit', 'besar', 'besar', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'standar', 'besar', 'tinggi', 'sedang', 'tinggi'),
('banyak', 'besar', 'besar', 'tinggi', 'tinggi', 'tinggi'),
('sedang', 'besar', 'besar', 'sedang', 'tinggi', 'tinggi'),
('sedang', 'besar', 'besar', 'sedang', 'tinggi', 'tinggi'),
('sedikit', 'besar', 'besar', 'sedang', 'sedang', 'sedang'),
('sedikit', 'besar', 'besar', 'sedang', 'tinggi', 'tinggi'),
('sedang', 'besar', 'sedang', 'tinggi', 'sedang', 'tinggi'),
('banyak', 'besar', 'besar', 'tinggi', 'sedang', 'sedang'),
('sedikit', 'besar', 'sedang', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'standar', 'besar', 'tinggi', 'tinggi', 'sedang'),
('banyak', 'standar', 'sedang', 'sedang', 'sedang', 'tinggi'),
('sedikit', 'standar', 'sedang', 'tinggi', 'rendah', 'sedang'),
('banyak', 'besar', 'besar', 'tinggi', 'tinggi', 'tinggi'),
('sedikit', 'standar', 'besar', 'sedang', 'tinggi', 'sedang'),
('sedang', 'besar', 'besar', 'tinggi', 'sedang', 'tinggi'),
('banyak', 'besar', 'sedang', 'tinggi', 'tinggi', 'sedang'),
('banyak', 'kecil', 'kecil', 'rendah', 'tinggi', 'tinggi'),
('sedikit', 'besar', 'besar', 'tinggi', 'sedang', 'rendah'),
('banyak', 'besar', 'kecil', 'tinggi', 'sedang', 'tinggi'),
('banyak', 'besar', 'besar', 'sedang', 'tinggi', 'sedang'),
('banyak', 'besar', 'besar', 'sedang', 'tinggi', 'sedang'),
('sedang', 'standar', 'besar', 'tinggi', 'tinggi', 'sedang'),
('sedang', 'besar', 'sedang', 'tinggi', 'tinggi', 'tinggi'),
('banyak', 'standar', 'sedang', 'tinggi', 'sedang', 'sedang'),
('sedang', 'besar', 'besar', 'sedang', 'tinggi', 'tinggi'),
('sedang', 'standar', 'besar', 'tinggi', 'rendah', 'tinggi');

-- --------------------------------------------------------

--
-- Table structure for table `datauji`
--

DROP TABLE IF EXISTS `datauji`;
CREATE TABLE `datauji` (
  `jumlah_pegawai` varchar(20) NOT NULL,
  `luas_gedung` varchar(20) NOT NULL,
  `luas_parkir` varchar(20) NOT NULL,
  `daya_listrik` varchar(20) NOT NULL,
  `perlengkapan_yang_dimiliki` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `datauji`
--

INSERT INTO `datauji` (`jumlah_pegawai`, `luas_gedung`, `luas_parkir`, `daya_listrik`, `perlengkapan_yang_dimiliki`) VALUES
('sedikit', 'kecil', 'sedang', 'sedang', 'sedang'),
('sedikit', 'kecil', 'sedang', 'sedang', 'rendah'),
('banyak', 'besar', 'sedang', 'sedang', 'tinggi'),
('sedang', 'besar', 'besar', 'tinggi', 'tinggi'),
('sedikit', 'kecil', 'kecil', 'tinggi', 'tinggi'),
('sedikit', 'besar', 'sedang', 'sedang', 'sedang'),
('banyak', 'kecil', 'sedang', 'tinggi', 'sedang'),
('sedikit', 'kecil', 'sedang', 'rendah', 'sedang'),
('banyak', 'besar', 'kecil', 'tinggi', 'rendah'),
('sedikit', 'besar', 'besar', 'tinggi', 'tinggi');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
