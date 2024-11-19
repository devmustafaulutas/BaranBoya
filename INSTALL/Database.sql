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

INSERT INTO `alt_kategoriler` (`isim`, `kategori_id`,`resim`) VALUES
('Polyester Reçineler', 1 ,'assets/img/categorys/YAT 1.jpeg'),
('Vinilester Reçineler', 1 ,'category.png'),
('Epoksi Reçineler', 1 ,'category.png'),
('Poliüretan Reçine', 1 ,'category.png'),
('Reçine Yardımcıları', 1 ,'category.png'),

('Genel Amaçlı Jelkotlar', 2 ,'category.png'),
('Performans Jelkotlar', 2 ,'category.png'),
('Yüksek Performanslı Jelkotlar', 2 ,'category.png'),
('Zımparalanabilir Jelkotlar', 2 ,'category.png'),
('Kalıplama Jelkotları', 2 ,'category.png'),
('Marin Uygulama Jelkotları', 2 ,'category.png'),
('Kimyasal Dayanımlı Jelkotlar', 2 ,'category.png'),
('Alev İlerletmeyen Jelkotlar', 2 ,'category.png'),

('Cam Elyaf Keçeler', 3 ,'category.png'),
('Cam Elyaf Dokuma Kumaşlar', 3 ,'category.png'),
('Karbon Elyaf Dokuma Kumaşlar', 3 ,'category.png'),
('Fitil İp Elyaflar', 3 ,'category.png'),
('Kırpık Elyaflar', 3 ,'category.png'),
('Yüzey Tülü (Kortel)', 3 ,'category.png'),
('Corematlar', 3 ,'category.png'),

('Vaks Kalıp Ayırıcılar', 4 ,'category.png'),
('Sprey Kalıp Ayırıcılar', 4 ,'category.png'),
('Sıvı Kalıp Ayırıcılar', 4 ,'category.png'),

('Kalsitler', 5 ,'category.png'),
('Talk Pudrası', 5 ,'category.png'),
('Aerosil', 5 ,'category.png'),
('Çinko Streat', 5 ,'category.png'),
('Alüminyum Tozu', 5 ,'category.png'),
('Alev Geciktirici Dolgu', 5 ,'category.png'),

('Polyester ve Poliüretan Renklendirici Pigmentler', 6 ,'category.png'),
('Titandioksitler', 6 ,'category.png'),
('Epoksi Reçine Pigmentler', 6 ,'category.png'),
('RTV-2 Kalıp Silikonu Pigmentleri', 6 ,'category.png'),

('Fırçalar ve Rulolar', 7 ,'category.png'),
('Tulumlar ve Maskeler', 7 ,'category.png'),
('Jelkot Tabancaları', 7 ,'category.png'),
('Zımpara Makineleri ve Zımparalar', 7 ,'category.png'),
('Polyester Ezme Rulolar', 7 ,'category.png'),
('Bantlar', 7 ,'category.png'),
('Ölçüm ve Dozajlama Ekipmanları', 7 ,'category.png');


INSERT INTO `alt_kategoriler_alt` (`isim`, `alt_kategori_id`) VALUES
('Döküm Tipi Polyester Reçineler', 1),
('Breton Tipi Polyester Reçineler', 1),
('Kalıplama Polyester Reçineler', 1),
('Genel Amaçlı (Elyaf Tip) Polyester Reçineler', 1),
('CTP Tipi (Fitil Sarma) Polyester Reçineler', 1),
('RTM/İnfüzyon Tipi Polyester Reçineler', 1),
('Akrilik Tipi Polyester Reçineler', 1),
('SMC/BMC Tipi Polyester Reçineler', 1),
('Düğme Tipi Polyester Reçineler', 1),
('Kimyasal Dayanımlı Polyester Reçineler', 1),  
('Alev İlerletmeyen Polyester Reçineler', 1), 
('Köpük Eritmeyen Polyester Reçineler', 1), 
('Köpük Eritmeyen Polyester Reçineler', 1), 

