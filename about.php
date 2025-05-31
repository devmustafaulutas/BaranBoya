<?php
session_start(); 
include "header.php"; 
include "z_db.php"; 

$ufile = ""; 
if ($stmt = $con->prepare("SELECT logo FROM logo WHERE id = ?")) {
    $fixedId = 1;
    $stmt->bind_param("i", $fixedId);
    $stmt->execute();
    $stmt->bind_result($rawLogoFilename);
    if ($stmt->fetch()) {
        $ufile = basename($rawLogoFilename);
    }
    $stmt->close();
}
?>
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                    <h2 class="text-white text-uppercase mb-3">Hakkımızda</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-uppercase text-white" href="index.php">Ana Sayfa</a>
                        </li>
                        <li class="breadcrumb-item text-white active">Hakkımızda</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="custom-bg" class="section about-area ptb_100">
    <div id="about-content" class="container">
        <div class="about-content row justify-content-between align-items-center">
            <div class="container">
                <h5 id="about-baslik" class="text-white text-uppercase mb-3 text-center">HAKKIMIZDA</h5>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box p-3">
                            <p style="height: auto; min-height: 300px;">
                                Baran Boya Kompozit olarak kuruluşumuz 1998 itibari ile CTP (Camelyaf Takviyeli
                                Polyester) sektörüne vermiş olduğumuz katkılarla ön plana çıkmaktayız. 25 yılı aşkın
                                sektör tecrübemiz ile güvenilir, yenilikçi, modern ve geniş hizmet ağımızla sektördeki
                                konumumuzu koruyarak arttırmaktayız.<br>
                                Ankara’da kurulan firmamız İvedik OSB ve Siteler’de aktif olarak müşteri ve iş
                                ortaklarına hizmet vermeye devam etmektedir. 2023 Mayıs ayından itibaren taşınmış
                                olduğumuz yeni merkezimizde (İvedik OSB) 1.000m² alanda sizlere genişleyen
                                stok/ürün çeşitliliğimiz ile daha kaliteli hizmetler sunmak için çalışmalarımıza hız
                                kesmeden devam etmekteyiz.<br><br>

                                Başlıca satış ve tedariğini yaptığımız ürünler:<br>
                                • Polyester Reçineler, Vinilester Reçineler, Alev İlerletmeyen Reçineler,<br>
                                • Mek Peroksitler, Kobalt Oktoatlar, Epoksi Reçineler, Jelkotlar ve Topkotlar,<br>
                                • Cam Elyaflar (Keçe Elyaflar, E-Mat/Mat, Dokuma/Hasır/Örgü, İp Elyaflar, Kırpık Elyaf, Karbon Elyaf),<br>
                                • Stiren Monomerler (Polyester İnceltici),<br>
                                • Temizleyiciler (Aseton ve Tiner),<br>
                                • Kalıp Ayırıcılar, RTV-2 Kalıp Silikonları, Poliüretan Reçine,<br>
                                • Yardımcı Ürünler, Pigment Renklendiriciler, Dolgu Malzemeleri ve Hırdavat Grubu Yardımcılar.<br><br>

                                <a href="contact.php" class="text-decoration-underline">
                                    Daha detaylı bilgi için bizlerle iletişime geçebilirsiniz.
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>

<section class="section-contact cta-area bg-overlay-1 ptb_100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="section-heading text-center m-0">
                    <div class="about-content-contact">
                        <a href="index.php">
                            <img src="assets/img/logo/<?php echo htmlspecialchars($ufile, ENT_QUOTES, 'UTF-8'); ?>"
                                 alt="brand-logo">
                        </a>
                    </div>
                    <p class="text-white d-none d-sm-block mt-4">
                        İletişim ve daha detaylı bilgi için
                    </p>
                    <a href="contact.php" class="btn btn-bordered-white mt-4">Bize Ulaşın</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>
