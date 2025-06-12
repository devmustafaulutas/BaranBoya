<?php
// /vogue/dashboard/twofa_settings.php

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../z_db.php';
require_once __DIR__ . '/lib/security.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

// 1) Sadece oturum açmış kullanıcılar görebilsin:
if (empty($_SESSION['authenticated']) || empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$user   = $_SESSION['username'];
$g      = new GoogleAuthenticator();
$issuer = 'Baran Boya';

// 2) POST ile “yeniden oluştur” talebi gelirse, secret & backup kodları yeniden yarat:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['regen'])) {
    // Yeni secret
    $secret    = $g->generateSecret();
    $secretEnc = encryptSecret($secret);
    // Yeni backup kodları
    $codes     = generateBackupCodes(8);
    $hashed    = hashBackupCodes($codes);
    $backupJson= json_encode($hashed);

    // Veritabanına kaydet
    $u = $con->prepare("
        UPDATE admin
           SET `2fa_secret_enc` = ?, `backup_codes` = ?
         WHERE username = ?
    ");
    $u->bind_param('sss', $secretEnc, $backupJson, $user);
    $u->execute();
    $u->close();

    // Oturumda gösterilecek kodlar
    $_SESSION['backup_codes'] = $codes;
}

// 3) Mevcut secret & kodları çek
$stmt = $con->prepare("
    SELECT twofa_enabled, `2fa_secret_enc`, `backup_codes`
      FROM admin
     WHERE username = ? LIMIT 1
");
$stmt->bind_param('s', $user);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Eğer daha önce hiç kurulmamışsa ilk sefer için yarat
if (!$row['twofa_enabled'] || empty($row['2fa_secret_enc'])) {
    $secret    = $g->generateSecret();
    $secretEnc = encryptSecret($secret);
    $codes     = generateBackupCodes(8);
    $hashed    = hashBackupCodes($codes);
    $backupJson= json_encode($hashed);

    $u = $con->prepare("
        UPDATE admin
           SET twofa_enabled   = 1,
               `2fa_secret_enc` = ?, 
               `backup_codes`   = ?
         WHERE username = ?
    ");
    $u->bind_param('sss', $secretEnc, $backupJson, $user);
    $u->execute();
    $u->close();

    $_SESSION['backup_codes'] = $codes;
} else {
    // Zaten kurulmuş: secret'i çöz, oturumdan kodları al
    $secret    = decryptSecret($row['2fa_secret_enc']);
}
$showCodes = $_SESSION['backup_codes'] ?? [];

$otpauth = "otpauth://totp/{$issuer}:{$user}"
         . "?secret={$secret}&issuer={$issuer}"
         . "&algorithm=SHA1&digits=6&period=30";

// 4) Sayfa layout'u
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0 text-white">2FA Ayarları</h4>
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

            <form method="post">
              <button name="regen" type="submit" class="btn btn-warning w-100 mb-2">
                QR &amp; Kodları Yeniden Oluştur
              </button>
              <a href="index.php" class="btn btn-secondary w-100">Panoya Dön</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

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
<?php include __DIR__ . '/footer.php'; ?>
