<?php 
// dashboard/sidebar.php

// 1) Oturum zaten init.php tarafından kontrol edildi, ekstra session_start() gerekmez
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// 2) $con, init.php vasıtasıyla z_db.php’de hazırlandı
//    Bu yüzden doğrudan veritabanı sorgusu yapabiliriz.
$ufile = '';
if (isset($con) && $con instanceof mysqli) {
    $rr = $con->query("SELECT logo FROM logo LIMIT 1");
    if ($rr && $rr->num_rows > 0) {
        $row   = $rr->fetch_assoc();
        $ufile = $row['logo'] ?? '';
    }
}
?>
<div class="app-menu navbar-menu">
  <!-- LOGO -->
  <div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="home" class="logo logo-dark">
      <span class="logo-sm">
        <?php if ($ufile !== ''): ?>
          <img src="../assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES); ?>" alt="Logo" height="22">
        <?php else: ?>
          <img src="../assets/img/logo/default-logo.png" alt="Logo Yok" height="22">
        <?php endif; ?>
      </span>
      <span class="logo-lg">
        <?php if ($ufile !== ''): ?>
          <img src="../assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES); ?>" alt="Logo" height="30">
        <?php else: ?>
          <img src="../assets/img/logo/default-logo.png" alt="Logo Yok" height="30">
        <?php endif; ?>
      </span>
    </a>
    <!-- Light Logo-->
    <a href="home" class="logo logo-light">
      <span class="logo-sm">
        <?php if ($ufile !== ''): ?>
          <img src="../assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES); ?>" alt="Logo" height="22">
        <?php else: ?>
          <img src="../assets/img/logo/default-logo.png" alt="Logo Yok" height="22">
        <?php endif; ?>
      </span>
      <span class="logo-lg">
        <?php if ($ufile !== ''): ?>
          <img src="../assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES); ?>" alt="Logo" height="30">
        <?php else: ?>
          <img src="../assets/img/logo/default-logo.png" alt="Logo Yok" height="30">
        <?php endif; ?>
      </span>
    </a>
  </div>

  <div id="scrollbar">
    <div class="container-fluid">
      <div id="two-column-menu"></div>
      <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title d-flex justify-content-between align-items-center">
          <span data-key="t-menu">Menu</span>
          <button id="sidebar-close-btn" class="close-sidebar-btn">
            <i class="fas fa-times-circle"></i>
          </button>
        </li>

        <li class="nav-item">
          <a href="home" class="nav-link" data-key="t-analytics">
            <i class="ri-dashboard-2-line"></i>
            <span data-key="t-dashboards">Admin Paneli</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link menu-link" href="blog" role="button" aria-expanded="true" aria-controls="sidebarLanding">
            <i class="ri-file-list-3-line"></i>
            <span data-key="t-landing">Blog Ayarları</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link menu-link" href="services" role="button" aria-expanded="true" aria-controls="sidebarLanding">
            <i class="ri-checkbox-multiple-line"></i>
            <span data-key="t-landing">Servis Ayarları</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link menu-link" href="suppliers" role="button" aria-expanded="true" aria-controls="sidebarLanding">
            <i class="ri-truck-line"></i>
            <span data-key="t-landing">Tedarikçi Ayarları</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link menu-link" href="socials" role="button" aria-expanded="true" aria-controls="sidebarLanding">
            <i class="ri-chrome-fill"></i>
            <span data-key="t-landing">Sosyal Medya</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link site-settings-toggle collapsed"
             data-bs-toggle="collapse"
             href="#sidebarSiteSettings"
             role="button"
             aria-expanded="false"
             aria-controls="sidebarSiteSettings">
            <i class="ri-tools-fill fs-5"></i>
            <span class="ms-2 flex-grow-1">Site Ayarları</span>
            <i class="ri-arrow-down-s-line site-settings-arrow ms-auto"></i>
          </a>
          <div class="collapse menu-dropdown ps-4" id="sidebarSiteSettings">
            <ul class="nav flex-column list-unstyled ps-0">
              <li class="nav-item">
                <a href="siteconfig.php" class="nav-link site-settings-item">
                  <i class="ri-settings-3-line me-1"></i> Genel Ayarlar
                </a>
              </li>
              <li class="nav-item">
                <a href="sitecontact.php" class="nav-link site-settings-item">
                  <i class="ri-phone-line me-1"></i> İletişim Bilgileri
                </a>
              </li>
              <li class="nav-item">
                <a href="logo.php" class="nav-link" data-key="t-logo">
                  <i class="ri-image-line me-1"></i> Logo
                </a>
              </li>
              <li class="nav-item">
                <a href="twofa.php" class="nav-link" data-key="t-2fa">
                  <i class="ri-shield-keyhole-line me-1"></i> 2 Aşamalı Doğrulama
                </a>
              </li>
              <li class="nav-item">
                <a href="static.php" class="nav-link" data-key="t-static">
                  <i class="ri-file-text-line me-1"></i> Statik İçerik
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link menu-link" href="products" role="button" aria-expanded="true" aria-controls="sidebarLanding">
            <i class="ri-shopping-bag-3-line"></i>
            <span data-key="t-landing">Ürünler</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link menu-link" href="categorys.php" role="button" aria-expanded="true" aria-controls="sidebarLanding">
            <i class="ri-list-unordered"></i>
            <span data-key="t-landing">Kategoriler</span>
          </a>
        </li>

      </ul>
    </div>
    <!-- Sidebar -->
  </div>

  <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
