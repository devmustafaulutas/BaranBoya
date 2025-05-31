<?php
require_once __DIR__ . '/../z_db.php';


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    print "
				<script language='javascript'>
					window.location = 'login.php';
				</script>
			";
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

</head>

<body>


    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                        id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>

                </div>
            </div>
    </div>
    </header>
</body>
</html>