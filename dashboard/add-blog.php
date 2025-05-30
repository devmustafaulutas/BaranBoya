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
include  __DIR__ . '/header.php';
include  __DIR__ . '/sidebar.php';
?>

<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Yeni Blog Ekle</h4>
            <div class="col-12 text-end">
              <a href="blog" class="btn btn-secondary">Geri</a>
            </div>
          </div>
          <div class="card-body">
            <?= $msg ?? '' ?>
            <form method="post" enctype="multipart/form-data">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="blog_title" class="form-label">Blog Başlığı</label>
                  <input type="text" id="blog_title" name="blog_title" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label for="ufile" class="form-label">Görsel</label>
                  <input type="file" id="ufile" name="ufile" accept="image/*" class="form-control" required>
                </div>
                <div class="col-12">
                  <label for="blog_desc" class="form-label">Kısa Açıklama</label>
                  <textarea id="blog_desc" name="blog_desc" class="form-control" rows="2"
                    required><?= htmlspecialchars($_POST['blog_desc'] ?? '', ENT_QUOTES) ?></textarea>
                </div>
                <div class="col-12">
                  <label for="blog_detail" class="form-label">Blog Detay</label>
                  <textarea id="blog_detail" name="blog_detail" class="form-control" rows="5"
                    required><?= htmlspecialchars($_POST['blog_detail'] ?? '', ENT_QUOTES) ?></textarea>
                </div>
              </div>
              <div class="mt-4 text-end">
                <button type="submit" name="save" class="btn btn-success">Kaydet</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include __DIR__ . "footer.php"; ?>