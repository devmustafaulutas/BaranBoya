<?php
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include "z_db.php";
include "header.php";
require 'dashboard/PHPMailer/src/Exception.php';
require 'dashboard/PHPMailer/src/PHPMailer.php';
require 'dashboard/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errormsg = "";

var_dump($_SESSION['csrf_token'], $_POST['csrf_token']);

// Başlık ve açıklama metinleri
if (!isset($contact_title)) {
    $contact_title = "İletişim";
}
if (!isset($contact_text)) {
    $contact_text = "Bize ulaşmak için aşağıdaki iletişim bilgilerini kullanabilirsiniz.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF kontrolü
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errormsg = "
            <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                Geçersiz istek (CSRF hatası).
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    } else {
        $status = "OK";

        // Basit rate-limit: en az 60 saniye bekle
        $bekleme_suresi = 60;
        if (
            isset($_SESSION['last_contact_time'])
            && (time() - $_SESSION['last_contact_time'] < $bekleme_suresi)
        ) {
            $errormsg = "
                <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                    Lütfen en az {$bekleme_suresi} saniye bekleyip tekrar deneyin.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            $status = "NOTOK";
        }

        // Girdileri temizle & filtrele
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
        $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));

        // Form doğrulama
        if ($status === "OK") {
            if (mb_strlen($name) < 5) {
                $errormsg .= "İsim 5 karakterden uzun olmalı.<br>";
                $status = "NOTOK";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errormsg .= "Geçerli bir e-posta adresi giriniz.<br>";
                $status = "NOTOK";
            }
            if (mb_strlen($phone) < 8) {
                $errormsg .= "Telefon numarası 8 karakterden uzun olmalı.<br>";
                $status = "NOTOK";
            }
            if (mb_strlen($message) < 10) {
                $errormsg .= "Mesaj 10 karakterden uzun olmalı.<br>";
                $status = "NOTOK";
            }
        }

        if ($status === "OK") {
            // 1) Veritabanına kaydet
            $stmt = $con->prepare(
                "INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $name, $email, $phone, $message);
            $stmt->execute();
            $stmt->close();

            // 2) E-posta gönderimi
            $mail = new PHPMailer(true);
            try {
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                // SMTP Ayarları
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = getenv('SMTP_USERNAME') ?: 'baranboya@gmail.com';
                $mail->Password = getenv('SMTP_PASSWORD') ?: 'lbzg cigc usyk zlwa';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Gönderen (fallback ile)
                $fromEmail = getenv('SMTP_FROM_EMAIL') ?: 'baranboya@gmail.com';
                $mail->setFrom($fromEmail, 'Baran Boya');

                // Yanıt adresi olarak kullanıcı
                $mail->addReplyTo($email, $name);

                // Alıcı
                $mail->addAddress(getenv('SMTP_TO_EMAIL') ?: 'mustafaum538@gmail.com');

                // İçerik
                $mail->isHTML(true);
                $mail->Subject = 'Yeni İletişim Mesajı';
                $mail->Body = "
                    <h3>İsim: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</h3>
                    <h3>Email: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</h3>
                    <h3>Telefon: " . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . "</h3>
                    <p>Mesaj: " . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "</p>
                ";

                $mail->send();

                // Rate-limit zamanını güncelle
                $_SESSION['last_contact_time'] = time();

                $errormsg = "
                    <div class='alert alert-success alert-dismissible alert-outline fade show'>
                        Mesaj başarıyla gönderildi. En kısa sürede sizinle iletişime geçilecektir.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            } catch (Exception $e) {
                $errormsg = "
                    <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                        Mesaj gönderilemedi. Hata: " . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8') . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
        } else {
            // Validasyon veya rate-limit hataları
            $errormsg = "
                <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                    {$errormsg}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }
}

?>


<!-- ***** Breadcrumb Area Start ***** -->
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Breamcrumb Content -->
                <div class="breadcrumb-content text-center">
                    <h2 class="text-white text-uppercase mb-3">İLETİŞİM</h2>

                    <ol class="breadcrumb d-flex justify-content-center">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Home</a></li>
                        <li class="breadcrumb-item text-white active">İletişim</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Breadcrumb Area End ***** -->


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
            <div class="col-12 col-lg-6 pt-6">
                <div class="contact-box text-center">
                    <?php if (!empty($errormsg))
                        echo $errormsg; ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token"
                            value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
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
                                    <textarea class="form-control" name="message" placeholder="Mesaj"
                                        required></textarea>
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


<!--====== Map Area Start ======-->
<section id="custom-bg" class="section map-area">
    <div class="container">
        <div class="row">
            <div class="col-md-6 d-flex flex-column"> <!-- Flexbox için d-flex ve flex-column kullanıldı -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3056.2845398240997!2d32.76084867580579!3d40.00209137150924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d349881bd99935%3A0x4f2adf4a1b5956d5!2sBaran%20Boya%20Polyester!5e0!3m2!1str!2str!4v1730577931652!5m2!1str!2str"
                    style="border:0; width: 100%; height: 500px;" allowfullscreen="" loading="lazy"></iframe>
                <div class="contact-map-info">
                    <h5>BARAN BOYA İVEDİK (Merkez)</h5>
                </div>
                <div id="phone-contact" class="contact-us">
                    <ul>
                        <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-2">
                            <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                            <a class="d-block my-2" href="tel:<?php print $phone1 ?>">
                                <h3><?php print $phone1 ?></h3>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6 d-flex flex-column"> <!-- Flexbox için d-flex ve flex-column kullanıldı -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d48915.37859149094!2d32.792040339506265!3d39.981386176280935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d3520a32d52bc3%3A0x766a247a8bfe0bee!2sBaran%20Boya%20Polyester%20Re%C3%A7ine%20Elyaf%20Jelkot%20Siteler!5e0!3m2!1str!2str!4v1730553471165!5m2!1str!2str"
                    style="border:0; width: 100%; height: 500px;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" style="border:0; width: 100%; height: 500px;"
                    allowfullscreen="" loading="lazy"></iframe>
                <div class="contact-map-info">
                    <h5>BARAN BOYA SİTELER (Şube)</h5>
                </div>
                <div id="phone-contact" class="contact-us">
                    <ul>
                        <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-2">
                            <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                            <a class="d-block my-2" href="tel:<?php print $phone2 ?>">
                                <h3><?php print $phone2 ?></h3>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!--====== Map Area End ======-->
<?php include "footer.php"; ?>