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


$selectedSector = isset($_GET['sector']) ? urldecode($_GET['sector']) : '';
$sectorName = "";
$sectorDescription = "";

if ($selectedSector === "Havacılık ve Savunma Sanayi") {
    $sectorName = "Havacılık ve Savunma Sanayi";
    $sectorDescription = "Havacılık ve savunma alanında endüstriyel boyanın önemi büyüktür. Uçak, helikopter ve savunma araçlarının yüksek sıcaklık, basınç ve kimyasal etkilere dayanıklı olması gerekir. Endüstriyel boyalar sayesinde düzenli bakım sıklığı azalır, korozyon önlenir ve güvenlik artırılır. Bunun yanı sıra askeri teçhizatlarda görünmezlik ve radar soğurucu boyalar kullanılarak stratejik avantaj elde edilir. Böylece havacılık ve savunma sektörü, ileri teknolojiye dayalı endüstriyel boyalarla güçlenir.";
} elseif ($selectedSector === "Denizcilik") {
    $sectorName = "Denizcilik";
    $sectorDescription = "Denizcilik sektöründe endüstriyel boyaların amacı korozyonla mücadele, gemi ve deniz araçlarını uzun süreli korumaktır. Tuzlu su, UV ışınları ve sert hava koşulları gibi çevresel faktörler göz önüne alındığında, doğru seçilen endüstriyel boyalarla gemilerin bakımı kolaylaşır, operasyonel maliyetler düşer. Su altı kaplamaları, teknelerin yakıt verimliliğini artırırken deniz canlılarının tutunmasını da azaltır.";
} elseif ($selectedSector === "Banyo") {
    $sectorName = "Banyo";
    $sectorDescription = "Banyo sektöründe endüstriyel boyalar, su ve neme karşı yüksek dayanım sağlamasıyla ön plana çıkar. Bu ortamlar için geliştirilmiş boyalar, küflenme ve lekelenmeye karşı dirençli olur. Aynı zamanda dekoratif etkiler katılarak, banyolarda estetik görünüm ve uzun ömürlü kaplama elde edilir. Böylece hem hijyeni hem de şıklığı bir arada sunar.";
} elseif ($selectedSector === "Mutfak") {
    $sectorName = "Mutfak";
    $sectorDescription = "Mutfaklarda endüstriyel boyaların kullanımı, yüksek ısıya ve yağ lekelerine karşı dayanıklılık açısından önem taşır. Fırın çevresi, ocak arkası gibi sıcak noktalarda rengin solmaması, kir tutmaması ve kolay temizlenmesi için özel boyalar tercih edilir. Böylece mutfakta konforlu bir çalışma alanı yaratılır.";
} elseif ($selectedSector === "Hobi ve Tasarım") {
    $sectorName = "Hobi ve Tasarım";
    $sectorDescription = "Hobi ve tasarım alanında endüstriyel boyalar, yaratıcılığı sınırlamadan farklı malzemelerde üstün koruma sağlar. Ahşap, metal veya kompozit yüzeylerde kullanılan çok amaçlı boyalar, hem canlı renkler hem de dayanıklılık sunar. Böylece sanatsal projeler ve tasarım uygulamaları daha uzun ömürlü olur.";
} else {
    $sectorName = "";
    $sectorDescription = "";
}

?>
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content text-center">
                    <h2 class="text-white text-uppercase mb-3"><?= htmlspecialchars($sectorName, ENT_QUOTES, 'UTF-8') ?></h2>
                    <ol class="breadcrumb d-flex justify-content-center">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item text-white active">Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hizmetler Bölümü -->
<section class="section sector-detail-area">
    <div class="container">
        <?php if ($sectorName): ?>
            <h2><?= htmlspecialchars($sectorName, ENT_QUOTES, 'UTF-8') ?></h2>
            <p><?= htmlspecialchars($sectorDescription, ENT_QUOTES, 'UTF-8') ?></p>
        <?php else: ?>
            <p>Sektör bilgisi bulunamadı.</p>
        <?php endif; ?>
    </div>
</section>

<?php include "footer.php"; ?>

<script>
// ...existing code...

document.addEventListener("DOMContentLoaded", function() {
  const sectorDetailArea = document.querySelector(".sector-detail-area");
  // Basit hover animasyonu örneği
  sectorDetailArea.addEventListener("mouseenter", () => {
    sectorDetailArea.style.transform = "scale(1.01)";
    sectorDetailArea.style.transition = "transform 0.5s";
  });
  sectorDetailArea.addEventListener("mouseleave", () => {
    sectorDetailArea.style.transform = "scale(1)";
  });

  // Ekstra animasyonlar için ikonları ekleyin
  const icons = document.createElement('div');
  icons.innerHTML = `
    <div class="animated-icon"></div>
    <div class="animated-icon"></div>
    <div class="animated-icon"></div>
    <div class="animated-icon"></div>
  `;
  sectorDetailArea.appendChild(icons);
});
// ...existing code...
</script>