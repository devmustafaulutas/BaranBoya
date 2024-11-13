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
    `isim` VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4;

-- Alt kategoriler tablosu, ana kategorilere bağlı
CREATE TABLE `alt_kategoriler` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(255) NOT NULL,
    `kategori_id` INT,
    FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler`(`id`) ON DELETE CASCADE
) CHARACTER SET utf8mb4;

-- Alt kategorilerin alt kategorileri, alt kategorilere bağlı
CREATE TABLE `alt_kategoriler_alt` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `isim` VARCHAR(255) NOT NULL,
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

INSERT INTO `kategoriler` (`isim`) VALUES

('Reçineler'),
('Jelkotlar'),
('Cam Elyaflar'),
('Kalıp Ayırıcılar'),
('Dolgu Malzemeleri'),
('Renklendiriciler'),
('Sarf Malzemeleri');

INSERT INTO `alt_kategoriler` (`isim`, `kategori_id`) VALUES
('Polyester Reçineler', 1),
('Vinilester Reçineler', 1),
('Epoksi Reçineler', 1),
('Poliüretan Reçine', 1),
('Reçine Yardımcıları', 1),

('Genel Amaçlı Jelkotlar', 2),
('Performans Jelkotlar', 2),
('Yüksek Performanslı Jelkotlar', 2),
('Zımparalanabilir Jelkotlar', 2),
('Kalıplama Jelkotları', 2),
('Marin Uygulama Jelkotları', 2),
('Kimyasal Dayanımlı Jelkotlar', 2),
('Alev İlerletmeyen Jelkotlar', 2),

('Cam Elyaf Keçeler', 3),
('Cam Elyaf Dokuma Kumaşlar', 3),
('Karbon Elyaf Dokuma Kumaşlar', 3),
('Fitil İp Elyaflar', 3),
('Kırpık Elyaflar', 3),
('Yüzey Tülü (Kortel)', 3),
('Corematlar', 3),
('RTM Elyaflar', 3),

('Vaks Kalıp Ayırıcılar', 4),
('Sprey Kalıp Ayırıcılar', 4),
('Sıvı Kalıp Ayırıcılar', 4),

('Kalsitler', 5),
('Talk Pudrası', 5),
('Aerosil', 5),
('Çinko Streat', 5),
('Alüminyum Tozu', 5),
('Alev Geciktirici Dolgu', 5),

('Polyester ve Poliüretan Renklendirici Pigmentler', 6),
('Titandioksitler', 6),
('Epoksi Reçine Pigmentler', 6),
('RTV-2 Kalıp Silikonu Pigmentleri', 6),

('Fırçalar ve Rulolar', 7),
('Tulumlar ve Maskeler', 7),
('Jelkot Tabancaları', 7),
('Zımpara Makineleri ve Zımparalar', 7),
('Polyester Ezme Rulolar', 7),
('Bantlar', 7),
('Ölçüm ve Dozajlama Ekipmanları', 7);


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
('Köpük Eritmeyen Polyester Reçineler', 1);

-- "Epoksi Reçineler" alt kategorisinin alt kategorileri
INSERT INTO `alt_kategoriler_alt` (`isim`, `alt_kategori_id`) VALUES
('İnce Döküm Epoksi Reçineler', 3),
('Kalın Döküm Epoksi Reçineler', 3),
('Laminasyon Tipi Epoksi Reçineler', 3);

-- "Vinilester Reçineler" alt kategorisinin alt kategorileri
INSERT INTO `alt_kategoriler_alt` (`isim`, `alt_kategori_id`) VALUES
('Bisfenol-A Vinilester Reçineler', 2),
('Novalak Vinilester Reçineler', 2),
('Amin Hızlandırıcı Vinilester Reçineler', 2),
('Bromine Epoksi Vinilester Reçineler', 2);


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
(1, 'assets/img/favicon.png', 'assets/img/favicon.png', '2024-11-01 16:17:29');

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
    'We provide the best digital services', 
    'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.', 
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
    `ufile`, 
    `upadated_at`
) 
VALUES
(
    2, 
    'Affiliate Marketing', 
    'Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.', 
    'Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.',
    '5645portrait-happy-young-black-woman-posing-office_116547-21539.webp', 
    '2022-07-17 21:19:37'
),
(
    3, 
    'Email Marketing', 
    'Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.', 
    'Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.',
    '9668788-7884680_hero-headshot-sitting-hd-png-download.jpg', 
    '2022-07-17 21:20:13'
),
(
    4, 
    'Website Growth', 
    'Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.', 
    'Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.\r\n\r\nLearn More Lorem ipsum dolor sit amet, consectet ur adipisicing elit, sed do eiusmod tempor incididunt emit.\r\n\r\nLearn More', 
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

INSERT INTO `social` (`id`, `name`, `fa`, `social_link`) VALUES
(1, 'Facebook', 'fa-facebook', 'https://www.facebook.com/baranpolyester'),
(2, 'Instagram', 'fa-instagram', 'https://www.instagram.com/baranboyaepoxs/')
(2, 'Instagram', 'fa-twitter', 'https://x.com/baranboya');

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
    'Baran Boya', 
    '',
    '2022-07-17 18:38:04'
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

--
-- Dumping data for table `testimony`
--
INSERT INTO `tedarikcilerimiz` (`id`, `resim`, `guncellenme_tarihi`)
VALUES
(1, "dashboard/uploads/tedarikcilerimiz/AKPA.png", CURRENT_TIMESTAMP),
(2, "dashboard/uploads/tedarikcilerimiz/JUSHI.png", CURRENT_TIMESTAMP),
(3, "dashboard/uploads/tedarikcilerimiz/MARKER.png", CURRENT_TIMESTAMP),
(4, "dashboard/uploads/tedarikcilerimiz/POLİKOR.png", CURRENT_TIMESTAMP),
(5, "dashboard/uploads/tedarikcilerimiz/STARGİL.png", CURRENT_TIMESTAMP),
(6, "dashboard/uploads/tedarikcilerimiz/ŞİŞECAM.png", CURRENT_TIMESTAMP),
(7, "dashboard/uploads/tedarikcilerimiz/WACKER.png", CURRENT_TIMESTAMP);

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
-- Indexes for table `testimony`
--
ALTER TABLE `testimony`
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
