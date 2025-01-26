<?php
session_start();
include_once("../z_db.php");
require '../vendor/autoload.php'; // Kök dizindeki vendor klasörünü dahil edin
use \Sonata\GoogleAuthenticator\GoogleAuthenticator;

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['2fa_code'];
    $username = $_SESSION['username'];

    $query = "SELECT 2fa_secret FROM admin WHERE username = '$username'";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $secret = $row['secret'];

        $g = new GoogleAuthenticator();
        if ($g->checkCode($secret, $code)) {
            // 2FA doğrulaması başarılı
            $_SESSION['authenticated'] = true;
            header("Location: dashboard/index.php");
            exit;
        } else {
            $msg = "2FA kodu geçersiz.";
        }
    } else {
        $msg = "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2FA Doğrulama</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/login.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="auth-page-wrapper">
        <div class="auth-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">2FA Doğrulama</h5>
                                    <i class="fas fa-shield-alt fa-3x text-primary"></i>
                                </div>
                                <div class="p-2 mt-4">
                                    <form method="post">
                                        <div class="mb-3">
                                            <label for="2fa_code" class="form-label">2FA Kodu:</label>
                                            <input type="text" id="2fa_code" name="2fa_code" class="form-control" required>
                                        </div>
                                        <div class="mt-3 d-grid">
                                            <button type="submit" class="btn btn-primary">Doğrula</button>
                                        </div>
                                    </form>
                                    <?php if (isset($msg)) { echo "<p class='text-danger mt-3'>$msg</p>"; } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end auth-page-content -->

        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="mb-0">&copy; 2023 Your Company. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/login.js"></script>
</body>
</html>