<?php
require __DIR__ . '/init.php';

$msg = "";
$status = "OK";
if (isset($_POST['save'])) {
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['resim']['tmp_name'];
        $origName = basename($_FILES['resim']['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array($ext, $allowed)) {
            $msg = "Sadece JPG, JPEG, PNG ve GIF formatlarına izin verilmektedir.";
            $status = "NOTOK";
        } else {
            $newName = uniqid('sup_').".".$ext;
            $uploadDir = '../assets/img/tedarikcilerimiz/';
            if (!move_uploaded_file($tmpName, $uploadDir.$newName)) {
                $msg = "Dosya yükleme sırasında bir hata oluştu.";
                $status = "NOTOK";
            }
        }
    } else {
        $msg = "Lütfen bir resim seçin.";
        $status = "NOTOK";
    }
    if ($status === "OK") {
        $sql = "INSERT INTO tedarikcilerimiz (resim) VALUES ('".mysqli_real_escape_string($con, $newName)."')";
        if (mysqli_query($con, $sql)) {
            header("Location: suppliers");
            exit;
        } else {
            $msg = "Veritabanı hatası: " . mysqli_error($con);
        }
    }
}
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-6">
          <h4 class="page-title">Yeni Tedarikçi Ekle</h4>
        </div>
        <div class="col-6 text-end">
          <a href="suppliers" class="btn btn-secondary">Geri Dön</a>
        </div>
      </div>
      <?php if (!empty($msg)): ?>
        <div class="alert alert-danger"><?= $msg ?></div>
      <?php endif; ?>
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                  <label class="form-label">Tedarikçi Resmi</label>
                  <input type="file" name="resim" accept="image/*" class="form-control" required>
                </div>
                <div class="text-end">
                  <button type="submit" name="save" class="btn btn-primary">Kaydet</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"; ?>
