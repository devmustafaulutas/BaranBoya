<?php

session_name("SITE_SESSION");
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax',
]);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['site_csrf_token'])) {
    $_SESSION['site_csrf_token'] = bin2hex(random_bytes(32));
}


include "header.php";
include "z_db.php";

require 'dashboard/PHPMailer/src/Exception.php';
require 'dashboard/PHPMailer/src/PHPMailer.php';
require 'dashboard/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errormsg = "";

$contact_title = $contact_title ?? "İletişim";
$contact_text = $contact_text ?? "Bize ulaşmak için aşağıdaki iletişim bilgilerini kullanabilirsiniz.";
$tedarikciler = [];
$stmt = $con->prepare("SELECT id, resim FROM tedarikcilerimiz ORDER BY id");
$stmt->execute();
$stmt->bind_result($id, $resim);
while ($stmt->fetch()) {
    $tedarikciler[] = ['id' => $id, 'resim' => $resim];
}
$stmt->close();

$phone1 = $phone2 = $siteEmail = '';
$stmt = $con->prepare("SELECT phone1, phone2, email FROM sitecontact WHERE id = 1 LIMIT 1");
$stmt->execute();
$stmt->bind_result($phone1, $phone2, $siteEmail);
$stmt->fetch();
$stmt->close();

$tedarikciSayisi = count($tedarikciler);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['site_csrf_token'])
        || !hash_equals($_SESSION['site_csrf_token'], $_POST['site_csrf_token'])
    ) {
        $errormsg = "
            <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                Geçersiz istek (CSRF hatası).
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>";
    } else {
        $status = "OK";
        $bekleme_suresi = 60;
        if (isset($_SESSION['last_contact_time']) && (time() - $_SESSION['last_contact_time'] < $bekleme_suresi)) {
            $errormsg = "
                <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                    Lütfen en az {$bekleme_suresi} saniye bekleyip tekrar deneyin.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            $status = "NOTOK";
        }

        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
        $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));

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
            $stmt = $con->prepare(
                "INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $name, $email, $phone, $message);
            $stmt->execute();
            $stmt->close();

            $mail = new PHPMailer(true);
            try {
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;

                $mail->Username = 'berkinardadeveli@gmail.com';
                $mail->Password = 'gvipatsbkgucuvbj';

                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('berkinardadeveli@gmail.com', 'Baran Boya');
                $mail->addAddress('baranboya@gmail.com');
                $mail->addReplyTo($email, $name);

                $mail->addReplyTo($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'Yeni İletişim Mesajı';
                $mail->Body = "
                    <h3>İsim: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</h3>
                    <h3>Email: " . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . "</h3>
                    <h3>Telefon: " . htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') . "</h3>
                    <p>Mesaj: " . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "</p>
                ";
                $mail->send();

                $_SESSION['last_contact_time'] = time();

                $errormsg = "
                    <div class='alert alert-success alert-dismissible alert-outline fade show'>
                        Mesaj başarıyla gönderildi. En kısa sürede sizinle iletişime geçilecektir.
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            } catch (Exception $e) {
                $err = $mail->ErrorInfo;
                $errormsg = "
                    <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                        Mesaj gönderilemedi. Hata: " . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
            }
        } else {
            $errormsg = "
                <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                    {$errormsg}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        }
    }
}

$result = [];
$services = [];
$tedarikciler = [];

$stmt = $con->prepare("SELECT id, stitle, stext FROM static");
$stmt->execute();
$stmt->bind_result($id, $stitle, $stext);
while ($stmt->fetch()) {
    $result[] = ['id' => $id, 'stitle' => $stitle, 'stext' => $stext];
}
$stmt->close();

$stmt = $con->prepare("SELECT id, service_title, service_desc, icon FROM service ORDER BY id");
$stmt->execute();
$stmt->bind_result($id, $serviceg, $service_desc, $icon);
while ($stmt->fetch()) {
    $services[] = [
        'id' => $id,
        'service_title' => $serviceg,
        'service_desc' => $service_desc,
        'icon' => $icon
    ];
}
$stmt->close();

$stmt = $con->prepare("SELECT id, resim FROM tedarikcilerimiz ORDER BY id");
$stmt->execute();
$stmt->bind_result($id, $resim);
while ($stmt->fetch()) {
    $tedarikciler[] = ['id' => $id, 'resim' => $resim];
}
$stmt->close();

