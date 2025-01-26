<?php
include_once("../z_db.php");
require '../vendor/autoload.php'; // Kök dizindeki vendor klasörünü dahil edin

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $status = "OK"; //initial status
    $msg = "";
    $username = mysqli_real_escape_string($con, $_POST['username']); //fetching details through post method
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $code = isset($_POST['2fa_code']) ? $_POST['2fa_code'] : '';

    if ($status == "OK") {
        // Retrieve username and password from database according to user's input, preventing sql injection
        $query = "SELECT id, username, password, 2fa_secret FROM admin WHERE (username = '". mysqli_real_escape_string($con, $_POST['username']) . "') AND (password = '" . mysqli_real_escape_string($con, $_POST['password']) . "')";
        if ($stmt = mysqli_prepare($con, $query)) {
            /* execute query */
            mysqli_stmt_execute($stmt);
            /* store result */
            mysqli_stmt_store_result($stmt);
            $num = mysqli_stmt_num_rows($stmt);

            if ($num > 0) {
                mysqli_stmt_bind_result($stmt, $id, $username, $password, $secret); // secret burada 2fa_secret değerini tutacaktır.
                mysqli_stmt_fetch($stmt);

                $g = new GoogleAuthenticator();
                if ($g->checkCode($secret, $code)) {
                    // 2FA doğrulaması başarılı
                    $_SESSION['username'] = $username;
                    $_SESSION['authenticated'] = true;
                    header("Location: index.php");
                    exit;
                } else {
                    $msg = "2FA kodu geçersiz.";
                }
            } else {
                $msg = "Kullanıcı adı veya şifre yanlış.";
            }
        } else {
            $msg = "Veritabanı sorgusu başarısız: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Giriş | Baran Boya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.icon">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css -->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- Custom Css -->
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
                                    <h5 class="text-primary">Admin Giriş</h5>
                                    <i class="fas fa-user-shield fa-3x text-primary"></i>
                                </div>
                                <div class="p-2 mt-4">
                                    <form method="post">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Kullanıcı Adı:</label>
                                            <input type="text" id="username" name="username" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Şifre:</label>
                                            <input type="password" id="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="2fa_code" class="form-label">2FA Kodu:</label>
                                            <input type="text" id="2fa_code" name="2fa_code" class="form-control" required>
                                        </div>
                                        <div class="mt-3 d-grid">
                                            <button type="submit" class="btn btn-primary">Giriş</button>
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