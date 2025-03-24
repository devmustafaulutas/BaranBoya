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
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="index.php">Ana Sayfa</a>
                        </li>
                        <li class="breadcrumb-item text-white active">Hakkımızda</li>
                    </ol>
                </div>
            </div>
        </div>
</section>
<!-- ***** Breadcrumb Area End ***** -->

<!-- ***** About Area Start ***** -->
<section id="custom-bg" class="section about-area ptb_100">
    <div id="about-content" class="container">
        <div class="about-content row justify-content-between align-items-center">
            <div class="container">
                <h5 id="about-baslik" class="text-white text-uppercase mb-3 text-center">HAKKIMIZDA</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="box p-3">
                            <p style="height: auto; min-height: 300px;">
                                Baran Boya Kompozit olarak kuruluşumuz 1998 itibari ile CTP (Camelyaf Takviyeli
                                Polyester) sektörüne vermiş olduğumuz katkılarla ön plana çıkmaktayız. 25 yılı aşkın
                                sektör tecrübemiz ile güvenilir, yenilikçi, modern ve geniş hizmet ağımızla sektördeki
                                konumumuzu koruyarak arttırmaktayız.
                                Ankara’da kurulan firmamız İvedik OSB ve Siteler’de aktif olarak müşteri ve iş
                                ortaklarına hizmet vermeye devam etmektedir. 2023 Mayıs ayından itibaren taşınmış
                                olduğumuz yeni merkezimizde (İvedik OSB) 1.000m² alanda sizlere genişleyen
                                stok/ürün çeşitliliğimiz ile daha kaliteli hizmetler sunmak için çalışmalarımıza hız
                                kesmeden devam etmekteyiz.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box p-3">
                            <p style="height: auto; min-height: 300px;">
                                Başlıca satış ve tedariğini yaptığımız ürünler: Polyester Reçineler, Vinilester
                                Reçineler, Alev İlerletmeyen Reçineler, Mek Peroksitler, Kobalt Oktoatlar, Epoksi
                                Reçineler, Jelkotlar ve Topkotlar, Cam Elyaflar (Keçe Elyaflar E-Mat/Mat,
                                Dokuma/Hasır/Örgü, İp Elyaflar, Kırpık Elyaf, Karbon Elyaf), Stiren Monomerler
                                (Polyester İnceltici), Temizleyiciler (Aseton ve Tiner), Kalıp Ayırıcılar, RTV-2 Kalıp
                                Silikonları, Poliüretan Reçine, Yardımcı Ürünler, Pigment Renklendiriciler, Dolgu
                                Malzemeleri ve Hırdavat Grubu Yardımcılar. Daha detaylı bilgi için bizlerle iletişime
                                geçebilirsiniz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="box p-3">
                        <h5 id="about-baslik" class="text-white text-uppercase mb-3 text-center">VİZYONUMUZ</h5>
                        <p>
                            Değerlerimiz, bilgimiz ve çalışanlarımızla, müşterilerimizin ve tedarikçilerimizin
                            memnuniyetini
                            sağlamak ve gelişime paralel olarak kaliteli ve güvenilir ürünler sunmak temel amacımızdır.
                        </p>
                    </div>
                </div>
                <div id class="col-md-12 mt-3">
                    <div class="box p-3">
                        <h5 id="about-baslik" class="text-white text-uppercase mb-3 text-center">MİSYONUMUZ</h5>
                        <p>
                            Sürekli gelişim anlayışı ile bilgi ve tecrübemizden yararlanarak, ürün ve hizmetlerimizi
                            müşterilerimizin beklentilerine uygun hale getirmek. Müşterilerimiz ve tedarikçilerimizle
                            köklü ve
                            uzun süreli ilişkiler kurmak. Tüm çalışanlarımızı teşvik etmek ve başarılarını desteklemek.
                            Olası
                            tüm fırsatları değerlendirerek ortaklarımıza ve yatırımcılarımıza ekonomik değer katmak.
                        </p>
                    </div>
                </div>
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
$rt = mysqli_query($con, "SELECT logo FROM logo where id=1");
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
                            <img src="assets/img/logo/<?php print $ufile ?>" alt="brand-logo">
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