$service_title = "Hizmetlerimiz";
$service_text = "Sunulan hizmetler hakkında kısa açıklama.";
$contact_title = "İletişim";
$contact_text = "Bize ulaşmak için aşağıdaki iletişim bilgilerini kullanabilirsiniz.";
?>

<section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-md-12">
                <?php if (!empty($result)):
                    $first = $result[0]; ?>
                    <div class="welcome-intro">
                        <h1><?php echo htmlspecialchars($first['stitle'], ENT_QUOTES, 'UTF-8'); ?></h1>
                        <p class="text-white my-4"><?php echo htmlspecialchars($first['stext'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section id="custom-bg" id="service" class="section service-area bg-grey ptb_150">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="section-heading text-center" data-text="<?php echo htmlspecialchars($service_title); ?>">
                    <h2 class="whoarewe-title"><?php echo htmlspecialchars($service_title); ?></h2>
                    <p class="d-none d-sm-block mt-4"><?php echo htmlspecialchars($service_text); ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($services as $service): ?>
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="single-service p-4" style="border:1px solid #788282;">
                        <h3 class="my-3"><?php echo htmlspecialchars($service['service_title']); ?></h3>
                        <p><?php echo htmlspecialchars($service['service_desc']); ?></p>
                        <div class="index-icon-container">
                            <?php echo $service['icon']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="shape shape-bottom">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
            <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3..."></path>
        </svg>
    </div>
</section>

<section id="custom-bg" class="index-sektorler-section">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="section-heading text-center col-12" data-text="<?php echo $service_title; ?>">
                <h2 class="whoarewe-title">SEKTÖRLERİMİZ</h2>
                <p class="index-sector-main-description fade-in">Yenilikçi çözümlerimizle lider olduğumuz sektörel
                    alanlarda sizlere en kaliteli hizmeti sunuyoruz.</p>
            </div>
        </div>

        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/HAVACILIK VE SAVUNMA.png" alt="Havacılık ve Savunma"
                    class="index-sector-image fade-in">
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                <div class="index-sector-content">
                    <i class="fas fa-plane-departure index-sector-icon"></i>
                    <h3 class="index-sector-title">Havacılık ve Savunma</h3>
                    <p class="index-sector-description">Havacılık sektörü için yenilikçi çözümler ve güvenlik önlemleri.
                    </p>
                    <a href="sektor_detay.php?sector=Havac%C4%B1l%C4%B1k%20ve%20Savunma%20Sanayi"
                        class="index-sector-link">Detaylar</a>
                </div>
            </div>
        </div>

        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                <div class="index-sector-content">
                    <i class="fas fa-anchor index-sector-icon"></i>
                    <h3 class="index-sector-title">Denizcilik</h3>
                    <p class="index-sector-description">Denizcilik sektörüne yönelik dayanıklı ve uzun ömürlü çözümler
                        sunuyoruz.</p>
                    <a href="sektor_detay.php?sector=Denizcilik" class="index-sector-link">Detaylar</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/unnamed (8).png" alt="Denizcilik" class="index-sector-image fade-in">
            </div>
        </div>

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

        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                <div class="index-sector-content">
                    <i class="fas fa-utensils index-sector-icon"></i>
                    <h3 class="index-sector-title">Mutfak</h3>
                    <p class="index-sector-description">Pratik ve estetik mutfak çözümleri ile hayatınızı
                        kolaylaştırıyoruz.</p>
                    <a href="sektor_detay.php?sector=Mutfak" class="index-sector-link">Detaylar</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/unnamed (6).png" alt="Mutfak" class="index-sector-image fade-in">
            </div>
        </div>

        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/HOBİ ve TASARIM.png" alt="Hobi ve Tasarım"
                    class="index-sector-image fade-in">
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

        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                <div class="index-sector-content">
                    <i class="fas fa-car index-sector-icon"></i>
                    <h3 class="index-sector-title">Otomotiv ve Ulaşım</h3>
                    <p class="index-sector-description">Araçlarınız için en kaliteli yüzey kaplama çözümleri.</p>
                    <a href="sektor_detay.php?sector=Otomotiv%20ve%20Ula%C5%9F%C4%B1m"
                        class="index-sector-link">Detaylar</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/unnamed (1).png" alt="Otomotiv ve Ulaşım"
                    class="index-sector-image fade-in">
            </div>
        </div>
        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/unnamed (4).png" alt="İnşaat ve Mimari Tasarım"
                    class="index-sector-image fade-in">
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                <div class="index-sector-content">
                    <i class="fas fa-building index-sector-icon"></i>
                    <h3 class="index-sector-title">İnşaat ve Mimari Tasarım</h3>
                    <p class="index-sector-description">İnşaat ve mimari tasarım projelerinize uyum sağlayan modern ve
                        dayanıklı boyalarla yapılarınızı geleceğe hazırlayın.</p>
                    <a href="sektor_detay.php?sector=%C4%B0n%C5%9Faat%20ve%20Mimari%20Tasar%C4%B1m"
                        class="index-sector-link">Detaylar</a>
                </div>
            </div>
        </div>

        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                <div class="index-sector-content">
                    <i class="fas fa-drafting-compass index-sector-icon"></i>
                    <h3 class="index-sector-title">Heykel ve Sanat</h3>
                    <p class="index-sector-description">Sanatsal projelerde yaratıcılığınızı artıracak yüksek kaliteli
                        boyalarla heykel ve sanat eserlerinizi hayata geçirin.</p>
                    <a href="sektor_detay.php?sector=Heykel%20ve%20Sanat" class="index-sector-link">Detaylar</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/unnamed (7).png" alt="Heykel ve Sanat"
                    class="index-sector-image fade-in">
            </div>
        </div>

        <div class="index-sektor-item row align-items-center mb-4">
            <div class="col-lg-4 col-md-6 col-sm-12 index-sector-image-container">
                <img src="assets/img/baranboya/SÜRDÜRÜLEBİLİR ENERJİ.png" alt="Enerji ve Sürdürülebilirlik"
                    class="index-sector-image fade-in">
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 index-sector-content-wrap fade-in">
                <div class="index-sector-content">
                    <i class="fas fa-wind index-sector-icon"></i>
                    <h3 class="index-sector-title">Enerji ve Sürdürülebilirlik</h3>
                    <p class="index-sector-description">Çevre dostu boyalarımızla enerji sektöründe sürdürülebilir
                        çözümler sunarak hem çevrenizi hem de projelerinizi koruyoruz.</p>
                    <a href="sektor_detay.php?sector=Enerji%20ve%20S%C3%BCrd%C3%BCr%C3%BClebilirlik"
                        class="index-sector-link">Detaylar</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="custom-bg" class="section review-area ptb_100">
    <div class="container">
        <hr>
        <div class="row justify-content-center">
            <div id="tedarikcilerimiz-baslik" class="col-12 col-md-10 col-lg-7">


                <div class="section-heading text-center"></div>
                <h2 class="sector-main-title">TEDARİKÇİLERİMİZ</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="client-logos" class="client-logos d-flex flex-wrap justify-content-center">
            <?php foreach ($tedarikciler as $t): ?>
                <div class="single-logo p-3">
                    <img src="assets/img/tedarikcilerimiz/<?= htmlspecialchars($t['resim'], ENT_QUOTES) ?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    </div>
</section>

<section id="custom-bg" class="contact-area ptb_100">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-lg-5">
                <div class="section-heading text-center mb-3">
                    <h2><?php echo htmlspecialchars($contact_title, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="d-none d-sm-block mt-4">
                        <?php echo htmlspecialchars($contact_text, ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
                <div class="contact-us">
                    <ul>
                        <!-- Telefon 1 -->
                        <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-3">
                            <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                            <a class="d-block my-2" href="tel:<?= htmlspecialchars($phone1, ENT_QUOTES) ?>">
                                <h3><?= htmlspecialchars($phone1, ENT_QUOTES) ?></h3>
                            </a>
                        </li>
                        <!-- Email -->
                        <li class="contact-info color-3 bg-hover active hover-bottom text-center p-5 m-3">
                            <span><i class="fas fa-envelope-open-text fa-3x"></i></span>
                            <a class="d-none d-sm-block my-2"
                                href="mailto:<?= htmlspecialchars($siteEmail, ENT_QUOTES) ?>">
                                <h3><?= htmlspecialchars($siteEmail, ENT_QUOTES) ?></h3>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-lg-6 pt-4 pt-lg-0">
                <div class="contact-box text-center">
                    <?php if (!empty($errormsg))
                        echo $errormsg; ?>
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="site_csrf_token"
                            value="<?php echo htmlspecialchars($_SESSION['site_csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
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

<?php include 'footer.php'; ?>