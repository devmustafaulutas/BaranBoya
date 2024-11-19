-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2022 at 12:30 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `truvaayv_scriptdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `updated_at`) VALUES
(1, 'admin', 'admin', '2022-07-13 11:00:19');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `urunler`
--
-- Ana kategoriler tablosu
-- Ana kategoriler tablosu
-- Ana kategoriler tablosu

CREATE TABLE `kategoriler` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(255) NOT NULL,
    `resim` VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4;

-- Alt kategoriler tablosu, ana kategorilere bağlı
CREATE TABLE `alt_kategoriler` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(255) NOT NULL,
    `resim` VARCHAR(255) NOT NULL,
    `kategori_id` INT,
    FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler`(`id`) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

-- Alt kategorilerin alt kategorileri, alt kategorilere bağlı
CREATE TABLE `alt_kategoriler_alt` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(255) NOT NULL,
    `resim` VARCHAR(255) NOT NULL,
    `alt_kategori_id` INT,
    FOREIGN KEY (`alt_kategori_id`) REFERENCES `alt_kategoriler`(`id`) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

-- Ürünler tablosu, alt kategorilere bağlı
CREATE TABLE `urunler` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(100) NOT NULL,
    `aciklama` TEXT,
    `fiyat` DECIMAL(10, 2) NOT NULL,
    `stok` INT DEFAULT 0,
    `resim` VARCHAR(255) NOT NULL,
    `kategori_id` INT DEFAULT NULL,
    `alt_kategori_id`  INT DEFAULT NULL,
    `alt_kategori_alt_id` INT DEFAULT NULL,
    FOREIGN KEY (`alt_kategori_id`) REFERENCES `alt_kategoriler`(`id`) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

INSERT INTO `kategoriler` (`isim`,`resim`) VALUES

('Reçineler','category.png'),
('Jelkotlar','category.png'),
('Cam Elyaflar','category.png'),
('Kalıp Ayırıcılar','category.png'),
('Dolgu Malzemeleri','category.png'),
('Renklendiriciler','category.png'),
('Sarf Malzemeleri','category.png');

INSERT INTO `alt_kategoriler` (`isim`, `kategori_id`, `resim`) VALUES
('Polyester Reçineler', 1, 'assets/img/categorys/YAT 1.jpeg'),1
('Vinilester Reçineler', 1, 'category.png'),2
('Epoksi Reçineler', 1, 'category.png'),3
('Poliüretan Reçine', 1, 'category.png'),4
('Reçine Yardımcıları', 1, 'category.png')5,

('Genel Amaçlı Jelkotlar', 2, 'category.png'),6
('Performans Jelkotlar', 2, 'category.png'),7
('Yüksek Performanslı Jelkotlar', 2, 'category.png'),8
('Zımparalanabilir Jelkotlar', 2, 'category.png'),9
('Kalıplama Jelkotları', 2, 'category.png'),10
('Marin Uygulama Jelkotları', 2, 'category.png'),11
('Kimyasal Dayanımlı Jelkotlar', 2, 'category.png'),12
('Alev İlerletmeyen Jelkotlar', 2, 'category.png'),13

('Cam Elyaf Keçeler', 3, 'category.png'),14
('Cam Elyaf Dokuma Kumaşlar', 3, 'category.png'),15
('Karbon Elyaf Dokuma Kumaşlar', 3, 'category.png'),16
('Fitil İp Elyaflar', 3, 'category.png'),17
('Kırpık Elyaflar', 3, 'category.png'),18
('Yüzey Tülü (Kortel)', 3, 'category.png'),19
('Corematlar', 3, 'category.png'),20

('Vaks Kalıp Ayırıcılar', 4, 'category.png'),21
('Sprey Kalıp Ayırıcılar', 4, 'category.png'),22
('Sıvı Kalıp Ayırıcılar', 4, 'category.png'),23

('Kalsitler', 5, 'category.png'),24
('Talk Pudrası', 5, 'category.png'),25
('Aerosil', 5, 'category.png'),26
('Çinko Streat', 5, 'category.png'),27
('Alüminyum Tozu', 5, 'category.png'),28
('Alev Geciktirici Dolgu', 5, 'category.png'),29

