<?php include "z_db.php";?>
<!doctype html>
<html class="no-js" lang="en">
<?php
    $rrs=mysqli_query($con,"SELECT * FROM section_title Where id=1");
$rs = mysqli_fetch_array($rrs);
$test_title = "$rs[test_title]";
$test_text="$rs[test_text]";
$enquiry_title="$rs[enquiry_title]";
$enquiry_text="$rs[enquiry_text]";
$enquiry_text="$rs[enquiry_text]";
$contact_title="$rs[contact_title]";
$contact_text="$rs[contact_text]";
$port_title="$rs[port_title]";
$port_text="$rs[port_text]";
$service_title="$rs[service_title]";
$service_text="$rs[service_text]";
$why_title="$rs[why_title]";
$why_text="$rs[why_text]";
$about_title="$rs[about_title]";
$about_text="$rs[about_text]";
?>



<?php
    $rt=mysqli_query($con,"SELECT * FROM sitecontact where id=1");
    $tr = mysqli_fetch_array($rt);
    $phone1 = "$tr[phone1]";
    $phone2 = "$tr[phone2]";
    $email1 = "$tr[email1]";
    $email2 = "$tr[email2]";
    $longitude = "$tr[longitude]";
    $latitude = "$tr[latitude]";
?>
<!-- Mirrored from theme-land.com/digimx/demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Jul 2022 15:12:40 GMT -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta Description -->
    <meta name="description" content="">
    <meta name="author" content="Themeland">
    <?php
    $rr=mysqli_query($con,"SELECT * FROM siteconfig where id=1");
    $r = mysqli_fetch_array($rr);
    $site_title = "$r[site_title]";
    $site_about = "$r[site_about]";
    $site_footer = "$r[site_footer]";
    $follow_text = "$r[follow_text]";
?>
    <!-- Title  -->
    <title>Baran Boya - <?php print $site_title ?></title>

    <!-- Favicon  -->
    <link rel="icon" href="assets/img/favicon.png">

    <!-- ***** All CSS Files ***** -->

    <!-- Style css -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Responsive css -->
    <link rel="stylesheet" href="assets/css/responsive.css">

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
                $rt = mysqli_query($con, "SELECT ufile FROM logo WHERE id=1");
                $tr = mysqli_fetch_array($rt);
                $ufile = "$tr[ufile]";
                ?>
                <a class="navbar-brand" href="home">
                    <img class="navbar-brand-regular" id="header-logo1" src="dashboard/uploads/logo/<?php print $ufile ?>" alt="brand-logo">
                    <img class="navbar-brand-sticky" id="header-logo2" src="dashboard/uploads/logo/<?php print $ufile ?>" alt="sticky brand-logo">
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
                            <a href="portfolio" class="nav-link">Blog</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Ürünler
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php while ($category = mysqli_fetch_array($categories_query)) { ?>
                                <div class="dropdown-submenu">
                                    <a class="dropdown-item" href="urunler.php?kategori_id=<?php echo $category['id']; ?>">
                                        <?php echo $category['isim']; ?>
                                    </a>
                                    <?php
                                    // Mevcut kategoriye ait alt kategorileri getir
                                    $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = " . $category['id']);
                                    ?>
                                    <div class="dropdown-divider"></div>
                                    <?php if (mysqli_num_rows($subcategories_query) > 0) { ?>
                                        <ul class="dropdown-menu">
                                            <?php while ($subcategory = mysqli_fetch_array($subcategories_query)) { ?>
                                                <li>
                                                    <a class="dropdown-item" href="urunler.php?kategori_id=<?php echo $category['id']; ?>&alt_kategori_id=<?php echo $subcategory['id']; ?>">
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

