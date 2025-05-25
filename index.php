<?php include "header.php"; ?>
<?php
    error_reporting(E_ALL); // Tüm hataları ve uyarıları göster
    ini_set('display_errors', 0); // Hataları ekrana yazdırma (güvenlik için kapalı)
    ini_set('log_errors', 1); // Hataları log dosyasına yaz
    ini_set('error_log', __DIR__ . '/error.log'); // Hataları kaydedeceğiniz log dosyasının yolu (__DIR__ geçerli dizini belirtir)

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'dashboard/PHPMailer/src/Exception.php';
require 'dashboard/PHPMailer/src/PHPMailer.php';
require 'dashboard/PHPMailer/src/SMTP.php';
require 'z_db.php'; // Veritabanı bağlantısını dahil et

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errormsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = "OK"; // Başlangıç durumu
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    // Form alanı doğrulama
    if (strlen($name) < 5) {
        $errormsg .= "İsim 5 karakterden uzun olmalı.<br>";
        $status = "NOTOK";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errormsg .= "Geçerli bir e-posta adresi giriniz.<br>";
        $status = "NOTOK";
    }
    if (strlen($phone) < 8) {
        $errormsg .= "Telefon numarası 8 karakterden uzun olmalı.<br>";
        $status = "NOTOK";
    }
    if (strlen($message) < 10) {
        $errormsg .= "Mesaj 10 karakterden uzun olmalı.<br>";
        $status = "NOTOK";
    }

    // Eğer doğrulama başarılıysa e-posta gönderimi
    if ($status == "OK") {
        $mail = new PHPMailer(true);

        try {
            // SMTP Sunucu Ayarları
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USERNAME'); // Çevresel değişkenden alınan Gmail adresi
            $mail->Password = getenv('SMTP_PASSWORD'); // Çevresel değişkenden alınan Gmail uygulama şifresi
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Gönderen ve Alıcı Bilgileri
            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), 'Sender Name'); // Gönderen e-posta adresi
            $mail->addAddress(getenv('SMTP_TO_EMAIL')); // Alıcı e-posta adresi

            // Mesaj İçeriği
            $mail->isHTML(true);
            $mail->Subject = 'Yeni İletişim Mesajı';
            $mail->Body = "
                <h3>İsim: $name</h3>
                <h3>Email: $email</h3>
                <h3>Telefon: $phone</h3>
                <p>Mesaj: $message</p>
            ";

            $mail->send();
            $errormsg = "
                <div class='alert alert-success alert-dismissible alert-outline fade show'>
                    Mesaj başarıyla gönderildi. En kısa sürede sizinle iletişime geçilecektir.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } catch (Exception $e) {
            $errormsg = "
                <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                    Mesaj gönderilemedi. Hata: {$mail->ErrorInfo}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    } else {
        $errormsg = "
            <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                $errormsg
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    }
}
?>
    <!-- ***** Welcome Area Start ***** -->
        <section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Welcome Intro Start -->
                    <div class="col-12 col-md-12">
                    <?php
    $stmt = $con->prepare("SELECT id, stitle, stext FROM static");
    $stmt->execute();
    $stmt->bind_result($id, $stitle, $stext); // Sütun adlarını veritabanınızdaki sütunlara göre ayarlayın

    while ($stmt->fetch()) {
        $result[] = [
            'id' => $id,
            'stitle' => $stitle,
            'stext' => $stext
        ];
    }

    $service_title = "Hizmetlerimiz"; // Örnek statik değer
    $service_text = "Sunulan hizmetler hakkında kısa açıklama."; // Örnek statik değer

    $contact_title = "İletişim"; // Örnek statik değer
    $contact_text = "Bize ulaşmak için aşağıdaki iletişim bilgilerini kullanabilirsiniz."; // Örnek statik değer

