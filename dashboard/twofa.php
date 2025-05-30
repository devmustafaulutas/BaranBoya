<?php
// dashboard/twofa.php

session_start();
// Eğer kullanıcı login olup da 2FA kurulumuna gelmişse burada:
// (login.php’de 2FA kurulmamışsa twofa.php’ye yönlendirdiğinizi varsayıyoruz)
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// DB ve security
require_once __DIR__ . '/../z_db.php';
require_once __DIR__ . '/lib/security.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$g      = new GoogleAuthenticator();
$user   = $_SESSION['username'];
$issuer = 'Baran Boya';

// 1) “Etkinleştir” POST’u: twofa_enabled = 1 olarak işaretle ve login’e dön
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upd = $con->prepare("UPDATE admin SET twofa_enabled = 1 WHERE username = ?");
    $upd->bind_param('s', $user);
    $upd->execute();
    // bir kez gösterilen backup kodlarını temizleyelim
    unset($_SESSION['backup_codes']);
    header('Location: login.php');
    exit;
}

// 2) Secret ve backup kodları çek / oluştur
$stmt = $con->prepare("
    SELECT twofa_enabled, `2fa_secret_enc`, `backup_codes`
      FROM admin
     WHERE username = ? LIMIT 1
");
$stmt->bind_param('s', $user);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Eğer henüz enable edilmediyse setup adımındayız
if (!$row['twofa_enabled']) {
    // Secret yoksa oluştur
    if (empty($row['2fa_secret_enc'])) {
        $secret    = $g->generateSecret();
        $secretEnc = encryptSecret($secret);
        $codes     = generateBackupCodes(8);
        $hashed    = hashBackupCodes($codes);
        $backupJson= json_encode($hashed);

        $u = $con->prepare("
            UPDATE admin
               SET `2fa_secret_enc` = ?, `backup_codes` = ?
             WHERE username = ?
        ");
        $u->bind_param('sss', $secretEnc, $backupJson, $user);
        $u->execute();
        $u->close();

        // code’ları ekranda göstermek üzere session’da sakla
        $_SESSION['backup_codes'] = $codes;
    } else {
        // zaten oluşturulmuş: decrypt et
        $secret = decryptSecret($row['2fa_secret_enc']);
    }
    // backup listesi
    $showCodes = $_SESSION['backup_codes'] ?? [];
} else {
    // eğer enable zaten 1 ise, bu sayfayı yeniden gösterme
    header('Location: index.php');
    exit;
}

// 3) OTP URI
$otpauth = "otpauth://totp/{$issuer}:{$user}"
         . "?secret={$secret}&issuer={$issuer}"
         . "&algorithm=SHA1&digits=6&period=30";

// 4) Layout
include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';
?>
<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header text-white text-center">
            <h4 class="mb-0">2FA Kurulumu</h4>
          </div>
          <div class="card-body text-center">
            <p>
              Google Authenticator uygulamasında
              <strong><?= htmlspecialchars($issuer) ?>:<?= htmlspecialchars($user) ?></strong>
              olarak eklemek için QR kodu okutun.
            </p>
            <div id="qrcode" class="d-flex justify-content-center mb-4"></div>

            <?php if (!empty($showCodes)): ?>
              <hr>
              <p><strong>Kurtarma Kodlarınız (bir kez görüntülendi):</strong></p>
              <ul class="list-group list-group-flush mb-3">
                <?php foreach ($showCodes as $code): ?>
                  <li class="list-group-item bg-transparent">
                    <code><?= htmlspecialchars($code) ?></code>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <!-- 5) Etkinleştir Butonu -->
            <form method="post">
              <button type="submit" class="btn btn-success w-100">
                2FA’yı Etkinleştir ve Girişe Dön
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- QRCode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    new QRCode(document.getElementById('qrcode'), {
      text: "<?= $otpauth ?>",
      width: 200,
      height: 200
    });
  });
</script>

  <?php include "footer.php"; ?>

