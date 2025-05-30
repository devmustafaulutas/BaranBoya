<?php
require 'z_db.php';
if (!function_exists('encrypt_id')) {
    function encrypt_id($id)
    {
        $key = 'gizli-anahtar';
        $cipher = openssl_encrypt($id, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
        return base64_encode($cipher);
    }
}

$cats = mysqli_query($con, "SELECT * FROM kategoriler");
$altCats = mysqli_query($con, "SELECT * FROM alt_kategoriler");

$stmt = $con->prepare("SELECT phone1, phone2, email FROM sitecontact WHERE id = ?");
$id = 1;
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($phone1, $phone2, $email);
$stmt->fetch();
$stmt->close();

$stmt = $con->prepare("
    SELECT site_keyword,
           site_desc,
           site_title,
           site_about,
           site_footer,
           follow_text,
           site_url
      FROM siteconfig
     WHERE id = ?
");
$id = 1;
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result(
    $site_keywords,
    $site_description,
    $site_title,
    $site_about,
    $site_footer,
    $follow_text,
    $site_url
);
$stmt->fetch();
$stmt->close();
?>
<!doctype html>
<html class="no-js" lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($site_keywords, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="author" content="Baran Boya">

    <meta property="og:title" content="<?= htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= rtrim($site_url, '/') ?>/assets/img/og-image.jpg">
    <meta property="og:url" content="<?= rtrim($site_url, '/') ?>">
    <meta property="og:type" content="website">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($site_title, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($site_description, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= rtrim($site_url, '/') ?>/assets/img/og-image.jpg">

    <link rel="icon" href="<?= rtrim($site_url, '/') ?>/favicon.ico" type="image/x-icon">


    <link rel="stylesheet" href="assets/css/new-section.css">
    <link rel="stylesheet" href="assets/css/sektor.css">
    <link rel="stylesheet" href="assets/css/background.css">
    <link rel="icon" href="assets/img/favicon.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-…" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>


    <body>
        <div id="preloader">
            <div id="digimax-preloader" class="digimax-preloader">
                <div class="preloader-animation">
                    <div class="spinner"></div>
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
                <div class="loader-animation">
                    <div class="row h-100">
                        <div class="col-3 single-loader p-0">
                            <div class="loader-bg"></div>
                        </div>
                        <div class="col-3 single-loader p-0">
                            <div class="loader-bg"></div>
                        </div>
                        <div class="col-3 single-loader p-0">
                            <div class="loader-bg"></div>
                        </div>
                        <div class="col-3 single-loader p-0">
                            <div class="loader-bg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="scrollUp" title="Scroll To Top">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div class="main overflow-hidden">
            <header id="header">
                <nav data-aos="zoom-out" data-aos-delay="800" class="navbar navbar-expand">
                    <div id="header-container" class="container header">
                        <?php
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
                            <img class="navbar-brand-regular" id="header-logo1"
                                src="assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES, 'UTF-8'); ?>">
                            <img class="navbar-brand-sticky" id="header-logo2"
                                src="assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES, 'UTF-8'); ?>">
                        </a>
                        <div class="ml-auto">
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
                                <?php
                                $catsRes = mysqli_query($con, "SELECT * FROM kategoriler");
                                $categories = [];
                                while ($r = mysqli_fetch_assoc($catsRes)) {
                                    $categories[$r['id']] = $r;
                                }
                                $altRes = mysqli_query($con, "SELECT * FROM alt_kategoriler");
                                $subcats = [];
                                while ($r = mysqli_fetch_assoc($altRes)) {
                                    $subcats[$r['kategori_id']][] = $r;
                                }
                                ?>
                                <?php
                                $catsRes = mysqli_query($con, "SELECT * FROM kategoriler");
                                $categories = [];
                                while ($r = mysqli_fetch_assoc($catsRes)) {
                                    $categories[$r['id']] = $r;
                                }
                                $altRes = mysqli_query($con, "SELECT * FROM alt_kategoriler");
                                $subcats = [];
                                while ($r = mysqli_fetch_assoc($altRes)) {
                                    $subcats[$r['kategori_id']][] = $r;
                                }
                                ?>
                                <li class="nav-item dropdown mega-dropdown">
                                    <a href="#" class="nav-link dropdown-toggle">Ürünler</a>
                                    <div class="mega-menu">
                                        <div class="mega-menu-col categories">
                                            <ul>
                                                <?php foreach ($categories as $cat): ?>
                                                    <li data-cat-id="<?= $cat['id'] ?>">
                                                        <a href="urunler.php?kategori_id=<?= rawurlencode(encrypt_id($cat['id'])) ?>"
                                                            class="category-link">
                                                            <?= htmlspecialchars($cat['isim']) ?>
                                                        </a>
                                                        <?php if (!empty($subcats[$cat['id']])): ?>
                                                            <span class="arrow">›</span>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="mega-menu-col subcats">
                                            <?php foreach ($categories as $cat): ?>
                                                <ul class="subcat-list" data-parent-id="<?= $cat['id'] ?>">
                                                    <?php foreach ($subcats[$cat['id']] ?? [] as $sc): ?>
                                                        <li>
                                                            <a href="urunler.php?kategori_id=<?= rawurlencode(encrypt_id($cat['id'])) ?>&alt_kategori_id=<?= rawurlencode(encrypt_id($sc['id'])) ?>"
                                                                class="dropdown-item">
                                                                <?= htmlspecialchars($sc['isim']) ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="https://tahsilat.baranboya.com/Payment/UnAuthenticatedPayment?notAut=True"
                                        target="_blank" class="nav-link">Online Tahsilat</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.kalipsilikonu.com/" target="_blank" class="nav-link">Online
                                        Alışveriş</a>
                                </li>
                                <li class="nav-item">
                                    <a href="contact" class="nav-link">İletişim</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav toggle">
                                <li class="nav-item">
                                    <a href="#" class="nav-link toggle-btn"><i
                                            class="fas fa-bars toggle-icon m-0"></i></a>
                                </li>
                            </ul>

                        </div>
                    </div>
                </nav>
                <div id="mobileMenu" class="mobile-menu-panel">
                    <div class="mobile-menu-header">
                        <span>Menu</span>
                        <button type="button" class="close-btn">&times;</button>
                    </div>
                    <div class="mobile-menu-content">
                        <ul class="mm-list">
                            <li><a href="home">Ana Sayfa</a></li>
                            <li><a href="about">Hakkımızda</a></li>
                            <li><a href="blog">Blog</a></li>

                            <li class="has-submenu">
                                <a href="#" class="submenu-toggle">Ürünler <span class="arrow">›</span></a>
                                <ul class="submenu">
                                    <?php foreach ($categories as $cat): ?>
                                        <li class="has-submenu">
                                            <a href="#" class="submenu-toggle">
                                                <?= htmlspecialchars($cat['isim']) ?>
                                                <?php if (!empty($subcats[$cat['id']])): ?>
                                                    <span class="arrow">›</span>
                                                <?php endif; ?>
                                            </a>
                                            <?php if (!empty($subcats[$cat['id']])): ?>
                                                <ul class="submenu">
                                                    <?php foreach ($subcats[$cat['id']] as $sc): ?>
                                                        <li>
                                                            <a
                                                                href="urunler.php?kategori_id=<?= rawurlencode(encrypt_id($cat['id'])) ?>&alt_kategori_id=<?= rawurlencode(encrypt_id($sc['id'])) ?>">
                                                                <?= htmlspecialchars($sc['isim']) ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>

                            <li> <a href="https://www.kalipsilikonu.com/" target="_blank" class="nav-link">Online
                                    Alışveriş</a></li>
                            <li> <a href="https://tahsilat.baranboya.com/Payment/UnAuthenticatedPayment?notAut=True"
                                    target="_blank" class="nav-link">Online Tahsilat</a></li>
                            <li><a href="contact">İletişim</a></li>
                        </ul>
                    </div>
                </div>
            </header>