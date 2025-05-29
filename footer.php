<?php
include __DIR__ . '/z_db.php';

// 2) siteconfig’dan bütün alanları çekelim
$stmt = $con->prepare(
    "SELECT site_about, site_footer
       FROM siteconfig
      WHERE id = 1
      LIMIT 1"
);
$stmt->execute();
$stmt->bind_result($site_about, $site_footer);
$stmt->fetch();
$stmt->close();

// 3) XSS koruması
$about_safe  = htmlspecialchars($site_about,  ENT_QUOTES, 'UTF-8');
$footer_safe = htmlspecialchars($site_footer, ENT_QUOTES, 'UTF-8');
?>
<!--====== Footer Area Start ======-->
<footer id="custom-bg-down" class="section footer-area">
  <div class="footer-top ptb_25" style="border-top:2px solid white">
    <div class="container">
      <div class="row">

        <!-- Hakkımızda -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="footer-items">
            <h3 class="footer-title text-uppercase mb-2">Hakkımızda</h3>
            <p class="mb-2"><?= $about_safe ?></p>
          </div>
        </div>

        <!-- Servislerimiz -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="footer-items">
            <h3 class="footer-title text-uppercase mb-2">Servislerimiz</h3>
            <ul>
              <?php
              $svcStmt = $con->prepare(
                  "SELECT id, service_title
                     FROM service
                    ORDER BY id DESC
                    LIMIT 5"
              );
              $svcStmt->execute();
              $svcStmt->bind_result($sid, $stitle);
              while ($svcStmt->fetch()):
                  $title_safe = htmlspecialchars($stitle, ENT_QUOTES, 'UTF-8');
                  $url = 'servicedetail.php?id=' . urlencode($sid);
              ?>
                <li class="py-2">
                  <a class="text-black-50" href="<?= $url ?>">
                    <?= $title_safe ?>
                  </a>
                </li>
              <?php
              endwhile;
              $svcStmt->close();
              ?>
            </ul>
          </div>
        </div>

        <!-- Sosyal Medya -->
        <div class="col-12 col-sm-6 col-lg-4">
          <div class="footer-items">
            <h3 class="footer-title text-uppercase mb-2">Sosyal Medya</h3>
            <p class="mb-2"><?= $follow_safe ?></p>
            <ul class="social-icons list-inline pt-2">
              <?php
              $socStmt = $con->prepare(
                  "SELECT fa, social_link
                     FROM social
                    ORDER BY id DESC
                    LIMIT 5"
              );
              $socStmt->execute();
              $socStmt->bind_result($fa, $slink);
              while ($socStmt->fetch()):
                  $icon_safe = htmlspecialchars($fa, ENT_QUOTES, 'UTF-8');
                  $href_safe = htmlspecialchars($slink, ENT_QUOTES, 'UTF-8');
              ?>
                <li class="list-inline-item px-1 text-white">
                  <a href="<?= $href_safe ?>" target="_blank" rel="noopener">
                    <i class="fab <?= $icon_safe ?>"></i>
                  </a>
                </li>
              <?php
              endwhile;
              $socStmt->close();
              ?>
            </ul>
          </div>
        </div>

      </div>

      <!-- Footer Alt (copyright) -->
      <div class="copyright-area d-flex flex-wrap justify-content-center justify-content-sm-between text-center py-4">
        <div class="copyright-left">
          <?= $footer_safe ?>
        </div>
        <div class="copyright-right">
          Made with <i class="fas fa-heart"></i> by
          <a href="https://github.com/devmustafaulutas" target="_blank" rel="noopener">
            Adeus
          </a>
        </div>
      </div>

    </div>
  </div>
</footer>
<!--====== Footer Area End ======-->

<!--====== Modal Responsive Menu Area Start ======-->
<div id="menu" class="modal fade p-0">
    <div class="modal-dialog dialog-animated">
        <div class="modal-content h-100">
            <div class="modal-header" data-dismiss="modal">
                Menu <i class="far fa-times-circle icon-close"></i>
            </div>
            <div class="menu modal-body">
                <div class="row w-100">
                    <div class="items p-0 col-12 text-center"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--====== Modal Responsive Menu Area End ======-->

</div>


<!-- ***** All jQuery Plugins ***** -->

<!-- jQuery(necessary for all JavaScript plugins) -->
<script src="assets/js/jquery/jquery-3.5.1.min.js"></script>

<!-- Bootstrap js -->
<script src="assets/js/bootstrap/popper.min.js"></script>
<script src="assets/js/bootstrap/bootstrap.min.js"></script>

<!-- Plugins js -->
<script src="assets/js/plugins/plugins.min.js"></script>

<!-- Animations js -->
<script src="assets/js/animations.js"></script>
<script src="assets/js/sektor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Active js -->
<script src="assets/js/active.js"></script>
<script src="assets/js/background.js"></script>
</body>


<!-- Mirrored from theme-land.com/digimx/demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Jul 2022 15:13:02 GMT -->

</html>