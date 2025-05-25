<?php
include"../z_db.php";
$username=$_SESSION['username'];
?>
<div class="app-menu navbar-menu">
  <!-- LOGO -->
  <div class="navbar-brand-box">
    <!-- Dark Logo-->
<?php
    $rr=mysqli_query($con,"SELECT logo FROM logo");
$r = mysqli_fetch_row($rr);
$ufile = $r[0];
?>

    <a href="home" class="logo logo-dark">
      <span class="logo-sm">
        <img src="../assets/img/logo/<?php print $ufile?>" alt="" height="22">
      </span>
      <span class="logo-lg">
        <img src="../assets/img/logo/<?php print $ufile?>" alt="" height="30">
      </span>
    </a>
    <!-- Light Logo-->
    <a href="home" class="logo logo-light">
      <span class="logo-sm">
        <img src="../assets/img/logo/<?php print $ufile?>" alt="" height="22">
      </span>
      <span class="logo-lg">
        <img src="../assets/img/logo/<?php print $ufile?>" alt="" height="30">
      </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
      <i class="ri-record-circle-line"></i>
    </button>
  </div>

  <div id="scrollbar">
    <div class="container-fluid">

      <div id="two-column-menu">
      </div>
      <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>


        <li class="nav-item">
                <a href="home" class="nav-link" data-key="t-analytics">  <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards"> Admin Paneli </span></a>
              </li>

              <li class="nav-item">
                            <a class="nav-link menu-link" href="blog" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-file-list-3-line"></i> <span data-key="t-landing">Blog Ayarları</span>
                            </a>
                        </li>

              <li class="nav-item">
                            <a class="nav-link menu-link" href="services"  role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-checkbox-multiple-line"></i> <span data-key="t-landing">Servis Ayarları</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="socials"  role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-chrome-fill"></i> <span data-key="t-landing">Sosyal Medya</span>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a class="nav-link menu-link" href="sitesettings.php?action=site_config"  role="button" aria-expanded="true" aria-controls="sidebarLanding">
                                <i class="ri-tools-fill"></i> <span data-key="t-landing"> Site Ayarları </span>
                            </a>
                            
                        </li>
                        <li class="nav-item">
                          <a class="nav-link menu-link" href="products" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                            <i class="ri-shopping-bag-3-line"></i> <span data-key="t-landing"> Ürünler </span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link menu-link" href="categorys" role="button" aria-expanded="true" aria-controls="sidebarLanding">
                            <i class="ri-list-unordered"></i> <span data-key="t-landing"> Kategoriler </span>
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