?>

                        <div class="welcome-intro">
                            <h1><?php print $stitle?></h1>
                            <p class="text-white my-4"><?php print $stext?></p>
                            <!-- Buttons -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
       
        

        <!-- ***** Service Area End ***** -->
        <!-- ***** Service Area End ***** -->

        
        <section id="custom-bg" id="service" class="section service-area bg-grey ptb_150">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-7">
                        <!-- Section Heading -->
                        <div class="section-heading text-center" data-text="<?php echo $service_title; ?>">
                            <h2 class="whoarewe-title" ><?php echo $service_title; ?></h2>
                            <p class="d-none d-sm-block mt-4"><?php echo $service_text; ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                <?php
                $stmt = $con->prepare("SELECT id, service_title, service_desc, icon FROM service ORDER BY id DESC LIMIT 6");
                $stmt->execute();
                $stmt->bind_result($id, $serviceg, $service_desc, $icon); // Sütun adlarını veritabanınızdaki sütunlara göre ayarlayın

                while ($stmt->fetch()) {
                    $services[] = [
                        'id' => $id,
                        'service_title' => $serviceg,
                        'service_desc' => $service_desc,
                        'icon' => $icon
                    ];
                }

                foreach ($services as $service) {
                    $id = $service['id'];
                    $serviceg = $service['service_title'];
                    $service_desc = $service['service_desc'];
                    $icon = $service['icon'];

                    // HTML çıktısını oluştur
                    print "
                    <div class='col-12 col-md-6 col-lg-4 mb-4'>
                        <div class='single-service p-4' style='border:1px solid #788282;'>
                            <h3 class='my-3'>$serviceg</h3>
                            <p>$service_desc</p>
                            <div class='index-icon-container'>
                                    $icon
                            </div>
                        </div>
                    </div>
                    ";
                }
                ?>

                </div>
            </div>
            <!-- Shape Bottom -->
            <div class="shape shape-bottom">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
                    <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
        c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
        c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </div>
        </section>

       
        <!-- ***** Sektor Area Start ***** -->
        <section id="custom-bg" class="index-sektorler-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="section-heading text-center col-12" data-text="<?php echo $service_title; ?>">
                        <h2 class="whoarewe-title" >SEKTÖRLERİMİZ</h2>
                        <p class="index-sector-main-description fade-in">Yenilikçi çözümlerimizle lider olduğumuz sektörel alanlarda sizlere en kaliteli hizmeti sunuyoruz.</p>
                    </div>
                </div>

                <!-- Sektör 1 (col-4 resim, col-8 içerik) -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/HAVACILIK VE SAVUNMA.png" alt="Havacılık ve Savunma" class="index-sector-image fade-in">
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-plane-departure index-sector-icon"></i>
                            <h3 class="index-sector-title">Havacılık ve Savunma</h3>
                            <p class="index-sector-description">Havacılık sektörü için yenilikçi çözümler ve güvenlik önlemleri.</p>
                            <a href="sektor_detay.php?sector=Havac%C4%B1l%C4%B1k%20ve%20Savunma%20Sanayi" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                </div>

                <!-- Sektör 2 (col-8 içerik, col-4 resim) -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-anchor index-sector-icon"></i>
                            <h3 class="index-sector-title">Denizcilik</h3>
                            <p class="index-sector-description">Denizcilik sektörüne yönelik dayanıklı ve uzun ömürlü çözümler sunuyoruz.</p>
                            <a href="sektor_detay.php?sector=Denizcilik" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/unnamed (8).png" alt="Denizcilik" class="index-sector-image fade-in">
                    </div>
                </div>

                <!-- Sektör 3 (col-4 resim, col-8 içerik) -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/unnamed (5).png" alt="Banyo" class="index-sector-image fade-in">
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-bath index-sector-icon"></i>
                            <h3 class="index-sector-title">Banyo</h3>
                            <p class="index-sector-description">Modern banyolar için en iyi tasarım çözümleri.</p>
                            <a href="sektor_detay.php?sector=Banyo" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                </div>

                <!-- Sektör 4 (col-8 içerik, col-4 resim) -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-utensils index-sector-icon"></i>
                            <h3 class="index-sector-title">Mutfak</h3>
                            <p class="index-sector-description">Pratik ve estetik mutfak çözümleri ile hayatınızı kolaylaştırıyoruz.</p>
                            <a href="sektor_detay.php?sector=Mutfak" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/unnamed (6).png" alt="Mutfak" class="index-sector-image fade-in">
                    </div>
                </div>

                <!-- Sektör 5: Hobi ve Tasarım -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/HOBİ ve TASARIM.png" alt="Hobi ve Tasarım" class="index-sector-image fade-in">
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-paint-brush index-sector-icon"></i>
                            <h3 class="index-sector-title">Hobi ve Tasarım</h3>
                            <p class="index-sector-description">Yaratıcı fikirlerinizi hayata geçirin!</p>
                            <a href="sektor_detay.php?sector=Hobi%20ve%20Tasar%C4%B1m" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                </div>

                <!-- Sektör 6: Otomotiv ve Ulaşım -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-car index-sector-icon"></i>
                            <h3 class="index-sector-title">Otomotiv ve Ulaşım</h3>
                            <p class="index-sector-description">Araçlarınız için en kaliteli yüzey kaplama çözümleri.</p>
                            <a href="sektor_detay.php?sector=Otomotiv%20ve%20Ula%C5%9F%C4%B1m" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/unnamed (1).png" alt="Otomotiv ve Ulaşım" class="index-sector-image fade-in">
                    </div>
                </div>

                <!-- Sektör 7: İnşaat ve Mimari Tasarım -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/unnamed (4).png" alt="İnşaat ve Mimari Tasarım" class="index-sector-image fade-in">
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-building index-sector-icon"></i>
                            <h3 class="index-sector-title">İnşaat ve Mimari Tasarım</h3>
                            <p class="index-sector-description">İnşaat ve mimari tasarım projelerinize uyum sağlayan modern ve dayanıklı boyalarla yapılarınızı geleceğe hazırlayın.</p>
                            <a href="sektor_detay.php?sector=%C4%B0n%C5%9Faat%20ve%20Mimari%20Tasar%C4%B1m" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                </div>

                <!-- Sektör 8: Heykel ve Sanat -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-drafting-compass index-sector-icon"></i>
                            <h3 class="index-sector-title">Heykel ve Sanat</h3>
                            <p class="index-sector-description">Sanatsal projelerde yaratıcılığınızı artıracak yüksek kaliteli boyalarla heykel ve sanat eserlerinizi hayata geçirin.</p>
                            <a href="sektor_detay.php?sector=Heykel%20ve%20Sanat" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/unnamed (7).png" alt="Heykel ve Sanat" class="index-sector-image fade-in">
                    </div>
                </div>

                <!-- Sektör 9: Enerji ve Sürdürülebilirlik -->
                <div class="index-sektor-item row align-items-center mb-4">
                    <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                        <img src="assets/img/baranboya/SÜRDÜRÜLEBİLİR ENERJİ.png" alt="Enerji ve Sürdürülebilirlik" class="index-sector-image fade-in">
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                        <div class="index-sector-content">
                            <i class="fas fa-wind index-sector-icon"></i>
                            <h3 class="index-sector-title">Enerji ve Sürdürülebilirlik</h3>
                            <p class="index-sector-description">Çevre dostu boyalarımızla enerji sektöründe sürdürülebilir çözümler sunarak hem çevrenizi hem de projelerinizi koruyoruz.</p>
                            <a href="sektor_detay.php?sector=Enerji%20ve%20S%C3%BCrd%C3%BCr%C3%BClebilirlik" class="index-sector-link">Detaylar</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Sektor Area End ***** -->
                
        <section id="custom-bg" class="section review-area ptb_100">
            <div class="container">
                <hr>
                <div class="row justify-content-center">
                    <div id="tedarikcilerimiz-baslik"class="col-12 col-md-10 col-lg-7">
                        <!-- Section Heading -->


                        <div class="section-heading text-center"></div>
                            <h2 class="sector-main-title">TEDARİKÇİLERİMİZ</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Client Logos -->
                    <div id="client-logos" class="client-logos d-flex flex-wrap justify-content-center">
                        <?php
                        $stmt = $con->prepare("SELECT id, resim FROM tedarikcilerimiz ORDER BY id DESC LIMIT 8");
                        $stmt->execute();
                        $stmt->bind_result($id, $resim); // Sütun adlarını veritabanınızdaki sütunlara göre ayarlayın

                        while ($stmt->fetch()) {
                            $tedarikciler[] = [
                                'id' => $id,
                                'resim' => $resim
                            ];
                        }

                        foreach ($tedarikciler as $tedarikci) {
                            $resim = $tedarikci['resim'];

                            // Resimleri yatayda göstermek için HTML çıktısı
                            print "
                            <div class='single-logo p-3'>
                                <img class='img-fluid' src='assets/img/tedarikcilerimiz/$resim' >
                            </div>
                            ";
                        }
                        ?>
                    </div>
                </div>

            </div>
        </section>
        <!-- ***** Review Area End ***** -->

        <!--====== Contact Area Start ======-->
        <section id="custom-bg" id="contact" class="contact-area ptb_100">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-lg-5">
                        <div class="section-heading text-center mb-3">
                            <h2><?php print $contact_title ?></h2>
                            <p class="d-none d-sm-block mt-4"><?php print $contact_text ?></p>
                        </div>
                        <div class="contact-us">
                            <ul>
                                <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-3">
                                    <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                                    <a class="d-block my-2" href="tel:<?php print $phone1 ?>">
                                        <h3><?php print $phone1 ?></h3>
                                    </a>
                                </li>
                                <li class="contact-info color-3 bg-hover active hover-bottom text-center p-5 m-3">
                                    <span><i class="fas fa-envelope-open-text fa-3x"></i></span>
                                    <a class="d-none d-sm-block my-2" href="mailto:<?php print $email ?>">
                                        <h3><?php print $email ?></h3>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 pt-4 pt-lg-0">
                        <div class="contact-box text-center">
                            <?php if (!empty($errormsg)) echo $errormsg; ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name" placeholder="İsim" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="phone" placeholder="Telefon" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message" placeholder="Mesaj" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button id="contact-button" type="submit" class="btn btn-bordered-white mt-4">
                                            <span class="text-white pr-3"><i class="fas fa-paper-plane"></i></span>Send Message
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <p class="form-message"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Contact Area End ======-->
        <!--====== Contact Area End ======-->

        <!--====== Call To Action Area Start ======-->

      <?php include "footer.php"; ?>
