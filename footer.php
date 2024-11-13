<?php include "z_db.php";?>

  <!--====== Footer Area Start ======-->
  <footer class="section footer-area">
            <!-- Footer Top -->
            <div class="footer-top ptb_100">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-4">
                            <!-- Footer Items -->
                            <div class="footer-items">
                                <!-- Footer Title -->
                            <h3 class="footer-title text-uppercase mb-2">Hakkımzıda</h3>
                                <p class="mb-2">Firmamız, endüstriyel boyada kalite ve dayanıklılık sunarak, her ihtiyaca özel çözümler geliştirmektedir. Müşteri memnuniyetini ve yenilikçiliği ön planda tutan ekibimizle sektörde güvenilir bir marka olmayı hedefliyoruz.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <!-- Footer Items -->
                            <div class="footer-items">
                                <!-- Footer Title -->
                                <h3 class="footer-title text-uppercase mb-2">Servislerimiz</h3>
                                <ul>

 <?php
   $q="SELECT * FROM  service ORDER BY id DESC LIMIT 5";
 $r123 = mysqli_query($con,$q);

while($ro = mysqli_fetch_array($r123))
{

	$id="$ro[id]";
	$service_title="$ro[service_title]";

print "
<li class='py-2'><a class='text-black-50' href='servicedetail.php?id=$id'>$service_title</a></li>
";
}
?>


                                </ul>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-4">
                            <!-- Footer Items -->
                            <div class="footer-items">
                                <!-- Footer Title -->
                                <h3 class="footer-title text-uppercase mb-2">Sosyal Medya</h3>
                                <p class="mb-2">Bizi sosyal medya hesaplarımızdan takip edebilirsiniz.</p>
                                <!-- Social Icons -->
                                <ul class="social-icons list-inline pt-2">

                                <?php
   $q="SELECT * FROM  social ORDER BY id DESC LIMIT 5";
 $r123 = mysqli_query($con,$q);

while($ro = mysqli_fetch_array($r123))
{

	$id="$ro[id]";
    $fa="$ro[fa]";
    $social_link="$ro[social_link]";

print "
<li class='list-inline-item px-1'><a href='$social_link'><i class='fab $fa'></i></a></li>
";
}
?>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Bottom -->
            <div class="footer-bottom bg-grey">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <!-- Copyright Area -->
                            <div class="copyright-area d-flex flex-wrap justify-content-center justify-content-sm-between text-center py-4">
                                <!-- Copyright Left -->

                                <div class="copyright-left"><?php print $site_footer ?></div>   
                                <!-- Copyright Right -->
                                <div class="copyright-right">Made with <i class="fas fa-heart"></i> By <a href="https://facebook.com/hillsoftsnetwork">Adeus</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--====== Footer Area End ======-->

        <!--====== Modal Search Area Start ======-->

        <!--====== Modal Search Area End ======-->

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

    <!-- Active js -->
    <script src="assets/js/active.js"></script>
</body>


<!-- Mirrored from theme-land.com/digimx/demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 11 Jul 2022 15:13:02 GMT -->
</html>