('Polyester ve Poliüretan Renklendirici Pigmentler', 6, 'category.png'),30
('Titandioksitler', 6, 'category.png'),31
('Epoksi Reçine Pigmentler', 6, 'category.png'),32
('RTV-2 Kalıp Silikonu Pigmentleri', 6, 'category.png'),33

('Fırçalar ve Rulolar', 7, 'category.png'),34
('Tulumlar ve Maskeler', 7, 'category.png'),35
('Jelkot Tabancaları', 7, 'category.png'),36
('Zımpara Makineleri ve Zımparalar', 7, 'category.png'),37
('Polyester Ezme Rulolar', 7, 'category.png'),38
('Bantlar', 7, 'category.png'),39
('Ölçüm ve Dozajlama Ekipmanları', 7, 'category.png');40


INSERT INTO `alt_kategoriler_alt` (`isim`, `alt_kategori_id`) VALUES
('Döküm Tipi Polyester Reçineler', 1),1
('Breton Tipi Polyester Reçineler', 1),2
('Kalıplama Polyester Reçineler', 1),3
('Genel Amaçlı (Elyaf Tip) Polyester Reçineler', 1),4
('CTP Tipi (Fitil Sarma) Polyester Reçineler', 1),5
('RTM/İnfüzyon Tipi Polyester Reçineler', 1),6
('Akrilik Tipi Polyester Reçineler', 1),7
('SMC/BMC Tipi Polyester Reçineler', 1),8
('Düğme Tipi Polyester Reçineler', 1),9
('Kimyasal Dayanımlı Polyester Reçineler', 1),10  
('Alev İlerletmeyen Polyester Reçineler', 1), 11
('Köpük Eritmeyen Polyester Reçineler', 1), 12
('Köpük Eritmeyen Polyester Reçineler', 1), 13

('Bisfenol-A Vinilester Reçineler', 2), 14
('Novolak Vinilester Reçineler', 2), 15
('Bromine Vinilester Reçineler', 2), 16
('Amin Hızlandırıcılı Vinilester Reçineler', 2), 17
('Poliüretan Reçine', 2), 18
('Epoksi Reçineler', 2); 19


