<?php
// twofa.php
include "header.php";
include "sidebar.php";
include "../z_db.php";
require '../vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

session_start();
$g = new GoogleAuthenticator();
$secret = $g->generateSecret();
$qr     = GoogleQrUrl::generate($_SESSION['username'], $secret, 'YourAppName');

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u = $_SESSION['username'];
  mysqli_query($con,"UPDATE admin SET 2fa_secret='$secret' WHERE username='$u'");
  $msg = "<div class='alert alert-success'>2FA Etkinleştirildi.</div>";
}
?>
<div class="main-content">
  <div class="page-content container-fluid">
    <h4>2 Aşamalı Doğrulama</h4>
    <?= $msg ?? '' ?>
    <p>Google Authenticator ile aşağıdaki QR’ı tarayın:</p>
    <img src="<?= $qr ?>" alt="QR Code">
    <form method="post"><button class="btn btn-primary mt-3">Etkinleştir</button></form>
  </div>
</div>
<?php include "footer.php"; ?>
