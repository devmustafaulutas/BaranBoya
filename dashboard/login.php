<?php
session_start();
include_once("../z_db.php");
require '../vendor/autoload.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = mysqli_real_escape_string($con, $_POST['username']);
    $passwordInput = mysqli_real_escape_string($con, $_POST['password']);

    $sql = "SELECT id, username, password, 2fa_secret 
            FROM admin 
            WHERE username = '$usernameInput'";
    $res = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($res)) {
        if ($row['password'] === $passwordInput) {
            $_SESSION['temp_user_id']   = $row['id'];
            $_SESSION['temp_username']  = $row['username'];

            if (empty($row['2fa_secret'])) {
                header('Location: setup_2fa.php');
                exit;
            } else {
                header('Location: verify_2fa.php');
                exit;
            }
        } else {
            $msg = "Şifre yanlış.";
        }
    } else {
        $msg = "Kullanıcı bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title>Giriş | Baran Boya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                    <div class="mt-3 d-grid">
                                        <button type="submit" class="btn btn-primary">Giriş Yap</button>
                                    </div>
                                </form>
                                <?php if (isset($msg)): ?>
                                  <p class="text-danger mt-3"><?= htmlspecialchars($msg) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end auth-page-content -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; 2023 Your Company. All rights reserved.</p>
        </div>
    </footer>
</div><!-- end auth-page-wrapper -->
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/libs/feather-icons/feather.min.js"></script>
<script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="assets/js/plugins.js"></script>
<script src="assets/js/login.js"></script>
</body>
</html>
