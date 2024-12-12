<?php
include "header.php";
error_reporting(E_ALL); // Tüm hataları ve uyarıları göster

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
        $errormsg = "İsim en az 5 karakter olmalıdır.";
        $status = "NOTOK";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errormsg = "Geçersiz email formatı.";
        $status = "NOTOK";
    }
    if (strlen($message) < 10) {
        $errormsg = "Mesaj en az 10 karakter olmalıdır.";
        $status = "NOTOK";
    }

    if ($status == "OK") {
        // PHPMailer ile email gönderme işlemi
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com';
            $mail->Password = 'your-email-password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('your-email@example.com', 'Your Name');
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Yeni Mesaj';
            $mail->Body    = "İsim: $name<br>Email: $email<br>Telefon: $phone<br>Mesaj: $message";

            $mail->send();
            echo 'Mesaj başarıyla gönderildi.';
        } catch (Exception $e) {
            echo "Mesaj gönderilemedi. Hata: {$mail->ErrorInfo}";
        }
    } else {
        echo $errormsg;
    }
}

// Veritabanından hizmet bilgilerini alalım
$services_query = "SELECT * FROM services";
$services_result = mysqli_query($con, $services_query);
?>

<!-- Hizmetler Bölümü -->
<section class="section services-area">
    <div class="container">
        <div class="row">
            <?php while ($service = mysqli_fetch_assoc($services_result)): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="single-service">
                    <h3><?= htmlspecialchars($service['name']); ?></h3>
                    <p><?= htmlspecialchars($service['description']); ?></p>
                    <a href="servicedetail.php?service_id=<?= urlencode(encrypt_id($service['id'])); ?>" class="btn btn-primary">Detaylar</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>