<?php
session_start();
include_once("../z_db.php");
require '../vendor/autoload.php'; // Kök dizindeki vendor klasörünü dahil edin

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

$g = new GoogleAuthenticator();
$secret = $g->generateSecret();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $query = "UPDATE admin SET 2fa_secret = '$secret' WHERE username = '$username'";
    if (mysqli_query($con, $query)) {
        echo "2FA başarıyla etkinleştirildi.";
    } else {
        echo "2FA etkinleştirilirken bir hata oluştu.";
    }
    exit;
}

$username = $_SESSION['username'];
$qrCodeUrl = GoogleQrUrl::generate($username, $secret, 'YourAppName');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2FA Kurulumu</title>
</head>
<body>
    <h1>2FA Kurulumu</h1>
    <p>Google Authenticator uygulamasını kullanarak aşağıdaki QR kodunu tarayın:</p>
    <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code">
    <form method="post">
        <input type="hidden" name="secret" value="<?php echo $secret; ?>">
        <button type="submit">2FA'yı Etkinleştir</button>
    </form>
</body>
</html>