<?php
require __DIR__ . '/init.php';
if (isset($_POST['save'])) {
  $status = "OK";
  $msg = "";
  $title = mysqli_real_escape_string($con, $_POST['blog_title']);
  $desc = mysqli_real_escape_string($con, $_POST['blog_desc']);
  $detail = mysqli_real_escape_string($con, $_POST['blog_detail']);
  if (strlen($title) < 5) {
    $msg .= "Başlık en az 5 karakter olmalı.<br>";
    $status = "NOTOK";
  }
  if (strlen($desc) > 150) {
    $msg .= "Açıklama 150 karakterden uzun olamaz.<br>";
    $status = "NOTOK";
  }
  if (strlen($detail) < 15) {
    $msg .= "Detay en az 15 karakter olmalı.<br>";
    $status = "NOTOK";
  }
  $uploads_dir = '../assets/img/blog';
  $tmp = $_FILES['ufile']['tmp_name'];
  $name = basename($_FILES['ufile']['name']);
  $new_name = rand(1000, 9999) . "_{$name}";

  if (empty($name) || !move_uploaded_file($tmp, "$uploads_dir/$new_name")) {
    $msg .= "Görsel yüklenemedi.<br>";
    $status = "NOTOK";
  }
  if ($status === "OK") {
    $sql = "INSERT INTO blog (blog_title, blog_desc, blog_detail, logo)
                VALUES ('$title','$desc','$detail','$new_name')";
    if (mysqli_query($con, $sql)) {
      header("Location: blog.php");
      exit;
    } else {
      $msg = "DB HATASI: " . mysqli_error($con);
    }
  }
}
?>
<?php
include "header.php";
include "sidebar.php";
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <h4>Blog Ekle</h4>
        </div>
      </div>
      <div class="row">
        <div class="col-xxl-9">
          <div class="card">
            <div class="card-body">
              <?php if (!empty($msg)): ?>
                <div class="alert alert-danger"><?= $msg ?></div>
              <?php endif; ?>
              <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                  <label class="form-label">Blog Başlığı</label>
                  <input type="text" name="blog_title" class="form-control">
                </div>
                <div class="mb-3">
                  <label class="form-label">Kısa Açıklama</label>
                  <textarea name="blog_desc" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Blog Detay</label>
                  <textarea name="blog_detail" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Görsel</label>
                  <input type="file" name="ufile" class="form-control">
                </div>
                <button type="submit" name="save" class="btn btn-primary">Ekle</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>