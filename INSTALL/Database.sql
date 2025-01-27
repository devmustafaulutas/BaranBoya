-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Ocak , 2025 at 12:30 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES 'utf8mb4';
SET CHARACTER SET utf8mb4;


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

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `2fa_secret`  VARCHAR(255) NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

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
    `resim` VARCHAR(255) NOT NULL,
    `kategori_id` INT,
    FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler`(`id`) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

-- Alt kategoriler tablosu, ana kategorilere bağlı
CREATE TABLE `alt_kategoriler` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(255) NOT NULL,
    `resim` VARCHAR(255) NOT NULL,
    `kategori_id` INT,
    FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler`(`id`) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

-- Alt kategorilerin alt kategorileri, alt kategorilere bağlı
CREATE TABLE `alt_kategoriler_alt` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(255) NOT NULL,
    `resim` VARCHAR(255) NOT NULL,
    `alt_kategori_id` INT,
    FOREIGN KEY (`alt_kategori_id`) REFERENCES `alt_kategoriler`(`id`) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

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
) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci;

INSERT INTO `kategoriler` (`isim`,`resim`) VALUES

('Reçineler','assets/img/categorys/YAT 1.jpeg'),
('Jelkotlar','category.png'),
('Cam Elyaflar','category.png'),
('Kalıp Ayırıcılar','category.png'),
('Dolgu Malzemeleri','category.png'),
('Renklendiriciler','category.png'),
('Sarf Malzemeleri','category.png');

INSERT INTO `alt_kategoriler` (`isim`, `kategori_id`, `resim`) VALUES
('Polyester Reçineler', 1, 'assets/img/categorys/YAT 1.jpeg'),
('Vinilester Reçineler', 1, 'category.png'),
('Epoksi Reçineler', 1, 'category.png'),
('Poliüretan Reçine', 1, 'category.png'),
('Reçine Yardımcıları', 1, 'category.png'),

('Genel Amaçlı Jelkotlar', 2, 'category.png'),
('Performans Jelkotlar', 2, 'category.png'),
('Yüksek Performanslı Jelkotlar', 2, 'category.png'),
('Zımparalanabilir Jelkotlar', 2, 'category.png'),
('Kalıplama Jelkotları', 2, 'category.png'),
('Marin Uygulama Jelkotları', 2, 'category.png'),
('Kimyasal Dayanımlı Jelkotlar', 2, 'category.png'),
('Alev İlerletmeyen Jelkotlar', 2, 'category.png'),

('Cam Elyaf Keçeler', 3, 'category.png'),
('Cam Elyaf Dokuma Kumaşlar', 3, 'category.png'),
('Karbon Elyaf Dokuma Kumaşlar', 3, 'category.png'),
('Fitil İp Elyaflar', 3, 'category.png'),
('Kırpık Elyaflar', 3, 'category.png'),
('Yüzey Tülü (Kortel)', 3, 'category.png'),
('Corematlar', 3, 'category.png'),

('Vaks Kalıp Ayırıcılar', 4, 'category.png'),
('Sprey Kalıp Ayırıcılar', 4, 'category.png'),
('Sıvı Kalıp Ayırıcılar', 4, 'category.png'),

('Kalsitler', 5, 'category.png'),
('Talk Pudrası', 5, 'category.png'),
('Aerosil', 5, 'category.png'),
('Çinko Streat', 5, 'category.png'),
('Alüminyum Tozu', 5, 'category.png'),
('Alev Geciktirici Dolgu', 5, 'category.png'),

('Polyester ve Poliüretan Renklendirici Pigmentler', 6, 'category.png'),
('Titandioksitler', 6, 'category.png'),
('Epoksi Reçine Pigmentler', 6, 'category.png'),
('RTV-2 Kalıp Silikonu Pigmentleri', 6, 'category.png'),

('Fırçalar ve Rulolar', 7, 'category.png'),
('Tulumlar ve Maskeler', 7, 'category.png'),
('Jelkot Tabancaları', 7, 'category.png'),
('Zımpara Makineleri ve Zımparalar', 7, 'category.png'),
('Polyester Ezme Rulolar', 7, 'category.png'),
('Bantlar', 7, 'category.png'),
('Ölçüm ve Dozajlama Ekipmanları', 7, 'category.png'),

('Temizleyici Solventler', 6, 'category.png');

INSERT INTO `alt_kategoriler_alt` (`isim`,`resim`, `alt_kategori_id`) VALUES
('Döküm Tipi Polyester Reçineler','alt_category_alt.png', 1),
('Breton Tipi Polyester Reçineler','alt_category_alt.png', 1),
('Kalıplama Polyester Reçineler','alt_category_alt.png', 1),
('Genel Amaçlı (Elyaf Tip) Polyester Reçineler','alt_category_alt.png', 1),
('CTP Tipi (Fitil Sarma) Polyester Reçineler','alt_category_alt.png', 1),
('RTM/İnfüzyon Tipi Polyester Reçineler','alt_category_alt.png', 1),
('Akrilik Tipi Polyester Reçineler','alt_category_alt.png', 1),
('SMC/BMC Tipi Polyester Reçineler','alt_category_alt.png', 1),
('Düğme Tipi Polyester Reçineler','alt_category_alt.png', 1),
('Kimyasal Dayanımlı Polyester Reçineler','alt_category_alt.png', 1),  
('Alev İlerletmeyen Polyester Reçineler','alt_category_alt.png', 1), 
('Köpük Eritmeyen Polyester Reçineler','alt_category_alt.png', 1), 
('Cila Tipi Köpük Eritmeyen Polyester Reçineler','alt_category_alt.png', 1), 

