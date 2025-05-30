<?php
// dashboard/suppliers_add.php
require __DIR__ . '/init.php';

$msg    = '';
$status = 'OK';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resim'])) {
    // File validation
    $file     = $_FILES['resim'];
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed  = ['jpg', 'jpeg', 'png', 'gif'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $msg    = 'Lütfen bir resim seçin.';
        $status = 'NOTOK';
    } elseif (!in_array($ext, $allowed)) {
        $msg    = 'Sadece JPG, JPEG, PNG ve GIF formatlarına izin verilmektedir.';
        $status = 'NOTOK';
    } else {
        $newName   = uniqid('sup_') . '.' . $ext;
        $uploadDir = __DIR__ . '/../assets/img/tedarikcilerimiz/';

        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
            $msg    = 'Dosya yükleme sırasında bir hata oluştu.';
            $status = 'NOTOK';
        }
    }

    if ($status === 'OK') {
        $stmt = $con->prepare(
            'INSERT INTO tedarikcilerimiz (resim, created_at) VALUES (?, NOW())'
        );
        $stmt->bind_param('s', $newName);
        if ($stmt->execute()) {
            header('Location: suppliers.php');
            exit;
        } else {
            $msg = 'Veritabanı hatası: ' . htmlspecialchars($stmt->error, ENT_QUOTES);
        }
        $stmt->close();
    }
}

include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';
?>

<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card shadow-sm">
          <div class="card-header  text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tedarikçi Ekle</h5>
            <a href="suppliers.php" class="btn btn-secondary btn-sm">Geri</a>
          </div>
          <div class="card-body">
            <?php if ($msg): ?>
              <div class="alert alert-danger"><?= $msg ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="resim" class="form-label">Tedarikçi Resmi</label>
                <input
                  type="file"
                  id="resim"
                  name="resim"
                  accept="image/*"
                  class="form-control"
                  required
                >
              </div>
              <div class="text-end">
                <button type="submit" name="save" class="btn btn-success">Kaydet</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <?php include "footer.php"; ?>
