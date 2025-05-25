<?php
session_start();
if (empty($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
include "header.php";
include "sidebar.php";
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- sayfa başlığı vs. -->

      <!-- Kartlar -->
      <div class="row g-3">
        <div class="col-lg-4 col-md-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="avatar-sm flex-shrink-0">
                  <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                    <i class="ri-git-merge-fill"></i>
                  </span>
                </div>
                <div class="flex-grow-1 ms-3">
                  <?php
                    $r = mysqli_fetch_row(mysqli_query($con, "SELECT count(*) FROM service"));
                  ?>
                  <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Toplam Servisler</p>
                  <h4 class="mb-0"><span class="counter-value" data-target="<?= $r[0] ?>"></span></h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 d-flex">
          <div class="card flex-fill">
            <div class="card-body">
              <div class="d-flex align-items-center">
                <div class="avatar-sm flex-shrink-0">
                  <span class="avatar-title bg-light text-primary rounded-circle fs-3">
                    <i class="ri-pages-line"></i>
                  </span>
                </div>
                <div class="flex-grow-1 ms-3">
                  <?php
                    $r = mysqli_fetch_row(mysqli_query($con, "SELECT count(*) FROM blog"));
                  ?>
                  <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Toplam Blog</p>
                  <h4 class="mb-0"><span class="counter-value" data-target="<?= $r[0] ?>"></span></h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Yeni kartlar buraya aynı pattern ile eklenebilir -->
      </div>
      <!-- /Kartlar -->

    </div>
  </div>
</div>
<?php include "footer.php"; ?>
