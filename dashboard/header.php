<?php
require_once __DIR__ . '/../z_db.php';

$script = basename($_SERVER['SCRIPT_NAME']);
// Eğer ne `username` ne de `temp_username` yoksa yönlendir
if (empty($_SESSION['username']) && empty($_SESSION['temp_username']) && $script !== 'twofa.php') {
    echo "<script>window.location='login.php';</script>";
    exit;
}


?>
<!doctype html>
<html lang="tr" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | BaranBoya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="../assets/img/favicon.png">

    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/blog.css" rel="stylesheet" />
    <link href="assets/css/custom.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet" />
    
    <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" />
    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" />
    
    
    <script src="assets/js/index.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link href="assets/css/app.min.css" rel="stylesheet" />
  <script>
    (function(){
      try {
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
          document.documentElement.classList.add('sidebar-collapsed');
        }
      } catch (e) {
      }
    })();
  </script>
</head>
<body>
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <!-- Hamburger / Menü -->
                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                        id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                    <!-- Sağ tarafa yerleştirilen kullanıcı/buton alanı -->
                    <div class="d-flex align-items-center">
                        <!-- İsteğe bağlı: Kullanıcı adına tıklayınca profil sayfasına gidebilirsiniz -->
                        <span class="me-3 text-white">Merhaba, <?= htmlspecialchars($username, ENT_QUOTES) ?></span>

                        <!-- Çıkış Yap butonu -->
                        <a href="logout.php"
                           class="btn btn-outline-light btn-sm"
                           style="margin-right: 1rem;">
                            <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                        </a>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <!-- Diğer içerikler… -->

    <!-- JS dosyaları en sona -->
    <script src="assets/js/dashboard.js"></script>
</body>
</html>