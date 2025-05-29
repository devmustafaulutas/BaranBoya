<?php
require __DIR__ . '/init.php';

require __DIR__ . '/lib/security.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

global $con;
$g      = new GoogleAuthenticator();
$user   = $_SESSION['username'];
$issuer = 'Baran Boya';

// DB'den mevcut şifrelenmiş secret ve backup kayıtlarını çek
$stmt = $con->prepare("SELECT `2fa_secret_enc`, `backup_codes` FROM admin WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $user);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

$showCodes = [];

// İlk kurulum: secret yoksa oluştur ve kaydet
if (empty($row['2fa_secret_enc'])) {
    $secret     = $g->generateSecret();
    $secretEnc  = encryptSecret($secret);

    // Backup kodları üret ve hash'le
    $codes      = generateBackupCodes(8);
    $hashed     = hashBackupCodes($codes);
    $backupJson = json_encode($hashed);

    $upd = $con->prepare("UPDATE admin SET `2fa_secret_enc` = ?, `backup_codes` = ? WHERE username = ?");
    $upd->bind_param('sss', $secretEnc, $backupJson, $user);
    $upd->execute();
    $upd->close();

    $_SESSION['backup_codes'] = $codes;
    $showCodes = $codes;
} else {
    // Zaten kurulmuş: secret'i çöz
    $secret = decryptSecret($row['2fa_secret_enc']);
    if (isset($_SESSION['backup_codes'])) {
        $showCodes = $_SESSION['backup_codes'];
    }
}

// OTP URI oluştur
$otpauth = "otpauth://totp/{$issuer}:{$user}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";

// Layout include
include "header.php";
include "sidebar.php";
?>
<div class="content-wrapper" style="padding-top:70px;">
  <section class="content-header"><h1>2FA Kurulumu</h1></section>
  <section class="content">
    <div class="container d-flex justify-content-center">
      <div class="card" style="max-width:400px; width:100%;">
        <div class="card-body text-center">
          <p>Authenticator’da<br><strong><?=htmlspecialchars($issuer)?>:<?=htmlspecialchars($user)?></strong> eklemek için QR’yı okutun:</p>
          <div id="qrcode" class="mb-4"></div>

          <?php if (!empty($showCodes)): ?>
            <hr>
            <p><strong>Kurtarma Kodlarınız:</strong></p>
            <ul class="text-start">
              <?php foreach ($showCodes as $c): ?>
                <li><code><?=htmlspecialchars($c)?></code></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <a href="login.php" class="btn btn-success w-100 mt-3">Giriş Sayfasına Dön</a>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  new QRCode(document.getElementById('qrcode'), {
    text: "<?= $otpauth ?>",
    width: 200,
    height: 200
  });
</script>
<?php include "footer.php"; ?>