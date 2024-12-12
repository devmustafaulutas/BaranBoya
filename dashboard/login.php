
<?php
include_once("../z_db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
  $status = "OK"; //initial status
  $msg = "";
  $username = mysqli_real_escape_string($con, $_POST['username']); //fetching details through post method
  $password = mysqli_real_escape_string($con, $_POST['password']);


  if ($status == "OK") {

    // Retrieve username and password from database according to user's input, preventing sql injection
    $query = "SELECT * FROM admin WHERE (username = '". mysqli_real_escape_string($con, $_POST['username']) . "') AND (password = '" . mysqli_real_escape_string($con, $_POST['password']) . "')";


    if ($stmt = mysqli_prepare($con, $query)) {

      /* execute query */
      mysqli_stmt_execute($stmt);

      /* store result */
      mysqli_stmt_store_result($stmt);

      $num = mysqli_stmt_num_rows($stmt);

      /* close statement */
      mysqli_stmt_close($stmt);

    //mysqli_close($con);
    // Check username and password match

   if ($num == 1){

session_start();
        // Set username session variable
        $_SESSION['username'] = $username;

       $username = $_SESSION['username'];
       print "
       <script language='javascript'>
         window.location = 'index.php';
       </script>";

}

else{
$errormsg= "
<div class='alert alert-danger alert-dismissible alert-outline fade show'>
                 Username And/Or Password Does Not Match.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>"; //printing error if found in validation

}}}


else {

$errormsg= "
<div class='alert alert-danger alert-dismissible alert-outline fade show'>
                ".$msg."
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>"; //printing error if found in validation


}
}

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
  data-sidebar-image="none">


<!-- Mirrored from themesbrand.com/velzon/html/default/auth-signin-cover.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 24 Jun 2022 20:40:59 GMT -->

<head>

  <meta charset="utf-8" />
  <title>Giriş | Baran Boya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  <!-- App favicon -->
  <link rel="shortcut icon" href="assets/images/favicon.icon">

  <!-- Layout config Js -->
  <script src="assets/js/layout.js"></script>
  <!-- Bootstrap Css -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- Icons Css -->
  <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
  <!-- custom Css-->
  <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

  <!-- auth-page wrapper -->
  <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="bg-overlay"></div>
    <!-- auth-page content -->
    <div class="auth-page-content overflow-hidden pt-lg-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="card overflow-hidden">
              <div class="row g-0">
                <div class="col-lg-6">
                  <div class="p-lg-5 p-4 auth-one-bg h-100">
                    <div class="bg-overlay"></div>
                    <div class="position-relative h-100 d-flex flex-column">
                      <div class="mb-4">
                      <?php
    $rr=mysqli_query($con,"SELECT logo FROM logo");
$r = mysqli_fetch_row($rr);
$ufile = $r[0];
?>
                        <a href="index" class="d-block">
                          <img src="../assets/img/logo/<?php print $ufile;?>" height="18">
                        </a>
                      </div>

                    </div>
                  </div>
                </div>
                <!-- end col -->

                <div class="col-lg-6">
                  <div class="p-lg-5 p-4">
                    <div>
                      <h5 class="text-primary">Hoşgeldiniz !</h5>
                      <p class="text-muted">Admin paneline girmek için giriş yapın.</p>
                    </div>

                    <div class="mt-4">
                    <?php
						if($_SERVER['REQUEST_METHOD'] == 'POST' && ($errormsg!=""))
						{
						print $errormsg;
						}
						?>
                                    <form class="user" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"], ENT_QUOTES, "utf-8"); ?>" method="post">
                        <div class="mb-3">
                          <label for="username" class="form-label">Kullanıcı adı</label>
                          <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı adı girin">
                        </div>

                        <div class="mb-3">

                          <label class="form-label" for="password-input">Şifre</label>
                          <div class="position-relative auth-pass-inputgroup mb-3">
                            <input type="password" class="form-control pe-5" name="password" placeholder="Şifre girin"
                              id="password-input">

                          </div>
                        </div>



                        <div class="mt-4">
                          <button class="btn btn-success w-100" type="submit">Giriş</button>
                        </div>


                      </form>
                    </div>


                  </div>
                </div>
                <!-- end col -->
              </div>
              <!-- end row -->
            </div>
            <!-- end card -->
          </div>
          <!-- end col -->

        </div>
        <!-- end row -->
      </div>
      <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="text-center">
            <?php
    $rr=mysqli_query($con,"SELECT site_footer FROM siteconfig");
$r = mysqli_fetch_row($rr);
$site_footer = $r[0];
?>
            <p class="mb-0">
             <?php print $site_footer ?>
              </p>
            </div>
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

  <!-- password-addon init -->
  <script src="assets/js/pages/password-addon.init.js"></script>
</body>


<!-- Mirrored from themesbrand.com/velzon/html/default/auth-signin-cover.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 24 Jun 2022 20:40:59 GMT -->

</html>