('Bisfenol-A Vinilester Reçineler','alt_category_alt.png', 2), 
('Novolak Vinilester Reçineler','alt_category_alt.png', 2), 
('Bromine Vinilester Reçineler','alt_category_alt.png', 2), 
('Amin Hızlandırıcılı Vinilester Reçineler','alt_category_alt.png', 2), 


('Mek Peroksitler (Polyester ve Jelkot Dondurucu)','alt_category_alt.png', 33),
('Kobalt Oktoatlar (Polyester ve Jelkot Hızlandırıcılar)','alt_category_alt.png', 33), 
('İnceltici Monomerler ve Kıvamlaştırıcılar','alt_category_alt.png', 33);

-- Ürünler için INSERT sorguları
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Turkuaz TP100 Döküm Tipi Polyester', 'Döküm Tipi Polyester Reçine', 100.00, 50, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 1),
('Camelyaf Resins CE80 Döküm Tipi Polyester', 'Döküm Tipi Polyester Reçine', 120.00, 30, 'assets/img/products/CAMELYAF 18 KG.png', 1, 1, 1),
('Poliya Polipol 3453 Döküm Tipi Polyester', 'Döküm Tipi Polyester Reçine', 110.00, 40, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 1),
('Turkuaz TP182 Kuvars Kompoze Taş Polyester', 'Breton Tipi Polyester Reçine', 140.00, 20, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 2),
('Poliya Polipol 357 Kuvars Kompoze Taş Polyester', 'Breton Tipi Polyester Reçine', 150.00, 25, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 2),
('Turkuaz TP220 (Tİ-CO) Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 130.00, 10, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 4),
('Turkuaz TP200 (Tİ-CO) Genel Amaçlı Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 135.00, 15, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 4),
('Camelyaf Resins CE92 N8 Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 125.00, 10, 'assets/img/products/CAMELYAF 18 KG.png', 1, 1, 4),
('Poliya Polipol 3404 (TA-LSE-H20/40/60/100) Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 140.00, 8, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 4),
('Poliya Polipol 3401 (TA-LSE-H20/40/60/100) Elyaf Tipi Polyester', 'Genel Amaçlı Elyaf Tipi Polyester Reçine', 140.00, 8, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 4),
('Poliya Polipol 320TA Kalıp Yapımı Polyester', 'Kalıplama Polyester Reçine', 160.00, 5, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 3),
('Poliya Polipol 321-Zero Kalıp Yapımı Polyester', 'Kalıplama Polyester Reçine', 165.00, 4, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 3),
('Turkuaz TP 1040 CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 170.00, 12, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 5),
('Turkuaz TP 1082 CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 170.00, 12, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 5),
('Poliya Polipol 3562 CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 175.00, 10, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 5),
('Poliya Polipol 3872-F CTP Fitil Sarma Polyester', 'CTP Tipi Polyester Reçine', 175.00, 10, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 5),
('Turkuaz TP 260 RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 180.00, 7, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 6),
('Turkuaz TP 911-CO RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 180.00, 7, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 6),
('Poliya Polipol 3387 Zero RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 185.00, 6, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 6),
('Poliya Polipol 3382 RTM Tipi Polyester', 'RTM/İnfüzyon Tipi Polyester Reçine', 185.00, 6, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 6),
('Turkuaz TP 1300 Akrilik Tipi Dolgusuz Polyester', 'Akrilik Tipi Polyester Reçine', 190.00, 3, 'resim16.jpg', 1, 1, 7),
('Poliya Polipol 341 Akrilik Tipi Dolgulu Polyester', 'Akrilik Tipi Polyester Reçine', 195.00, 2, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 7),
('Poliya Polipol 341-TA Akrilik Tipi Dolgulusuz Polyester', 'Akrilik Tipi Polyester Reçine', 195.00, 2, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 7),
('Poliya Polipol 342-TA D40 Dolgulu Polyester', 'Akrilik Tipi Polyester Reçine', 195.00, 2, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 7),
('Turkuaz TP 535 SMC/BMC Tipi Polyester', 'SMC/BMC Tipi Polyester Reçine', 200.00, 5, 'resim18.jpg', 1, 1, 8),
('Turkuaz TP 500 BMC Tipi Polyester', 'BMC Tipi Polyester Reçine', 200.00, 5, 'resim18.jpg', 1, 1, 8),
('Poliya Polipol 3417-V SMC/BMC Tipi Polyester', 'SMC/BMC Tipi Polyester Reçine', 200.00, 5, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 8),
('Poliya Polipol 382 SMC/BMC Tipi Kimyasal Dayanımlı Polyester', 'SMC/BMC Tipi Kimyasal Dayanımlı Polyester Reçine', 210.00, 4, 'resim19.jpg', 1, 1, 8),
('Poliya Polipol 3418 SMC/BMC Tipi Tam Maleik Polyester', 'SMC/BMC Tipi Kimyasal Dayanımlı Polyester Reçine', 210.00, 4, 'resim19.jpg', 1, 1, 8),
('Turkuaz TP 400 (C) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 220.00, 6, 'resim20.jpg', 1, 1, 9),
('Turkuaz TP 409 (C) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 220.00, 6, 'resim20.jpg', 1, 1, 9),
('Poliya Polipol 3541 (T) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 230.00, 3, 'resim21.jpg', 1, 1, 9),
('Poliya Polipol 3542 (T) Santrifuj ve Çubuk Düğme Tipi Polyester', 'Düğme Tipi Polyester Reçine', 230.00, 3, 'resim21.jpg', 1, 1, 9);
-- Kimyasal Dayanımlı Polyester Reçineler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Turkuaz TP1071 Kimyasal Dayanımlı Polyester', 'Kimyasal Dayanımlı Polyester Reçineler', 220.00, 6, 'assets/img/products/TURKUAZ 18 KG.png', 1, 1, 10),
('Poliya Polipol 381 Kimyasal Dayanımlı Polyester', 'Kimyasal Dayanımlı Polyester Reçineler', 220.00, 6, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 10),
('Poliya Polipol 391 Yüksek Kimyasal ve Işık Dayanımlı Bisfenolik Polyester', 'Kimyasal Dayanımlı Polyester Reçineler', 230.00, 3, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 10);

-- Alev İlerletmeyen Polyester Reçineler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polipol 345-FR', 'Alev İlerletmeyen Polyester Reçine', 150.00, 100, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 11),
('Boytek BRE 400 Stratford Köpüğü Eritmeyen Polyester', 'Köpüğü Eritmeyen Polyester Reçine', 120.00, 200, 'boytek_bre_400.jpg', 1, 1, 12),
('Poliya Polipol 3515 Polistiren Köpüğü Eritmeyen Polyester', 'Polistiren Köpüğü Eritmeyen Polyester Reçine', 130.00, 150, 'assets/img/products/POLİYA 18 KG.png', 1, 1, 12),
('İlkalem İlkester P1020 Cila Tipi Polyester', 'Cila Tipi Polyester Reçine', 110.00, 80, 'ilkester_p1020.jpg', 1, 1, 13);

-- Vinilester Reçineler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polives 701 Yüksek Kimyasal Dayanımlı Performans Vinilester', 'Yüksek Kimyasal Dayanımlı Performans Vinilester', 180.00, 100, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 14),
('Poliya Polives 702 RTM/İnfüzyon Tipi Vinilester', 'RTM/İnfüzyon Tipi Vinilester', 170.00, 150, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 14),
('Poliya Polives 711 Yüksek Kimyasal Dayanımlı Yüksek Performans Vinilester', 'Yüksek Performans Vinilester', 190.00, 80, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 14),
('Poliya Polives 709 Pultrüzyon Tipi Vinilester', 'Pultrüzyon Tipi Vinilester', 160.00, 120, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 14),
('Poliya Polives 710 RTM Alev İlerletmeyen Vinilester', 'Alev İlerletmeyen RTM Vinilester', 200.00, 50, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 14),
('Poliya Polives 721 Novolak Vinilester', 'Novolak Vinilester', 210.00, 70, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 15),
('Poliya Polives 710 RTM Alev İlerletmeyen Vinilester', 'Alev İlerletmeyen RTM Vinilester', 200.00, 50, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 16),
('Poliya Polives 710-I RTM/İnfüzyon Alev İlerletmeyen Vinilester', 'RTM/İnfüzyon Alev İlerletmeyen Vinilester', 220.00, 40, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 16),
('Poliya Polives 710-TA-LSE Alev İlerletmeyen ve Tiksotropik Düşük Stiren İçeren Vinilester', 'Tiksotropik Düşük Stiren İçeren Vinilester', 230.00, 60, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 16),
('Poliya Polives 701-ABP Amin Hızlandırıcılı Yüksek Kimyasal Dayanımlı Vinilester', 'Amin Hızlandırıcılı Yüksek Kimyasal Dayanımlı Vinilester', 240.00, 55, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 17),
('Poliya Polives 711-ABP Amin Hızlandırıcılı Yüksek Kimyasal Dayanımlı Yüksek Performans Vinilester', 'Amin Hızlandırıcılı Yüksek Performans Vinilester', 250.00, 45, 'assets/img/products/POLİYA 18 KG.png', 1, 2, 17);

-- Poliüretan Reçine
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Polipol ve İzosiyanat Poliüretan Reçine Sıvı Plastik', 'Sıvı Plastik Poliüretan Reçine', 300.00, 30, 'assets/img/products/POLİYA 18 KG.png', 1, 4, 18);

-- Epoksi Reçineler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('EpoXs İnce Döküm (Hediyelik) Tipi Epoksi', 'İnce Döküm (Hediyelik) Tipi Epoksi', 50.00, 200, 'epoxs_ince.jpg', 1, 3, 19),
('EpoXs Kalın Döküm (Masa) Tipi Epoksi', 'Kalın Döküm (Masa) Tipi Epoksi', 120.00, 150, 'epoxs_kalin.jpg', 1, 3, 19),
('EpoXs Teşbih Yapım Epoksisi', 'Teşbih Yapım Epoksisi', 80.00, 180, 'epoxs_tesbih.jpg', 1, 3, 19),
('EpoXs Laminasyon (Kompozit) Tipi Epoksi', 'Laminasyon Tipi Epoksi', 110.00, 100, 'epoxs_laminasyon.jpg', 1, 3, 19);


-- Jelkotlar

-- Genel Amaçlı Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Turkuaz TP600 Genel Amaçlı Jelkot', 'Genel Amaçlı Jelkot', 190.00, 18, 'assets/img/products/TURKUAZ 18 KG.png', 2, 6, NULL),
('Poliya Polijel 206 Genel Amaçlı Jelkot', 'Genel Amaçlı Jelkot', 190.00, 18, 'assets/img/products/POLİYA 18 KG.png', 2, 6, NULL),
('Poliya Polijel 208 Genel Amaçlı Jelkot', 'Genel Amaçlı Jelkot', 185.00, 12, 'assets/img/products/POLİYA 18 KG.png', 2, 6, NULL);

-- Performans Jelkot
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Turkuaz TP900 Performans Jelkot', 'Performans Jelkot', 220.00, 10, 'assets/img/products/TURKUAZ 18 KG.png', 2, 7, NULL),
('Poliya Polijel 211 Performans Jelkot', 'Performans Jelkot', 220.00, 10, 'assets/img/products/POLİYA 18 KG.png', 2, 7, NULL);

-- Yüksek Performans Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Turkuaz TP1000 Yüksek Performans Jelkot', 'Yüksek Performanslı Jelkot', 250.00, 8, 'assets/img/products/TURKUAZ 18 KG.png', 2, 8, NULL),
('Poliya Polijel 213 Yüksek Performans Jelkot', 'Yüksek Performanslı Jelkot', 250.00, 8, 'assets/img/products/POLİYA 18 KG.png', 2, 8, NULL),
('Poliya Polijel 215 Yüksek Performans Jelkot', 'Yüksek Performanslı Jelkot', 250.00, 8, 'assets/img/products/POLİYA 18 KG.png', 2, 8, NULL);

-- Kimyasal Dayanımlı Jelktolar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polijel 271 Kimyasal ve Işık Dayanımlı Jelkot', 'Kimyasal Dayanımlı Jelkot', 280.00, 10, 'resim22.jpg', 2, 12, NULL);

-- Zımparalanabilir Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polijel 209-Z Zımparalanabilir Jelkot', 'Zımparalanabilir Jelkot', 240.00, 15, 'resim19.jpg', 2, 9, NULL),
('Poliya Polijel 2089-Z Yüksek Isıl Dayanımlı Zımparalanabilir Jelkot', 'Zımparalanabilir Jelkot', 240.00, 15, 'resim19.jpg', 2, 9, NULL);

-- Kalıp Yapımı Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polijel 220 Kalıp Jelkotu', 'Kalıplama Jelkot', 230.00, 20, 'resim20.jpg', 2, 10, NULL),
('Poliya Polijel 291 Yüksek Isıl ve Kimyasal Dayanımlı Kalıp Jelkotu', 'Kalıplama Jelkot', 230.00, 20, 'resim20.jpg', 2, 10, NULL),
('Poliya Polijel 240 Kalıp Tamir Jelkotu', 'Kalıplama Jelkot', 230.00, 20, 'resim20.jpg', 2, 10, NULL);

-- Alev İlerletmeyen Jelkotlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polijel F-345 FR Alev İlerletmeyen Jelkot', 'Alev İlerletmeyen Jelkot', 300.00, 5, 'resim23.jpg', 2, 13, NULL),

('Turkuaz TP 2085 Marin Uygulama Jelkot', 'Marin Uygulama Jelkot', 270.00, 12, 'resim21.jpg', 2, 11, NULL);

-- Cam Elyaflar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Şişecam Cam Elyaf E-Mat1 300 gr/m²', 'Cam Elyaf Keçe', 150.00, 100, 'resim24.jpg', 3, 14, NULL),
('Şişecam Cam Elyaf E-Mat1 450 gr/m²', 'Cam Elyaf Keçe', 160.00, 80, 'resim25.jpg', 3, 14, NULL),
('Şişecam Cam Elyaf E-Mat1 600 gr/m²', 'Cam Elyaf Keçe', 160.00, 80, 'resim25.jpg', 3, 14, NULL),
('Şişecam Cam Elyaf Mat8 300 gr/m²', 'Cam Elyaf Keçe', 160.00, 80, 'resim25.jpg', 3, 14, NULL),
('Şişecam Cam Elyaf Mat8 450 gr/m²', 'Cam Elyaf Keçe', 160.00, 80, 'resim25.jpg', 3, 14, NULL),
('Jushi Sıvı (Mumlu) Elyaf 450gr/m²', 'Cam Elyaf Keçe', 160.00, 80, 'resim25.jpg', 3, 14, NULL),
('Dokuma (Örgü) Elyaf 100 gr/m²', 'Cam Elyaf Dokuma Kumaş', 170.00, 70, 'resim26.jpg', 3, 15, NULL),
('Fiber Flex Dokuma (Örgü) Elyaf 300 gr/m²', 'Cam Elyaf Dokuma Kumaş', 170.00, 70, 'resim26.jpg', 3, 15, NULL),
('Fiber Flex Dokuma (Örgü) Elyaf 500 gr/m²', 'Cam Elyaf Dokuma Kumaş', 170.00, 70, 'resim26.jpg', 3, 15, NULL),
('Fiber Flex Dokuma (Örgü) Elyaf 800 gr/m²', 'Cam Elyaf Dokuma Kumaş', 180.00, 60, 'resim27.jpg', 3, 15, NULL),
('Karbon Elyaf Plain 200 gr/m²', 'Karbon Elyaf Dokuma Kumaş', 220.00, 50, 'resim28.jpg', 3, 16, NULL),
('Karbon Elyaf Twill 200 gr/m²', 'Karbon Elyaf Dokuma Kumaş', 220.00, 50, 'resim28.jpg', 3, 16, NULL),
('Karbon Elyaf Plain 245 gr/m²', 'Karbon Elyaf Dokuma Kumaş', 220.00, 50, 'resim28.jpg', 3, 16, NULL),
('Karbon Elyaf Twill 245 gr/m²', 'Karbon Elyaf Dokuma Kumaş', 240.00, 40, 'resim29.jpg', 3, 16, NULL),
('Karbon Elyaf Forged', 'Karbon Elyaf Dokuma Kumaş', 240.00, 40, 'resim29.jpg', 3, 16, NULL);

-- -- Fitil İp Elyaf
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Şişecam FWR6 2400 Tek Uçlu Fitil İp Elyaf', 'Fitil İp Elyaflar', 200.00, 30, 'resim30.jpg', 3, 17, NULL),
('Şişecam FWR6 4800 Tek Uçlu Fitil İp Elyaf', 'Fitil İp Elyaflar', 200.00, 30, 'resim30.jpg', 3, 17, NULL),
('Jushi 2400 Tek Uçlu Fitil İp Elyaf', 'Fitil İp Elyaflar', 200.00, 30, 'resim30.jpg', 3, 17, NULL);

-- Kırpık Elyaf
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Jushı 6mm SMC/BMC Kırpık Elyaf', 'Kırpık Elyaflar', 190.00, 25, 'resim31.jpg', 3, 18, NULL);

-- Yüzey Tülü (Kortel)
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Kortel Yüzey Tülü 26 gr/m²', 'Yüzey Tülü', 210.00, 20, 'resim32.jpg', 3, 19, NULL),
('Kortel Yüzey Tülü 50 gr/m²', 'Yüzey Tülü', 210.00, 20, 'resim32.jpg', 3, 19, NULL);

-- Corematlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Coremat 2mm', 'Coremat', 250.00, 15, 'resim33.jpg', 3, 20, NULL),
('Coremat 3mm', 'Coremat', 270.00, 10, 'resim34.jpg', 3, 20, NULL);

-- Kalıp Ayırıcılar
-- Vaks Kalıp Ayırıcılar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polivaks SV-6', 'Vaks Kalıp Ayırıcılar', 90.00, 50, 'resim35.jpg', 4, 21, NULL),
('Poliya Polivaks Ekonomik Vaks', 'Vaks Kalıp Ayırıcılar', 100.00, 60, 'resim36.jpg', 4, 21, NULL),
('Poliya Polivaks N-Vaks', 'Vaks Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 21, NULL),
('Viky Kalıp Ayırıcı Vaks', 'Vaks Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 21, NULL);

-- Sprey Kalıp Ayırıcılar

INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES

('Poliya Polivaks PV-7 Performans Kalıp Ayırıcı', 'Sprey Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 22, NULL),
('Best Sprey Kalıp Ayırıcı', 'Sprey Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 22, NULL),
('Colorıum Yüksek Performans Sprey Kalıp Ayırıcı', 'Sprey Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 22, NULL);

-- Sıvı Kalıp Ayırıcılar

INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polivaks PVA Performans Sıvı Ayırıcı', 'Sıvı Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 23, NULL),
('Poliya Polivaks Eko PVA Ekonomik Sıvı Ayırıcı', 'Sıvı Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 23, NULL),
('Marbocote CEE277 Yüksek Performans Sıvı Ayırıcı', 'Sıvı Kalıp Ayırıcılar', 110.00, 40, 'resim37.jpg', 4, 23, NULL);


-- Dolgu Malzemeleri

-- Kalsitler (Mermer Tozları)
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Aksaray Kalsit 40 Mikron Beyaz Kalsit', 'Kalsit', 50.00, 120, 'resim38.jpg', 5, 24, NULL),
('MerTaş 5 Mikron Beyaz Kalsit', 'Kalsit', 50.00, 120, 'resim38.jpg', 5, 24, NULL),
('Aksaray Kalsit Kalın (Mıcır) Kalsit', 'Kalsit', 40.00, 130, 'resim39.jpg', 5, 24, NULL),
('Talk Pudrası Extra', 'Talk Pudrası', 60.00, 110, 'resim40.jpg', 5, 25, NULL);

-- erosiller
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Wacker HDK N2O Aerosil Kıvamlaştırıcı', 'Aerosiller', 70.00, 100, 'resim41.jpg', 5, 26, NULL),
('Ekonomik Aerosil Kıvamlaştırıcı', 'Aerosiller', 70.00, 100, 'resim41.jpg', 5, 26, NULL);

-- Alev Geciktirici Dolgu
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('ATH APYRAL 16 Alev Geciktirici Dolgu', 'Alev Geciktirici Dolgu', 80.00, 90, 'resim42.jpg', 5, 29, NULL);

-- Talk Pudrası
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Talk Pudrası Extra', 'Talk Pudrası', 80.00, 90, 'resim42.jpg', 5, 25, NULL);

-- RENKLENDİRİCİLER

-- Polyester ve Poliüretan Pigmentler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Poliya Polipigment Polyester ve Poliüretan Renklendiriciler', 'Polyester ve Poliüretan Pigmentler', 80.00, 90, 'resim42.jpg', 6, 30, NULL),
('Turkuaz Polipigment Polyester ve Poliüretan Renklendiriciler', 'Polyester ve Poliüretan Pigmentler', 80.00, 90, 'resim42.jpg', 6, 30, NULL);

-- Titandioksitler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Dupond R902 Beyaz Titandioksit', 'Titandioksitler', 80.00, 90, 'resim42.jpg', 6, 31, NULL),
('Ekonomik Beyaz Titandioksit', 'Titandioksitler', 80.00, 90, 'resim42.jpg', 6, 31, NULL),
('Carbon Black ‘Siyah’ Titandioksit', 'Titandioksitler', 80.00, 90, 'resim42.jpg', 6, 31, NULL);

-- Epoksi Reçine Pigmentler
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES

('EpoXs Tone Opak Renkler’ Titandioksit', 'Epoksi Reçine Pigmentler', 80.00, 90, 'resim42.jpg', 6, 32, NULL),
('EpoXs Tone Sedefli Renkler', 'Epoksi Reçine Pigmentler', 80.00, 90, 'resim42.jpg', 6, 32, NULL),
('EpoXs Tone Saydam Renkler', 'Epoksi Reçine Pigmentler', 80.00, 90, 'resim42.jpg', 6, 32, NULL);

-- RTV-2 Kalıp Silikonu Renkleri

-- Mek Peroksitler 
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES

('Akpa Kimya Mek Peroksit A1', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Akpa Kimya Mek Peroksit A60', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Akpa Kimya PK295 D50 (SMC/BMC)', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Akpa Kimya AAP (Asetil Aseton Peroksit)', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Akpa Kimya MIKP', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Akpa Kimya BP50 Paste', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Akpa Kimya BP50 Powder', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Akpa Kimya BP75 Powder', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Poliya M50 Butanox', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20),
('Poliya M60 Butanox', 'Mek Peroksitler', 80.00, 90, 'resim42.jpg', 6, 33, 20);

-- Kobalt Oktoatlar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES

('Akpa Kimya SR6 Kobalt Oktoat', 'Kobalt Oktoatlar', 80.00, 90, 'resim42.jpg', 6, 33, 21),
('Akpa Kimya KXC-6 Kobalt Oktoat', 'Kobalt Oktoatlar', 80.00, 90, 'resim42.jpg', 6, 33, 21),
('Akpa Kimya %6 Kobalt Oktoat', 'Kobalt Oktoatlar', 80.00, 90, 'resim42.jpg', 6, 33, 21),
('Akpa Kimya RC88 Şeffaf Kobalt', 'Kobalt Oktoatlar', 80.00, 90, 'resim42.jpg', 6, 33, 21);

-- İnceltici Monomerler ve Kıvamlaştırıcılar
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES
('Turkuaz TPY001 Stiren Monomer (Polyester İnceltici)', 'İnceltici Monomerler ve Kıvamlaştırıcılar', 80.00, 90, 'resim42.jpg', 6, 33, 22),
('Poliya Polyester İnceltici Stiren', 'İnceltici Monomerler ve Kıvamlaştırıcılar', 80.00, 90, 'resim42.jpg', 6, 33, 22),
('Poliya Jelkot İnceltici', 'İnceltici Monomerler ve Kıvamlaştırıcılar', 80.00, 90, 'resim42.jpg', 6, 33, 22),
('Poliya MMA Monomer (Metil Metakrilat)', 'İnceltici Monomerler ve Kıvamlaştırıcılar', 80.00, 90, 'resim42.jpg', 6, 33, 22),
('Poliya D-32 Parafin Reçine Kıvamlaştırıcı (Thixo)', 'İnceltici Monomerler ve Kıvamlaştırıcılar', 80.00, 90, 'resim42.jpg', 6, 33, 22),
('RTV-2 Kalıp Silikonu Kıvamlaştırıcı (Thixo)', 'İnceltici Monomerler ve Kıvamlaştırıcılar', 80.00, 90, 'resim42.jpg', 6, 33, 22);


-- TEMİZLEYİCİ SOLVENTLER
INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES

('Turkuaz TPY003 Polyester Temizleyici (Aseton)', 'TEMİZLEYİCİ SOLVENTLER', 80.00, 90, 'resim42.jpg', 6, 41, NULL),
('Poliya Kalıp Temizleyici Çözelti', 'TEMİZLEYİCİ SOLVENTLER', 80.00, 90, 'resim42.jpg', 6, 41, NULL),
('Mobel Selülozik Tiner', 'TEMİZLEYİCİ SOLVENTLER', 80.00, 90, 'resim42.jpg', 6, 41, NULL),
('Genç Selülozik Tiner', 'TEMİZLEYİCİ SOLVENTLER', 80.00, 90, 'resim42.jpg', 6, 41, NULL),
('Dewilux Selülozik Tiner', 'TEMİZLEYİCİ SOLVENTLER', 80.00, 90, 'resim42.jpg', 6, 41, NULL);


INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES

('Melos Çinko Streat', 'TEMİZLEYİCİ SOLVENTLER', 80.00, 90, 'resim42.jpg', 5, 27, NULL);



-- --------------------------------------------------------



CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL,
  `blog_title` varchar(1000) NOT NULL,
  `blog_desc` varchar(1000) NOT NULL,
  `blog_detail` varchar(5000) NOT NULL,
  `logo` varchar(2000) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (
    `id`, 
    `blog_title`, 
    `blog_desc`, 
    `blog_detail`, 
    `logo`, 
    `updated_at`
) 
VALUES
(
    1, 
    'Endüstriyel Boya Kullanım Alanları', 
    'Endüstriyel boyaların farklı kullanım alanları hakkında bilgi.', 
    'Endüstriyel boyalar, otomotiv, inşaat, denizcilik ve daha birçok sektörde kullanılmaktadır. Bu boyalar, dayanıklılık ve uzun ömürlülük sağlamak için özel olarak formüle edilmiştir. Endüstriyel boyaların kullanım alanları ve avantajları hakkında daha fazla bilgi edinin.', 
    'EL YATIRMASI 1.jpg', 
    CURRENT_TIMESTAMP()
),
(
    2, 
    'Endüstriyel Boya Seçiminde Dikkat Edilmesi Gerekenler', 
    'Endüstriyel boya seçerken nelere dikkat etmelisiniz?', 
    'Endüstriyel boya seçimi, projenizin başarısı için kritik öneme sahiptir. Doğru boyayı seçmek, yüzeyin korunması ve estetik görünümün sağlanması açısından önemlidir. Bu blog yazısında, endüstriyel boya seçiminde dikkat edilmesi gereken faktörleri ele alıyoruz.', 
    'ENDÜSTRİYEL TASARIM.jpg', 
    CURRENT_TIMESTAMP()
),
(
    3, 
    'Endüstriyel Boya Uygulama Teknikleri', 
    'Endüstriyel boyaların doğru uygulanması için teknikler.', 
    'Endüstriyel boyaların doğru uygulanması, yüzeyin dayanıklılığı ve estetik görünümü için önemlidir. Bu yazıda, endüstriyel boya uygulama teknikleri ve püf noktaları hakkında bilgi veriyoruz. Profesyonel uygulama yöntemleri ile en iyi sonuçları elde edin.', 
    'EL YATIRMASI 1.jpg', 
    CURRENT_TIMESTAMP()
),
(
    4, 
    'Endüstriyel Boyaların Çevresel Etkileri', 
    'Endüstriyel boyaların çevre üzerindeki etkileri ve sürdürülebilirlik.', 
    'Endüstriyel boyaların çevresel etkileri, günümüzde önemli bir konudur. Bu yazıda, endüstriyel boyaların çevre üzerindeki etkilerini ve sürdürülebilirlik açısından neler yapılabileceğini ele alıyoruz. Çevre dostu boyalar ve uygulama yöntemleri hakkında bilgi edinin.', 
    'ENDÜSTRİYEL TASARIM.jpg', 
    CURRENT_TIMESTAMP()
),
(
    5, 
    'Endüstriyel Boya ve Korozyon Koruması', 
    'Endüstriyel boyaların korozyon korumasındaki rolü.', 
    'Endüstriyel boyalar, metal yüzeylerin korozyona karşı korunmasında önemli bir rol oynar. Bu yazıda, endüstriyel boyaların korozyon korumasındaki etkilerini ve doğru boya seçimi ile nasıl koruma sağlanabileceğini ele alıyoruz. Korozyon önleyici boyalar hakkında bilgi edinin.', 
    'EL YATIRMASI 1.jpg', 
    CURRENT_TIMESTAMP()
),
(
    6, 
    'Endüstriyel Boya ve Güvenlik Önlemleri', 
    'Endüstriyel boya uygulamalarında güvenlik önlemleri.', 
    'Endüstriyel boya uygulamaları sırasında güvenlik önlemleri almak, iş sağlığı ve güvenliği açısından önemlidir. Bu yazıda, endüstriyel boya uygulamalarında alınması gereken güvenlik önlemlerini ve dikkat edilmesi gereken noktaları ele alıyoruz. Güvenli uygulama yöntemleri hakkında bilgi edinin.', 
    'ENDÜSTRİYEL TASARIM.jpg', 
    CURRENT_TIMESTAMP()
);

-- --------------------------------------------------------

--
-- Table structure for table `logo`
--

CREATE TABLE IF NOT EXISTS`logo` (
  `id` int(11) NOT NULL,
  `logo` varchar(1000) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

--
-- Dumping data for table `logo`
--

INSERT INTO `logo` (`id`, `logo`, `updated_at`) VALUES
(1, 'logo.png', CURRENT_TIMESTAMP());

-- --------------------------------------------------------


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

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
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255),
    `email` VARCHAR(255),
    `phone` VARCHAR(50),
    `message` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

COMMIT;

CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL,
  `service_title` varchar(500) NOT NULL,
  `service_desc` varchar(1000) NOT NULL,
  `service_detail` varchar(2000) NOT NULL,
  `ufile` varchar(1000) NOT NULL,
  `icon` varchar(200) NOT NULL,
  `upadated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (
    `id`, 
    `service_title`, 
    `service_desc`, 
    `icon`,
    `upadated_at`
) 

VALUES  
(  
    2,   
    '81 İLE HIZLI TESLİMAT',   
    'Türkiye genelindeki 81 ile hızlı ve güvenilir teslimat hizmeti sunuyoruz. Siparişleriniz güvenli şekilde adresinize teslim edilir.',  
    '<i class="material-icons">local_shipping</i>',  
    CURRENT_TIMESTAMP()
    ),  
(  
    3,   
    'TEKNİK DESTEK',   
    'Her türlü teknik sorununuzda uzman ekibimiz yanınızda. Sorunlarınıza hızlı ve etkili çözümler sunuyoruz.',  
    '<i class="material-icons">build</i>',   
    CURRENT_TIMESTAMP()
),  
(  
    4,   
    'YÜKSEK ÜRÜN KALİTESİ',   
    'Sizlere en iyi ürün deneyimini sunmak için yüksek kalite standartlarında üretim yapıyoruz. Ürünlerimiz güvenilir ve dayanıklıdır.',  
    '<i class="material-icons">percent</i>',   
    CURRENT_TIMESTAMP()
);  



-- Table structure for table `siteconfig`
--

CREATE TABLE IF NOT EXISTS `siteconfig` (
  `id` int(11) NOT NULL,
  `site_keyword` varchar(1000) NOT NULL,
  `site_desc` varchar(500) NOT NULL,
  `site_title` varchar(300) NOT NULL,
  `site_about` varchar(1000) NOT NULL,
  `site_footer` varchar(1000) NOT NULL,
  `follow_text` varchar(1000) NOT NULL,
  `site_url` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

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
  `email` varchar(100) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

--
-- Dumping data for table `sitecontact`
--

INSERT INTO `sitecontact` (`id`, `phone1`, `phone2`, `email`, `updated_at`) VALUES
(1, '+90 0312 394 44 21', '+90 0312 350 39 50', 'baranboya@gmail.com', '2024-03-11 11:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--




-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE IF NOT EXISTS `social` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `fa` varchar(150) NOT NULL,
  `social_link` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

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

CREATE TABLE IF NOT EXISTS `static` (
  `id` int(11) NOT NULL,
  `stitle` varchar(150) NOT NULL,
  `stext` varchar(500) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_turkish_ci;

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

CREATE TABLE IF NOT EXISTS `tedarikcilerimiz` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `resim` VARCHAR(1000) NOT NULL,  -- Resim dosya yolu veya adı
  `guncellenme_tarihi` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;


INSERT INTO tedarikcilerimiz (resim, guncellenme_tarihi)
VALUES 
    ('Logo_mobile.png', CURRENT_TIMESTAMP()),
    ('dyson.png', CURRENT_TIMESTAMP()),
    ('DuPont_tm_rgb.png', CURRENT_TIMESTAMP()),
    ('WACKER.png', CURRENT_TIMESTAMP()),
    ('JUSHI.png', CURRENT_TIMESTAMP()),
    ('AKPA.png', CURRENT_TIMESTAMP()),
    ('ŞİŞECAM.png', CURRENT_TIMESTAMP()),
    ('POLİYA LOGO.png', CURRENT_TIMESTAMP()),
    ('Turkuaz.png', CURRENT_TIMESTAMP());

-- --------------------------------------------------------
-- --------------------------------------------------------
--
-- Table structure for table `sektörler`
--

--
-- Table structure for table `why_us`
--

--
-- Dumping data for table `why_us`
--


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


