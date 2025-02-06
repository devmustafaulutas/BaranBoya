
<?php include "header.php"; ?>
        <!-- ***** Breadcrumb Area Start ***** -->
        <section class="section breadcrumb-area overlay-dark d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Breamcrumb Content -->
                        <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                            <h2 class="text-white text-uppercase mb-3">Hakkımızda</h2>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-uppercase text-white" href="index.php">Ana Sayfa</a></li>
                                <li class="breadcrumb-item text-white active">Hakkımızda</li>
                            </ol>
                            <p>
                                
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Breadcrumb Area End ***** -->
            
        <!-- ***** About Area Start ***** -->
        <section class="section about-area ptb_100">
            <div id="about-content" class="container">
                <div class="about-content row justify-content-between align-items-center">
                    <div id="hakkimizda">
                        <p>Baran Boya ailesi olarak çalışma alanımızın ağırlığı mobilya, kompozit ve sanayi sektörüne yönelik Polyester Reçineler, Vinilester Reçineler, Epoksi Reçineler ,Cam Elyaf Takviyeleri, RTV-2 Kalıp Silikonu, Poliüretan Reçine ve yardımcı malzemeleridir.</p>
                    </div>
                    <div id="vizyon">
                        <h5 id="about-baslik" class="text-white text-uppercase mb-3">VİZYONUMUZ</h5>
                        <p>
                            Değerlerimiz, bilgimiz ve çalışanlarımızla, müşterilerimizin ve tedarikçilerimizin memnuniyetini sağlamak ve gelişime pararel olarak kaliteli ve güvenilir ürünler sunmak temel amacımızdır.
                        </p>
                    </div>
                    <div id="misyon">
                        <h5 id="about-baslik" class="text-white text-uppercase mb-3">MİSYONUMUZ</h5>
                        <p>
                            Sürekli gelişim anlayışı ile bilgi ve tecrübemizden yararlanarak, ürün ve hizmetlerimizi müşterilerimizin beklentilerine uygun hale getirmek. Müşterilerimiz ve tedarikçilerimizle köklü ve uzun süreli ilişkiler kurmak. Tüm çalışanlarımızı teşvik etmek ve başarılarını desteklemek. Olası tüm fırsatları değerlendirerek ortaklarımıza ve yatırımcılarımıza ekonomik değer katmak.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** About Area End ***** -->


        <!-- ***** Our Goal Area End ***** -->

        <!-- ***** Team Area Start ***** -->

        <!-- ***** Team Area End ***** -->

        <!--====== Contact Area Start ======-->
    

        <!--====== Call To Action Area Start ======-->
                    <?php
    $rt=mysqli_query($con,"SELECT logo FROM logo where id=1");
    $tr = mysqli_fetch_array($rt);
    $ufile = "$tr[logo]";
?>


        <section class="section-contact cta-area bg-overlay-1 ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <!-- Section Heading -->
                        <div class="section-heading text-center m-0">   
                            <div class="about-content-contact">
                                <a href="index.php">
                                    <img src="assets/img/logo/<?php print $ufile?>" alt="brand-logo">
                                </a>
                            </div>
                            <p class="text-white d-none d-sm-block mt-4">İletişim ve daha detaylı bilgi için</p>
                            <a href="contact" class="btn btn-bordered-white mt-4">Bize Ulaşın</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Call To Action Area End ======-->
<?php include "footer.php"; ?>
