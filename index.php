<?php include "header.php"; ?>
<?php

require  'dashboard/PHPMailer/src/Exception.php';
require  'dashboard/PHPMailer/src/PHPMailer.php';
require  'dashboard/PHPMailer/src/SMTP.php';

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
            $mail->Username = 'baranboya@gmail.com'; // Gönderen Gmail adresi
            $mail->Password = 'lbzg cigc usyk zlwa'; // Gmail uygulama şifresi
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Gönderen ve Alıcı Bilgileri
            $mail->setFrom('your-email@gmail.com', 'Sender Name'); // Gönderen e-posta adresi
            $mail->addAddress('baranboya@gmail.com'); // Alıcı e-posta adresi

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
    $rr=mysqli_query($con,"SELECT * FROM static");
$r = mysqli_fetch_row($rr);
$stitle = $r[1];
$stext=$r[2];
?>

                        <div class="welcome-intro">
                            <h1 class="text-white"><?php print $stitle?></h1>
                            <p class="text-white my-4"><?php print $stext?></p>
                            <!-- Buttons -->
                            <div class="button-group">
                                <a href="about" class="btn btn-bordered-white d-none d-sm-inline-block">Biz Kimiz</a>
                            </div>
                        </div>
                    </div>
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
        <!-- ***** Welcome Area End ***** -->

        <!-- ***** Promo Area Start ***** -->
        <section class="section promo-area ptb_100">
            <div class="container">
                <div class="row">


                <?php
				   $q="SELECT * FROM  why_us ORDER BY id DESC LIMIT 3";


 $r123 = mysqli_query($con,$q);

while($ro = mysqli_fetch_array($r123))
{

	$title="$ro[title]";
	$detail="$ro[detail]";

print "
<div class='col-12 col-md-6 col-lg-4 res-margin'>
<!-- Single Promo -->
<div class='single-promo color-1 bg-hover hover-bottom text-center p-5'>
    <h3 class='mb-3'>$title</h3>
    <p>$detail</p>
</div>
</div>
";
}
?>




                </div>
            </div>
        </section>
        <!-- ***** Promo Area End ***** -->

        <!-- ***** Content Area Start ***** -->

        <!-- ***** Content Area End ***** -->

        <!-- ***** Content Area Start ***** -->

        <!-- ***** Content Area End ***** -->

        <!-- ***** Service Area End ***** -->
        <section id="service" class="section service-area bg-grey ptb_150">
            <!-- Shape Top -->
            <div class="shape shape-top">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
                    <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
                c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
                c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-7">
                        <!-- Section Heading -->
                        <div id="index-sevices-tittle" class="section-heading text-center" data-text="<?php echo $service_title; ?>">
                            <h2><?php echo $service_title; ?></h2>
                            <p class="d-none d-sm-block mt-4"><?php echo $service_text; ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">

                <?php
                $qs = "SELECT * FROM service ORDER BY id DESC LIMIT 6";  // Veritabanından son 6 hizmeti al

                $r1 = mysqli_query($con, $qs);  // Sorguyu çalıştır

                while ($rod = mysqli_fetch_array($r1)) {  // Sonuçları döngüyle al
                    $id = $rod['id'];  // Hizmetin ID'si
                    $serviceg = $rod['service_title'];  // Hizmetin başlığı
                    $service_desc = $rod['service_desc'];  // Hizmetin açıklaması
                    $icon = $rod['icon'];  // Veritabanından çekilen ikon sınıfı

                    // HTML çıktısını oluştur
                    print "
                    <div class='col-12 col-md-6 col-lg-4'>
                        <!-- Single Service -->
                        <div class='single-service p-4' style='border: solid 1px #788282;'>
                            <h3 class='my-3'>$serviceg</h3>
                            <p>$service_desc</p>
                            <div class='index-icon-container'>
                                <a class='service-btn' href='#'>Learn More</a>
                                <a href='#'>
                                    $icon
                                </a>
                            </div>
                        </div>
                        <br>
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
        <!-- ***** Service Area End ***** -->

        <!-- ***** Portfolio Area Start ***** -->
        
        <!-- ***** Portfolio Area End ***** -->

        <!-- ***** Price Plan Area Start ***** -->

        <!-- ***** Price Plan Area End ***** -->

        <!-- ***** Review Area Start ***** -->
        <section id="sektorler" class="section bg-lightblue ptb_200">
            <div class="container">
                <!-- Başlık -->
                <div class="row text-center mb-5">
                    <div class="col-12">
                        <h2>Sektörlerimiz</h2>
                        <p>
                            Yenilikçi çözümlerimizle lider olduğumuz sektörel alanlarda sizlere en kaliteli hizmeti sunuyoruz. 
                            Her bir sektördeki profesyonelliğimizle fark yaratıyoruz.
                        </p>
                    </div>
                </div>

                <!-- Sektörler -->
                <div class="sector-row">
                    <!-- Sektör Kartı 1 -->
                    <div class="sector-card">
                        <img src="assets/img/baranboya/HAVACILIK VE SAVUNMA.png">
                        <h4 class="sector-card-title">Havacılık ve Savunma Sanayi</h4>
                    </div>

                    <!-- Sektör Kartı 2 -->
                    <div class="sector-card">
                        <img src="assets/img/baranboya/unnamed (5).png">
                        <h4 class="sector-card-title">Banyo</h4>
                    </div>

                    <!-- Sektör Kartı 3 -->
                    <div class="sector-card">
                        <img src="assets/img/baranboya/unnamed (6).png">
                        <h4 class="sector-card-title">Mutfak</h4>
                    </div>

                   <!-- Sektör Kartı 4 -->
                    <div class="sector-card">
                        <img src="assets/img/baranboya/unnamed (8).png">
                        <h4 class="sector-card-title">Denizcilik</h4>
                    </div>
                </div>
            </div>
        </section>



        <section id="review" class="section review-area bg-overlay ptb_100">
            <div class="container">
                <hr>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-7">
                        <!-- Section Heading -->


                        <div class="section-heading text-center">
                            <h2 class="text-white">TEDARİKÇİLERİMİZ</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Client Logos -->
                    <div id="client-logos" class="client-logos d-flex flex-wrap justify-content-center">
                        <?php
                        // Veritabanı sorgusu: Son 8 resmi al
                        $q = "SELECT * FROM tedarikcilerimiz ORDER BY id DESC LIMIT 8";
                        $r123 = mysqli_query($con, $q);

                        // Her bir kaydı işleyerek resimleri ekle
                        while ($ro = mysqli_fetch_array($r123)) {
                            $resim = "$ro[resim]";  // Resim dosyasının yolu veya adı

                            // Resimleri yatayda göstermek için HTML çıktısı
                            print "
                            <div class='single-logo p-3'>
                                <img class='img-fluid' src='$resim' alt='Tedarikçi Logosu'>
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
        <section id="contact" class="contact-area ptb_100">
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
                                    <a class="d-none d-sm-block my-2" href="mailto:<?php print $email1 ?>">
                                        <h3><?php print $email1 ?></h3>
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
                                        <button type="submit" class="btn btn-bordered active btn-block mt-3">
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
