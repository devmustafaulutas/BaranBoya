<?php include "z_db.php";?>

<!doctype html>
<html class="no-js" lang="en">
<?php
    // encrypt_id() fonksiyonunu sadece tanımlanmamışsa tanımlayın
    if (!function_exists('encrypt_id')) {
        function encrypt_id($id) {
            $key = "your-encryption-key"; // Güvenli bir anahtar kullanın
            return openssl_encrypt($id, 'AES-128-ECB', $key);
        }
    }

    // sitecontact tablosundan verileri hazırlıklı ifadelerle alın
    $stmt = $con->prepare("SELECT phone1, phone2, email FROM sitecontact WHERE id = ?");
    if (!$stmt) {
        die("Sorgu hazırlanamadı: (" . $con->errno . ") " . $con->error);
    }
    $id = 1;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($phone1, $phone2, $email);
    $stmt->fetch();
    $stmt->close();

    // siteconfig tablosundan verileri hazırlıklı ifadelerle alın
    $stmt = $con->prepare("SELECT site_title, site_about, site_footer, follow_text FROM siteconfig WHERE id = ?");
    if (!$stmt) {
        die("Sorgu hazırlanamadı: (" . $con->errno . ") " . $con->error);
    }
    $id = 1;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($site_title, $site_about, $site_footer, $follow_text);
    $stmt->fetch();
    $stmt->close();
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta Description -->
    <meta name="description" content="<?php echo htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($site_keywords, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="Themeland">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="path/to/image.jpg">
    <meta property="og:url" content="<?php echo htmlspecialchars($siteconfig['site_url'], ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="path/to/image.jpg">
    
    <!-- Title  -->
    <title><?php echo htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8'); ?></title>

    <link rel="stylesheet" href="assets/css/new-section.css">
    <link rel="stylesheet" href="assets/css/sektor.css">
    <!-- Favicon  -->
    <link rel="icon" href="assets/img/favicon.png">

    <!-- ***** All CSS Files ***** -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- Style css -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Responsive css -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <!--====== Preloader Area Start ======-->
    <div id="preloader">
        <!-- Digimax Preloader -->
        <div id="digimax-preloader" class="digimax-preloader">
            <!-- Preloader Animation -->
            <div class="preloader-animation">
                <!-- Spinner -->
                <div class="spinner"></div>
                <!-- Loader -->
                <div class="loader">
                    <span data-text-preloader="B" class="animated-letters">B</span>
                    <span data-text-preloader="A" class="animated-letters">A</span>
                    <span data-text-preloader="R" class="animated-letters">R</span>
                    <span data-text-preloader="A" class="animated-letters">A</span>
                    <span data-text-preloader="N" class="animated-letters">N</span>
                    <span data-text-preloader="B" class="animated-letters">B</span>
                    <span data-text-preloader="O" class="animated-letters">O</span>
                    <span data-text-preloader="Y" class="animated-letters">Y</span>
                    <span data-text-preloader="A" class="animated-letters">A</span>
                </div>
                <p class="fw-5 text-center text-uppercase">Yükleniyor</p>
            </div>
            <!-- Loader Animation -->
            <div class="loader-animation">
                <div class="row h-100">
                    <!-- Single Loader -->
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                    <!-- Single Loader -->
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                    <!-- Single Loader -->
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                    <!-- Single Loader -->
                    <div class="col-3 single-loader p-0">
                        <div class="loader-bg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====== Preloader Area End ======-->

    <!--====== Scroll To Top Area Start ======-->
    <div id="scrollUp" title="Scroll To Top">
        <i class="fas fa-arrow-up"></i>
    </div>
    <!--====== Scroll To Top Area End ======-->

    <div class="main overflow-hidden">
    <header id="header">
        <nav data-aos="zoom-out" data-aos-delay="800" class="navbar navbar-expand">
            <div id="header-container" class="container header">
                <?php
                // Logo verilerini hazırlıklı ifadelerle alın
                $stmt = $con->prepare("SELECT logo FROM logo WHERE id = ?");
                if (!$stmt) {
                    die("Sorgu hazırlanamadı: (" . $con->errno . ") " . $con->error);
                }
                $id = 1;
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->bind_result($ufile);
                $stmt->fetch();
                $stmt->close();
                ?>
                <a class="navbar-brand" href="home" style="margin-left: 30px;">
                    <img class="navbar-brand-regular" id="header-logo1" src="assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES, 'UTF-8'); ?>">
                    <img class="navbar-brand-sticky" id="header-logo2" src="assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES, 'UTF-8'); ?>">
                </a>
                <div class="ml-auto">
                    <?php
                    // Ana kategorileri al
                    $categories_query = mysqli_query($con, "SELECT * FROM kategoriler");
                    ?>
                    <ul class="navbar-nav items">
                        <li class="nav-item">
                            <a class="nav-link" href="home">Ana Sayfa</a>
                        </li>
                        <li class="nav-item">
                            <a href="about" class="nav-link">Hakkımızda</a>
                        </li>
                        <li class="nav-item">
                            <a href="blog" class="nav-link">Blog</a>
                        </li>
                        <li class="nav-item dropdown">
                            <!-- Desktop-only link that triggers dropdown on hover -->
  <a href="urunler" class="nav-link dropdown-toggle d-none d-md-block" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
    Ürünler
  </a>

  <!-- Mobile-only button for toggling dropdown -->
  <button
    class="d-md-none d-flex justify-content-between align-items-center w-100"

  >
  <a href="urunler" class="nav-link" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
    Ürünler
  </a>
    <span class="dropdown-toggle"     id="dropdownToggle"
    data-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false"></span> <!-- Down arrow on the right -->
  </button>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php while ($category = mysqli_fetch_array($categories_query)) { ?>
                                    <div class="dropdown-submenu">
                                        <a class="dropdown-item menu-link" href="urunler.php?kategori_id=<?php echo encrypt_id($category['id']); ?>">
                                            <?php echo $category['isim']; ?>
                                        </a>
                                        <?php
                                        // Mevcut kategoriye ait alt kategorileri getir
                                        $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = " . $category['id']);
                                        ?>
                                        <?php if (mysqli_num_rows($subcategories_query) > 0) { ?>
                                            <ul class="dropdown-menu">
                                                <?php while ($subcategory = mysqli_fetch_array($subcategories_query)) { ?>
                                                    <li class="dropdown-submenu">
                                                        <a class="dropdown-item menu-link" href="urunler.php?kategori_id=<?php echo encrypt_id($category['id']); ?>&alt_kategori_id=<?php echo encrypt_id($subcategory['id']); ?>">
                                                            <?php echo $subcategory['isim']; ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </div>

                                <?php } ?>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="https://tahsilat.baranboya.com/Payment/UnAuthenticatedPayment?notAut=True" target="_blank" class="nav-link">Online Tahsilat</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.kalipsilikonu.com/" target="_blank" class="nav-link">Online Alışveriş</a>
                        </li>
                        <li class="nav-item">
                            <a href="contact" class="nav-link">İletişim</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav toggle">
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-toggle="modal" data-target="#menu">
                                <i class="fas fa-bars toggle-icon m-0"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
            <!-- ***** Header End ***** -->



