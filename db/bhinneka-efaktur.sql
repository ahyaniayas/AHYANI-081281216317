-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2022 at 02:29 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bhinneka-efaktur`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_produk`
--

CREATE TABLE `m_produk` (
  `id` varchar(5) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `satuan` varchar(10) NOT NULL,
  `harga` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_produk`
--

INSERT INTO `m_produk` (`id`, `nama`, `satuan`, `harga`) VALUES
('PR01', 'Ban', 'pcs', '450000.00'),
('PR02', 'Sasis', 'pcs', '5500000.00'),
('PR03', 'Body 3/4', 'pcs', '25560000.00'),
('PR04', 'Engine 500HP', 'set', '120500000.00');

-- --------------------------------------------------------

--
-- Table structure for table `m_supplier`
--

CREATE TABLE `m_supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `kontak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_supplier`
--

INSERT INTO `m_supplier` (`id`, `nama`, `alamat`, `kontak`) VALUES
(1, 'PT Sabar Jaya', 'Jl Raya Pantura No. 15', 'Rohmat'),
(4, 'PT Laksana Karoseri', 'Jl. Adipati Dolken', 'Suradi');

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `username` varchar(16) NOT NULL,
  `password` text NOT NULL,
  `nama` varchar(50) NOT NULL,
  `divisi` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`username`, `password`, `nama`, `divisi`) VALUES
('ahyani', 'd12ffb439055ebfc971c6aeca6e301093174e25fb354fcc237859a0ba28b7c5a3263f871aa78a48b9e47e0851caaf56ff6c56b27da014fa91e3bf24e2eb7e82913b02b7ce41c98c416972fc89c60dcda146e403402bf', 'Ahyani', 'Purchasing'),
('ayas', 'a8e45142378403da5f246dc6ee52643a6cec47e4667b75642beeaae3eaceeb181a7b81042ba7c26cd84e48625f6266d9f0d83469c4e0e64f2eba0810fb2e7726b6cc997dc6425ad7cb1f0ec4924b323723eb2906', 'Ayas', 'Purchasing'),
('jamal', '7254ba76df56fe649728914df07050ef751e460b675239d5eb6eef982838aa6359cfdae33e4271231c99cff5184610a5f6815c7772aa2fcae1f0c7e9e3ab9e6b3adf9ed2282267f585a21c17f873747b5b0ea2946c', 'Jamal Mirdad', 'Human Resource');

-- --------------------------------------------------------

--
-- Table structure for table `t_faktur_dtl`
--

CREATE TABLE `t_faktur_dtl` (
  `id` int(11) NOT NULL,
  `no` varchar(25) NOT NULL,
  `produk_id` varchar(5) NOT NULL,
  `produk_nama` varchar(25) NOT NULL,
  `produk_satuan` varchar(10) NOT NULL,
  `produk_harga` decimal(18,2) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_faktur_dtl`
--

INSERT INTO `t_faktur_dtl` (`id`, `no`, `produk_id`, `produk_nama`, `produk_satuan`, `produk_harga`, `jumlah`) VALUES
(8, '03/X/2022', 'PR01', 'Ban', 'pcs', '450000.00', 10),
(9, '03/X/2022', 'PR02', 'Sasis', 'pcs', '5500000.00', 3),
(10, '01/X/2022', 'PR03', 'Body 3/4', 'pcs', '25560000.00', 2),
(11, '01/X/2022', 'PR04', 'Engine 500HP', 'set', '120500000.00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `t_faktur_hdr`
--

CREATE TABLE `t_faktur_hdr` (
  `no` varchar(25) NOT NULL,
  `tanggal` datetime NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_nama` varchar(50) NOT NULL,
  `supplier_alamat` text NOT NULL,
  `supplier_kontak` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_faktur_hdr`
--

INSERT INTO `t_faktur_hdr` (`no`, `tanggal`, `supplier_id`, `supplier_nama`, `supplier_alamat`, `supplier_kontak`) VALUES
('01/X/2022', '2022-10-09 02:20:59', 4, 'PT Laksana Karoseri', 'Jl. Adipati Dolken', 'Suradi'),
('03/X/2022', '2022-10-08 22:57:57', 1, 'PT Sabar Jaya', 'Jl Raya Pantura No. 15', 'Rohmat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_produk`
--
ALTER TABLE `m_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_supplier`
--
ALTER TABLE `m_supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `t_faktur_dtl`
--
ALTER TABLE `t_faktur_dtl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_faktur_hdr`
--
ALTER TABLE `t_faktur_hdr`
  ADD PRIMARY KEY (`no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_supplier`
--
ALTER TABLE `m_supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_faktur_dtl`
--
ALTER TABLE `t_faktur_dtl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