('Bisfenol-A Vinilester Reçineler', 2), 
('Novolak Vinilester Reçineler', 2), 
('Bromine Vinilester Reçineler', 2), 
('Amin Hızlandırıcılı Vinilester Reçineler', 2), 
('Poliüretan Reçine', 2), 
('Epoksi Reçineler', 2); 


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
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`,`alt_kategori_id`,`alt_kategori_alt_id`) VALUES
('Turkuaz TP600 Genel Amaçlı Jelkot', 'Genel Amaçlı Jelkot', 60.00, 300, 'turkuaz_tp600.jpg', 5, 1,NULL),
('Poliya Polijel 206 Genel Amaçlı Jelkot', 'Genel Amaçlı Jelkot', 65.00, 250, 'polijel_206.jpg', 5, 1,NULL),
('Poliya Polijel 208 Genel Amaçlı Jelkot', 'Genel Amaçlı Jelkot', 70.00, 220, 'polijel_208.jpg', 5, 1,NULL),
('Turkuaz TP900 Performans Jelkot', 'Performans Jelkot', 90.00, 150, 'turkuaz_tp900.jpg', 5, 2,NULL),
('Poliya Polijel 211 Performans Jelkot', 'Performans Jelkot', 95.00, 130, 'polijel_211.jpg', 5, 2,NULL),
('Turkuaz TP1000 Yüksek Performans Jelkot', 'Yüksek Performans Jelkot', 120.00, 100, 'turkuaz_tp1000.jpg', 5, 3,NULL);
-- Yüksek Performans Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`,`alt_kategori_alt_id`) VALUES
('Poliya Polijel 213 Yüksek Performans Jelkot', 'Yüksek Performans Jelkot', 150.00, 120, 'polijel_213.jpg', 5, 3,NULL),
('Poliya Polijel 215 Yüksek Performans Jelkot', 'Yüksek Performans Jelkot', 155.00, 110, 'polijel_215.jpg', 5, 3,NULL);

-- Kimyasal Dayanımlı Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`,`alt_kategori_alt_id`) VALUES
('Poliya Polijel 271 Kimyasal ve Işık Dayanımlı Jelkot', 'Kimyasal ve Işık Dayanımlı Jelkot', 200.00, 80, 'polijel_271.jpg', 5, 4,NULL);

-- Zımparalanabilir Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`,`alt_kategori_alt_id`) VALUES
('Poliya Polijel 209-Z Zımparalanabilir Jelkot', 'Zımparalanabilir Jelkot', 90.00, 150, 'polijel_209z.jpg', 5, 5,NULL),
('Poliya Polijel 2089-Z Yüksek Isıl Dayanımlı Zımparalanabilir Jelkot', 'Yüksek Isıl Dayanımlı Zımparalanabilir Jelkot', 110.00, 120, 'polijel_2089z.jpg', 5, 5,NULL);

-- Kalıp Yapımı Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`,`alt_kategori_alt_id`) VALUES
('Poliya Polijel 220 Kalıp Jelkotu', 'Kalıp Jelkotu', 130.00, 100, 'polijel_220.jpg', 5, 6,NULL),
('Poliya Polijel 291 Yüksek Isıl ve Kimyasal Dayanımlı Kalıp Jelkotu', 'Yüksek Isıl ve Kimyasal Dayanımlı Kalıp Jelkotu', 150.00, 90, 'polijel_291.jpg', 5, 6,NULL),
('Poliya Polijel 240 Kalıp Tamir Jelkotu', 'Kalıp Tamir Jelkotu', 140.00, 80, 'polijel_240.jpg', 5, 6, NULL);

-- Alev İlerletmeyen Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`,`alt_kategori_alt_id`) VALUES
('Poliya Polijel F-345 FR Alev İlerletmeyen Jelkot', 'Alev İlerletmeyen Jelkot', 180.00, 60, 'polijel_f345_fr.jpg', 5, 7,NULL);

-- Cam Elyaf Keçeler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Şişecam Cam Elyaf E-Mat1 300 gr/m²', 'Cam Elyaf Keçeler', 30.00, 200, 'sisecam_emat1_300.jpg', 6, 1, NULL),
('Şişecam Cam Elyaf E-Mat1 450 gr/m²', 'Cam Elyaf Keçeler', 35.00, 180, 'sisecam_emat1_450.jpg', 6, 1, NULL),
('Şişecam Cam Elyaf E-Mat1 600 gr/m²', 'Cam Elyaf Keçeler', 40.00, 150, 'sisecam_emat1_600.jpg', 6, 1, NULL),
('Şişecam Cam Elyaf Mat8 300 gr/m²', 'Cam Elyaf Keçeler', 32.00, 200, 'sisecam_mat8_300.jpg', 6, 1, NULL),
('Şişecam Cam Elyaf Mat8 450 gr/m²', 'Cam Elyaf Keçeler', 37.00, 170, 'sisecam_mat8_450.jpg', 6, 1, NULL),
('Jushi Sıvı (Mumlu) Elyaf 450gr/m²', 'Cam Elyaf Keçeler', 38.00, 160, 'jushi_sivi_450.jpg', 6, 1, NULL);

-- Cam Elyaf Dokuma Kumaşlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Dokuma (Örgü) Elyaf 100 gr/m²', 'Cam Elyaf Dokuma Kumaşlar', 50.00, 120, 'dokuma_elyaf_100.jpg', 6, 2, NULL),
('Fiber Flex Dokuma (Örgü) Elyaf 300 gr/m²', 'Cam Elyaf Dokuma Kumaşlar', 70.00, 100, 'fiber_flex_300.jpg', 6, 2, NULL),
('Fiber Flex Dokuma (Örgü) Elyaf 500 gr/m²', 'Cam Elyaf Dokuma Kumaşlar', 90.00, 80, 'fiber_flex_500.jpg', 6, 2, NULL),
('Fiber Flex Dokuma (Örgü) Elyaf 800 gr/m²', 'Cam Elyaf Dokuma Kumaşlar', 120.00, 50, 'fiber_flex_800.jpg', 6, 2, NULL);

