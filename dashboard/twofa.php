<?php
require __DIR__ . '/init.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

global $con;
$g = new GoogleAuthenticator();
$user = $_SESSION['username'];
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
  $secret = $g->generateSecret();
  $secretEnc = encryptSecret($secret);

  // Backup kodları üret ve hash'le
  $codes = generateBackupCodes(8);
  $hashed = hashBackupCodes($codes);
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
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-12 col-lg-12">
        <div class="card shadow-sm">
          <div class="card-header  text-white text-center">
            <h4 class="mb-0">İki Aşamalı Doğrulama (2FA)</h4>
          </div>
          <div class="card-body text-center">
            <p>
              Google Authenticator uygulamasında
              <strong><?= htmlspecialchars($issuer) ?>:<?= htmlspecialchars($user) ?></strong>
              hesabınızı eklemek için QR kodu taratın . <br>
              Admin paneline sadece bu iki aşamalı doğrulama koduna veya kurtarma koduna sahip kullanıcılar giriş yapabilir.
            </p>
            <div id="qrcode" class="d-flex justify-content-center mb-4"></div>

            <?php if (!empty($showCodes)): ?>
              <hr>
              <p><strong>Kurtarma Kodlarınız (bir kez görüntülendi):</strong></p>
              <ul class="list-group list-group-flush text-center mb-3">
                <?php foreach ($showCodes as $code): ?>
                  <li class="list-group-item bg-transparent"><code><?= htmlspecialchars($code) ?></code></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <a href="login.php" class="btn btn-success w-10">Giriş Sayfasına Dön</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- QRCode.js yüklemesi -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById('qrcode'), {
      text: "<?= $otpauth ?>",
      width: 200,
      height: 200
    });
  });
</script>
<?php include "footer.php"; ?>