-- Ürünler için INSERT sorguları
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Turkuaz TP100 Döküm Tipi Polyester', 'Döküm Tipi Polyester Reçine', 100.00, 50, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 1),
('Camelyaf Resins CE80 Döküm Tipi Polyester', 'Döküm Tipi Polyester Reçine', 120.00, 30, 'assets/img/products/CAMELYAF 18 KG.png', 1, 1, 1),
('Poliya Polipol 3453 Döküm Tipi Polyester', 'Döküm Tipi Polyester Reçine', 110.00, 40, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 1),
('Turkuaz TP182 Kuvars Kompoze Taş Polyester', 'Breton Tipi Polyester Reçine', 140.00, 20, 'resim4.jpg', 1, 2, 2),
('Poliya Polipol 357 Kuvars Kompoze Taş Polyester', 'Breton Tipi Polyester Reçine', 150.00, 25, 'resim5.jpg', 1, 2, 2),
('Turkuaz TP220 (Tİ-CO) Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 130.00, 10, 'resim6.jpg', 1, 4, 4),
('Turkuaz TP200 (Tİ-CO) Genel Amaçlı Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 135.00, 15, 'resim7.jpg', 1, 4, 4),
('Camelyaf Resins CE92 N8 Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 125.00, 10, 'resim8.jpg', 1, 4, 4),
('Poliya Polipol 3404 (TA-LSE-H20/40/60/100) Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 140.00, 8, 'resim9.jpg', 1, 4, 4),
('Poliya Polipol 3401 (TA-LSE-H20/40/60/100) Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 140.00, 8, 'resim9.jpg', 1, 4, 4),
('Poliya Polipol 320TA Kalıp Yapımı Polyester', 'Kalıplama Polyester Reçine', 160.00, 5, 'resim10.jpg', 1, 3, 3),
('Poliya Polipol 321-Zero Kalıp Yapımı Polyester', 'Kalıplama Polyester Reçine', 165.00, 4, 'resim11.jpg', 1, 3, 3),
('Turkuaz TP 1040 CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 170.00, 12, 'resim12.jpg', 1, 5, 5),
('Turkuaz TP 1082 CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 170.00, 12, 'resim12.jpg', 1, 5, 5),
('Poliya Polipol 3562 CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 175.00, 10, 'resim13.jpg', 1, 5, 5),
('Poliya Polipol 3872-F CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 175.00, 10, 'resim13.jpg', 1, 5, 5),
('Turkuaz TP 260 RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 180.00, 7, 'resim14.jpg', 1, 6, 6),
('Turkuaz TP 911-CO RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 180.00, 7, 'resim14.jpg', 1, 6, 6),
('Poliya Polipol 3387 Zero RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 185.00, 6, 'resim15.jpg', 1, 6, 6),
('Poliya Polipol 3382 RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 185.00, 6, 'resim15.jpg', 1, 6, 6),
('Turkuaz TP 1300 Akrilik Tipi Dolgusuz Polyester', 'Akrilik Tipi Polyester Reçine', 190.00, 3, 'resim16.jpg', 1, 7, 7),
('Poliya Polipol 341 Akrilik Tipi Dolgulu Polyester', 'Akrilik Tipi Polyester Reçine', 195.00, 2, 'resim17.jpg', 1, 7, 7),
('Poliya Polipol 341-TA Akrilik Tipi Dolgulusuz Polyester', 'Akrilik Tipi Polyester Reçine', 195.00, 2, 'resim17.jpg', 1, 7, 7),
('Poliya Polipol 342-TA D40 Dolgulu Polyester', 'Akrilik Tipi Polyester Reçine', 195.00, 2, 'resim17.jpg', 1, 7, 7),
('Turkuaz TP 535 SMC/BMC Tipi Polyester', 'SMC/BMC Tipi Polyester Reçine', 200.00, 5, 'resim18.jpg', 1, 8, 8),
('Turkuaz TP 500 BMC Tipi Polyester', 'BMC Tipi Polyester Reçine', 200.00, 5, 'resim18.jpg', 1, 8, 8),
('Poliya Polipol 3417-V SMC/BMC Tipi Polyester', 'SMC/BMC Tipi Polyester Reçine', 200.00, 5, 'resim18.jpg', 1, 8, 8),
('Poliya Polipol 382 SMC/BMC Tipi Kimyasal Dayanımlı Polyester', 'SMC/BMC Tipi Kimyasal Dayanımlı Polyester Reçine', 210.00, 4, 'resim19.jpg', 1, 8, 8),
('Poliya Polipol 3418 SMC/BMC Tipi Tam Maleik Polyester', 'SMC/BMC Tipi Kimyasal Dayanımlı Polyester Reçine', 210.00, 4, 'resim19.jpg', 1, 8, 8),
('Turkuaz TP 400 (C) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 220.00, 6, 'resim20.jpg', 1, 9, 9),
('Turkuaz TP 409 (C) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 220.00, 6, 'resim20.jpg', 1, 9, 9),
('Poliya Polipol 3541 (T) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 230.00, 3, 'resim21.jpg', 1, 9, 9),
('Poliya Polipol 3542 (T) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 230.00, 3, 'resim21.jpg', 1, 9, 9);

-- Alev İlerletmeyen Polyester Reçineler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polipol 345-FR', 'Alev İlerletmeyen Polyester Reçine', 150.00, 100, 'polipol_345_fr.jpg', 1, 1, 11),
('Boytek BRE 400 Stratford Köpüğü Eritmeyen Polyester', 'Köpüğü Eritmeyen Polyester Reçine', 120.00, 200, 'boytek_bre_400.jpg', 1, 1, 12),
('Poliya Polipol 3515 Polistiren Köpüğü Eritmeyen Polyester', 'Polistiren Köpüğü Eritmeyen Polyester Reçine', 130.00, 150, 'polipol_3515.jpg', 1, 1, 12),
('İlkalem İlkester P1020 Cila Tipi Polyester', 'Cila Tipi Polyester Reçine', 110.00, 80, 'ilkester_p1020.jpg', 1, 1, 13);

-- Vinilester Reçineler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polives 701 Yüksek Kimyasal Dayanımlı Performans Vinilester', 'Yüksek Kimyasal Dayanımlı Performans Vinilester', 180.00, 100, 'polives_701.jpg', 2, 1, 14),
('Poliya Polives 702 RTM/İnfüzyon Tipi Vinilester', 'RTM/İnfüzyon Tipi Vinilester', 170.00, 150, 'polives_702.jpg', 2, 1, 14),
('Poliya Polives 711 Yüksek Kimyasal Dayanımlı Yüksek Performans Vinilester', 'Yüksek Performans Vinilester', 190.00, 80, 'polives_711.jpg', 2, 1, 14),
('Poliya Polives 709 Pultrüzyon Tipi Vinilester', 'Pultrüzyon Tipi Vinilester', 160.00, 120, 'polives_709.jpg', 2, 1, 14),
('Poliya Polives 710 RTM Alev İlerletmeyen Vinilester', 'Alev İlerletmeyen RTM Vinilester', 200.00, 50, 'polives_710.jpg', 2, 1, 14),
('Poliya Polives 721 Novolak Vinilester', 'Novolak Vinilester', 210.00, 70, 'polives_721.jpg', 2, 2, 15),
('Poliya Polives 710 RTM Alev İlerletmeyen Vinilester', 'Alev İlerletmeyen RTM Vinilester', 200.00, 50, 'polives_710_rtm.jpg', 2, 2, 16),
('Poliya Polives 710-I RTM/İnfüzyon Alev İlerletmeyen Vinilester', 'RTM/İnfüzyon Alev İlerletmeyen Vinilester', 220.00, 40, 'polives_710i.jpg', 2, 2, 16),
('Poliya Polives 710-TA-LSE Alev İlerletmeyen ve Tiksotropik Düşük Stiren İçeren Vinilester', 'Tiksotropik Düşük Stiren İçeren Vinilester', 230.00, 60, 'polives_710_ta_lse.jpg', 2, 2, 16),
('Poliya Polives 701-ABP Amin Hızlandırıcılı Yüksek Kimyasal Dayanımlı Vinilester', 'Amin Hızlandırıcılı Yüksek Kimyasal Dayanımlı Vinilester', 240.00, 55, 'polives_701_abp.jpg', 2, 2, 17),
('Poliya Polives 711-ABP Amin Hızlandırıcılı Yüksek Kimyasal Dayanımlı Yüksek Performans Vinilester', 'Amin Hızlandırıcılı Yüksek Performans Vinilester', 250.00, 45, 'polives_711_abp.jpg', 2, 2, 17);

-- Poliüretan Reçine
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Polipol ve İzosiyanat Poliüretan Reçine Sıvı Plastik', 'Sıvı Plastik Poliüretan Reçine', 300.00, 30, 'polipol_izosiyanat.jpg', 3, 1, 18);

-- Epoksi Reçineler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('EpoXs İnce Döküm (Hediyelik) Tipi Epoksi', 'İnce Döküm (Hediyelik) Tipi Epoksi', 50.00, 200, 'epoxs_ince.jpg', 4, 1, 19),
('EpoXs Kalın Döküm (Masa) Tipi Epoksi', 'Kalın Döküm (Masa) Tipi Epoksi', 120.00, 150, 'epoxs_kalin.jpg', 4, 1, 19),
('EpoXs Teşbih Yapım Epoksisi', 'Teşbih Yapım Epoksisi', 80.00, 180, 'epoxs_tesbih.jpg', 4, 1, 19),
('EpoXs Laminasyon (Kompozit) Tipi Epoksi', 'Laminasyon Tipi Epoksi', 110.00, 100, 'epoxs_laminasyon.jpg', 4, 1, 19);


-- Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polipol 3610 Jelkot', 'Genel Amaçlı Jelkot', 190.00, 18, 'resim15.jpg', 2, 1, 1),
('Turkuaz TP 1088 Jelkot', 'Genel Amaçlı Jelkot', 185.00, 12, 'resim16.jpg', 2, 1, 1),
('Turkuaz TP 2090 Performans Jelkot', 'Performans Jelkot', 220.00, 10, 'resim17.jpg', 2, 2, 2),
('Poliya Polipol 3615 Yüksek Performanslı Jelkot', 'Yüksek Performanslı Jelkot', 250.00, 8, 'resim18.jpg', 2, 3, 3),
('Poliya Polipol 3625 Zımparalanabilir Jelkot', 'Zımparalanabilir Jelkot', 240.00, 15, 'resim19.jpg', 2, 4, 4),
('Poliya Polipol 3630 Kalıplama Jelkot', 'Kalıplama Jelkot', 230.00, 20, 'resim20.jpg', 2, 5, 5),
('Turkuaz TP 2085 Marin Uygulama Jelkot', 'Marin Uygulama Jelkot', 270.00, 12, 'resim21.jpg', 2, 6, 6),
('Turkuaz TP 2120 Kimyasal Dayanımlı Jelkot', 'Kimyasal Dayanımlı Jelkot', 280.00, 10, 'resim22.jpg', 2, 7, 7),
('Turkuaz TP 2505 Alev İlerletmeyen Jelkot', 'Alev İlerletmeyen Jelkot', 300.00, 5, 'resim23.jpg', 2, 8, 8),

-- Cam Elyaflar
('Camelyaf 100 Keçe', 'Cam Elyaf Keçe', 150.00, 100, 'resim24.jpg', 3, 1, 1),
('Camelyaf 200 Keçe', 'Cam Elyaf Keçe', 160.00, 80, 'resim25.jpg', 3, 1, 1),
('Camelyaf Dokuma Kumaş 500g', 'Cam Elyaf Dokuma Kumaş', 170.00, 70, 'resim26.jpg', 3, 2, 2),
('Camelyaf Dokuma Kumaş 800g', 'Cam Elyaf Dokuma Kumaş', 180.00, 60, 'resim27.jpg', 3, 2, 2),
('Karbon Elyaf 300g', 'Karbon Elyaf Dokuma Kumaş', 220.00, 50, 'resim28.jpg', 3, 3, 3),
('Karbon Elyaf 500g', 'Karbon Elyaf Dokuma Kumaş', 240.00, 40, 'resim29.jpg', 3, 3, 3),
('Fitil İp Elyaf', 'Fitil İp Elyaflar', 200.00, 30, 'resim30.jpg', 3, 4, 4),
('Kırpık Elyaf', 'Kırpık Elyaflar', 190.00, 25, 'resim31.jpg', 3, 5, 5),
('Yüzey Tülü (Kortel)', 'Yüzey Tülü', 210.00, 20, 'resim32.jpg', 3, 6, 6),
('Coremat 2mm', 'Coremat', 250.00, 15, 'resim33.jpg', 3, 7, 7),
('Coremat 3mm', 'Coremat', 270.00, 10, 'resim34.jpg', 3, 7, 7),

-- Kalıp Ayırıcılar
('Vaks Kalıp Ayırıcı Sprey', 'Kalıp Ayırıcı', 90.00, 50, 'resim35.jpg', 4, 1, 1),
('Sprey Kalıp Ayırıcı', 'Sprey Kalıp Ayırıcı', 100.00, 60, 'resim36.jpg', 4, 2, 2),
('Sıvı Kalıp Ayırıcı', 'Sıvı Kalıp Ayırıcı', 110.00, 40, 'resim37.jpg', 4, 3, 3),

-- Dolgu Malzemeleri
('Kalsit 10kg', 'Kalsit', 50.00, 120, 'resim38.jpg', 5, 1, 1),
('Talk Pudrası 5kg', 'Talk Pudrası', 40.00, 130, 'resim39.jpg', 5, 2, 2),
('Aerosil 20kg', 'Aerosil', 60.00, 110, 'resim40.jpg', 5, 3, 3),
('Alev Geciktirici Dolgu 15kg', 'Alev Geciktirici Dolgu', 70.00, 100, 'resim41.jpg', 5, 4, 4),
('Çinko Streat 25kg', 'Çinko Streat', 80.00, 90, 'resim42.jpg', 5, 5, 5),
('Alüminyum Tozu 10kg', 'Alüminyum Tozu', 120.00, 50, 'resim43.jpg', 5, 6, 6),

-- Renklendiriciler
('Polyester Pigment 1kg', 'Polyester Pigment', 15.00, 150, 'resim44.jpg', 6, 1, 1),
('Epoksi Renklendirici 500g', 'Epoksi Renklendirici', 25.00, 140, 'resim45.jpg', 6, 2, 2),
('Silikon Pigment 1kg', 'Silikon Pigment', 30.00, 130, 'resim46.jpg', 6, 3, 3),

-- Sarf Malzemeleri
('Fırça 100mm', 'Fırça', 8.00, 200, 'resim47.jpg', 7, 1, 1),
('Fırça 200mm', 'Fırça', 10.00, 180, 'resim48.jpg', 7, 1, 1),
('Tulum 1 adet', 'Tulum', 20.00, 150, 'resim49.jpg', 7, 2, 2),
('Tulum 5 adet', 'Tulum', 90.00, 140, 'resim50.jpg', 7, 2, 2),
('Zımpara Makinesi', 'Zımpara Makinesi', 250.00, 80, 'resim51.jpg', 7, 3, 3),
('Zımpara Kağıdı', 'Zımpara Kağıdı', 5.00, 300, 'resim52.jpg', 7, 4, 4),
('Bant 50mm', 'Bant', 3.00, 350, 'resim53.jpg', 7, 5, 5),
('Bant 100mm', 'Bant', 5.00, 320, 'resim54.jpg', 7, 5, 5),
('Dozajlama Ekipmanı', 'Dozajlama Ekipmanı', 60.00, 120, 'resim55.jpg', 7, 6, 6);


-- --------------------------------------------------------


CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `blog_title` varchar(300) NOT NULL,
  `blog_desc` varchar(300) NOT NULL,
  `blog_detail` varchar(2000) NOT NULL,
  `ufile` varchar(1000) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (
    `id`, 
    `blog_title`, 
    `blog_desc`, 
    `blog_detail`, 
    `ufile`, 
    `updated_at`
) 
VALUES
(
    1, 
    'We provide the best digital services', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem.', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem est deserunt molestias accusamus harum ullam tempore debitis et, expedita, repellat delectus aspernatur neque itaque qui quod.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem est deserunt molestias accusamus harum ullam tempore debitis et, expedita, repellat delectus aspernatur neque itaque qui quod.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem est deserunt molestias accusamus harum ullam tempore debitis et, expedita, repellat delectus aspernatur neque itaque qui quod.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem est deserunt molestias accusamus harum ullam tempore debitis et, expedita, repellat delectus aspernatur neque itaque qui quod.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem est deserunt molestias accusamus harum ullam tempore debitis et, expedita, repellat delectus aspernatur neque itaque qui quod.',
    '', 
    '2022-07-15 12:47:45'
),
(
    2, 
    'We provide the best digital services', 
    'We provide the best digital servicesWe provide the best digital servicesWe provide the best digital services', 
    'We provide the best digital servicesWe provide the best digital servicesWe provide the best digital servicesWe provide the best digital servicesWe provide the best digital servicesWe provide the best digital servicesWe provide the best digital services', 
    '60936059d354562031616499540.png', 
    '2022-07-16 05:49:44'
);

-- --------------------------------------------------------

--
-- Table structure for table `logo`
--

CREATE TABLE `logo` (
  `id` int(11) NOT NULL,
  `xfile` varchar(1000) NOT NULL,
  `ufile` varchar(1000) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logo`
--

INSERT INTO `logo` (`id`, `xfile`, `ufile`, `updated_at`) VALUES
(1, '5122favicon.png', '5122favicon.png', '2024-11-01 16:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `id` int(11) NOT NULL,
  `port_title` varchar(500) NOT NULL,
  `port_desc` varchar(1000) NOT NULL,
  `port_detail` varchar(2000) NOT NULL,
  `ufile` varchar(1000) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (
    `id`, 
    `port_title`, 
    `port_desc`, 
    `port_detail`, 
    `ufile`, 
    `updated_at`
) 
VALUES
(
    3, 
    'App Development', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit.', 
    '926070de04f0-df57-11ec-85a8-bda8f2c6ca77-rimg-w720-h720-gmir.jpg', 
    '2022-07-18 14:48:54'
);

-- --------------------------------------------------------

--
-- Table structure for table `section_title`
--

CREATE TABLE `section_title` (
  `id` int(11) NOT NULL,
  `about_title` varchar(500) NOT NULL,
  `about_text` varchar(1000) NOT NULL,
  `why_title` varchar(500) NOT NULL,
  `why_text` varchar(1000) NOT NULL,
  `service_title` varchar(500) NOT NULL,
  `service_text` varchar(1000) NOT NULL,
  `port_title` varchar(500) NOT NULL,
  `port_text` varchar(1000) NOT NULL,
  `test_title` varchar(500) NOT NULL,
  `test_text` varchar(1000) NOT NULL,
  `contact_title` varchar(500) NOT NULL,
  `contact_text` varchar(1000) NOT NULL,
  `enquiry_title` varchar(500) NOT NULL,
  `enquiry_text` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section_title`
--
INSERT INTO `section_title` (
    `id`, `about_title`, `about_text`, 
    `why_title`, `why_text`, `service_title`, 
    `service_text`, `port_title`, `port_text`, 
    `test_title`, `test_text`, `contact_title`, 
    `contact_text`, `enquiry_title`, `enquiry_text`
) 
VALUES
(
    1, 
    'We help to grow your business.', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.', 
    'Work smarter, not harder.', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.', 
    'Hizmetlerimiz', 
    '', 
    'Geçmiş Çalışmalarımız', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.', 
    'Our clients says', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.', 
    'Bize Ulaşın!', 
    'Sorularınızı ve fikirlerinizi bizimle paylaşın.', 
    'Looking for the best digital agency & marketing solution?', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.'
);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `service_title` varchar(500) NOT NULL,
  `service_desc` varchar(1000) NOT NULL,
  `service_detail` varchar(2000) NOT NULL,
  `ufile` varchar(1000) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `upadated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (
    `id`, 
    `service_title`, 
    `service_desc`, 
    `service_detail`, 
    `icon`,
    `ufile`, 
    `upadated_at`
) 

VALUES
(
    2, 
    '81 İLE HIZLI TESLİMAT', 
    '',
    '',
    '<i class="material-icons">local_shipping</i>',
    '5645portrait-happy-young-black-woman-posing-office_116547-21539.webp', 
    '2022-07-17 21:19:37'
),
(
    3, 
    'TEKNİK DESTEK', 
    '',
    '',
    '<i class="material-icons">build</i>', 
    '9668788-7884680_hero-headshot-sitting-hd-png-download.jpg', 
    '2022-07-17 21:20:13'
),
(
    4, 
    'YÜKSEK ÜRÜN KALİTESİ', 
    '',
    '',    
    '<i class="material-icons">percent</i>', 
    '648Eternity.jpg', 
    '2022-07-17 21:20:46'
);

-- --------------------------------------------------------

--
-- Table structure for table `siteconfig`
--

CREATE TABLE `siteconfig` (
  `id` int(11) NOT NULL,
  `site_keyword` varchar(1000) NOT NULL,
  `site_desc` varchar(500) NOT NULL,
  `site_title` varchar(300) NOT NULL,
  `site_about` varchar(1000) NOT NULL,
  `site_footer` varchar(1000) NOT NULL,
  `follow_text` varchar(1000) NOT NULL,
  `site_url` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `siteconfig`
--

INSERT INTO `siteconfig` (
    `id`, 
    `site_keyword`, 
    `site_desc`, 
    `site_title`, 
    `site_about`, 
    `site_footer`, 
    `follow_text`, 
    `site_url`, 
    `updated_at`
) 
VALUES
(
    1, 
    'Church, Marketing', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem est deserunt molestias accusamus harum ullam tempore debitis et, expedita, repellat delectus aspernatur neque itaque qui quod.', 
    'Vogue Website', 
    'Young coders can use events to coordinate timing and communication between different sprites or pieces of their story. For instance, the when _ key pressed block is an event that starts code whenever the corresponding key on the keyboard is pressed.', 
    '© 2022 All Rights Reserved', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit nihil tenetur minus quidem est deserunt molestias.', 
    'https://ornek.truva-software.com/vogue/', 
    '2022-07-17 19:52:12'
);

-- --------------------------------------------------------

--
-- Table structure for table `sitecontact`
--

CREATE TABLE `sitecontact` (
  `id` int(11) NOT NULL,
  `phone1` varchar(150) NOT NULL,
  `phone2` varchar(150) NOT NULL,
  `email1` varchar(100) NOT NULL,
  `email2` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `latitude` varchar(150) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sitecontact`
--

INSERT INTO `sitecontact` (`id`, `phone1`, `phone2`, `email1`, `email2`, `longitude`, `latitude`, `updated_at`) VALUES
(1, '+90 0312 394 44 21', '+90 0312 350 39 50', 'baranboya@gmail.com', 'baranboya@gmail.com', '7.099737483', '7.63734634', '2024-03-11 11:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `slide_title` varchar(150) NOT NULL,
  `slide_text` varchar(500) NOT NULL,
  `ufile` varchar(1000) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (
    `id`, 
    `slide_title`, 
    `slide_text`, 
    `ufile`, 
    `updated_at`
) 
VALUES
(
    1, 
    'Baran Boya', 
    '', 
    '58806059d354562031616499540.png', 
    '2024-11-07 14:23:07'
);

-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE `social` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `fa` varchar(150) NOT NULL,
  `social_link` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `social`
--

-- Dumping data for table `social`
INSERT INTO `social` (`id`, `name`, `fa`, `social_link`) 
VALUES 
    (1, 'Facebook', 'fa-facebook', 'https://www.facebook.com/baranpolyester'),
    (2, 'Instagram', 'fa-instagram', 'https://www.instagram.com/baranboyaepoxs/'),
    (3, 'Twitter', 'fa-twitter', 'https://x.com/baranboya');

-- --------------------------------------------------------

--
-- Table structure for table `static`
--

CREATE TABLE `static` (
  `id` int(11) NOT NULL,
  `stitle` varchar(150) NOT NULL,
  `stext` varchar(500) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `static`
--
INSERT INTO `static` (
    `id`, 
    `stitle`, 
    `stext`, 
    `updated_at`
) 
VALUES
(
    1, 
    'Baran Boya Kompozit Reçineler', 
    '',
    '2024-11-14 10:33:04'
);

-- --------------------------------------------------------

--
-- Table structure for table `tedariciklerimiz`
--

CREATE TABLE `tedarikcilerimiz` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `resim` VARCHAR(1000) NOT NULL,  -- Resim dosya yolu veya adı
  `guncellenme_tarihi` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;


-- --------------------------------------------------------

--
-- Table structure for table `why_us`
--

CREATE TABLE `why_us` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `detail` varchar(500) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `why_us`
--

INSERT INTO `why_us` (`id`, `title`, `detail`, `updated_on`) VALUES
(3, 'Keyword ranking', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur provident unde ex eligendi magni sit impedit iusto, sed ad fuga minima, dignissimos ducimus autem molestias, nostrum nesciunt enim? Ea, non hic voluptates dolorum impedit eveniet dolorem temporibus illo incidunt quis minima facere doloribus sit maiores, blanditiis labore quasi, accusantium quaerat!', '2022-07-17 18:43:07'),
(4, 'Social media', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur provident unde ex eligendi magni sit impedit iusto, sed ad fuga minima, dignissimos ducimus autem molestias, nostrum nesciunt enim? Ea, non hic voluptates dolorum impedit eveniet dolorem temporibus illo incidunt quis minima facere doloribus sit maiores, blanditiis labore quasi, accusantium quaerat!', '2022-07-17 18:44:19'),
(5, 'trend design', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur provident unde ex eligendi magni sit impedit iusto, sed ad fuga minima, dignissimos ducimus autem molestias, nostrum nesciunt enim? Ea, non hic voluptates dolorum impedit eveniet dolorem temporibus illo incidunt quis minima facere doloribus sit maiores, blanditiis labore quasi, accusantium quaerat!', '2022-07-17 18:44:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logo`
--
ALTER TABLE `logo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `section_title`
--
ALTER TABLE `section_title`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siteconfig`
--
ALTER TABLE `siteconfig`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sitecontact`
--
ALTER TABLE `sitecontact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social`
--
ALTER TABLE `social`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `static`
--
ALTER TABLE `static`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `why_us`
--
ALTER TABLE `why_us`
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
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `logo`
--
ALTER TABLE `logo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `section_title`
--
ALTER TABLE `section_title`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sitecontact`
--
ALTER TABLE `sitecontact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `social`
--
ALTER TABLE `social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `static`
--
ALTER TABLE `static`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimony`
--
ALTER TABLE `testimony`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `why_us`
--
ALTER TABLE `why_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