-- Karbon Elyaf Dokuma Kumaşlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Karbon Elyaf Plain 200 gr/m²', 'Karbon Elyaf Dokuma Kumaşlar', 150.00, 70, 'karbon_plain_200.jpg', 6, 3, NULL),
('Karbon Elyaf Twill 200 gr/m²', 'Karbon Elyaf Dokuma Kumaşlar', 160.00, 65, 'karbon_twill_200.jpg', 6, 3, NULL),
('Karbon Elyaf Plain 245 gr/m²', 'Karbon Elyaf Dokuma Kumaşlar', 180.00, 60, 'karbon_plain_245.jpg', 6, 3, NULL),
('Karbon Elyaf Twill 245 gr/m²', 'Karbon Elyaf Dokuma Kumaşlar', 190.00, 50, 'karbon_twill_245.jpg', 6, 3, NULL),
('Karbon Elyaf Forged', 'Karbon Elyaf Dokuma Kumaşlar', 200.00, 40, 'karbon_forged.jpg', 6, 3, NULL);

-- Vaks Kalıp Ayırıcılar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polivaks SV-6', 'Vaks Kalıp Ayırıcılar', 50.00, 200, 'polivaks_sv6.jpg', 7, 1, NULL),
('Poliya Polivaks Ekonomik Vaks', 'Vaks Kalıp Ayırıcılar', 40.00, 220, 'polivaks_ekonomik.jpg', 7, 1, NULL),
('Poliya Polivaks N-Vaks', 'Vaks Kalıp Ayırıcılar', 60.00, 180, 'polivaks_nvaks.jpg', 7, 1, NULL),
('Viky Kalıp Ayırıcı Vaks', 'Vaks Kalıp Ayırıcılar', 55.00, 190, 'viky_vaks.jpg', 7, 1, NULL);

-- Sprey Kalıp Ayırıcılar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polivaks PV-7 Performans Kalıp Ayırıcı', 'Sprey Kalıp Ayırıcılar', 70.00, 150, 'polivaks_pv7.jpg', 7, 2, NULL),
('Best Sprey Kalıp Ayırıcı', 'Sprey Kalıp Ayırıcılar', 60.00, 160, 'best_sprey.jpg', 7, 2, NULL),
('Colorıum Yüksek Performans Sprey Kalıp Ayırıcı', 'Sprey Kalıp Ayırıcılar', 80.00, 140, 'colorium_sprey.jpg', 7, 2, NULL);

-- Sıvı Kalıp Ayırıcılar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polivaks PVA Performans Sıvı Ayırıcı', 'Sıvı Kalıp Ayırıcılar', 90.00, 120, 'polivaks_pva.jpg', 7, 3, NULL),
('Poliya Polivaks Eko PVA Ekonomik Sıvı Ayırıcı', 'Sıvı Kalıp Ayırıcılar', 75.00, 130, 'polivaks_eko_pva.jpg', 7, 3, NULL),
('Marbocote CEE277 Yüksek Performans Sıvı Ayırıcı', 'Sıvı Kalıp Ayırıcılar', 100.00, 110, 'marbocote_cee277.jpg', 7, 3, NULL);

-- Kalsitler (Mermer Tozları)
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Aksaray Kalsit 40 Mikron Beyaz Kalsit', 'Kalsitler (Mermer Tozları)', 25.00, 300, 'aksaray_kalsit_40.jpg', 8, 1, NULL),
('MerTaş 5 Mikron Beyaz Kalsit', 'Kalsitler (Mermer Tozları)', 30.00, 280, 'mertas_kalsit_5.jpg', 8, 1, NULL),
('Aksaray Kalsit Kalın (Mıcır) Kalsit', 'Kalsitler (Mermer Tozları)', 20.00, 320, 'aksaray_kalsit_kalin.jpg', 8, 1, NULL);

-- Talk Pudrası
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Talk Pudrası Extra', 'Talk Pudrası', 35.00, 250, 'talk_pudrasi_extra.jpg', 8, 2, NULL);

-- Aerosiller
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Wacker HDK N2O Aerosil Kıvamlaştırıcı', 'Aerosiller', 45.00, 180, 'wacker_hdk.jpg', 8, 3, NULL),
('Ekonomik Aerosil Kıvamlaştırıcı', 'Aerosiller', 40.00, 200, 'ekonomik_aerosil.jpg', 8, 3, NULL);

-- Alev Geciktirici Dolgu
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('ATH APYRAL 16 Alev Geciktirici Dolgu', 'Alev Geciktirici Dolgu', 60.00, 150, 'ath_apyral_16.jpg', 8, 5, NULL);

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
