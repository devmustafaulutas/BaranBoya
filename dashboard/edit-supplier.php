<?php
// dashboard/suppliers_edit.php
require __DIR__ . '/init.php';

$msg    = '';
$status = 'OK';
$id     = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) {
    header('Location: suppliers.php');
    exit;
}

// Fetch existing supplier image
$stmt = $con->prepare("SELECT resim FROM tedarikcilerimiz WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($currentImage);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: suppliers.php');
    exit;
}
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    if (!empty($_FILES['resim']['name']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
        $file    = $_FILES['resim'];
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array($ext, $allowed)) {
            $msg    = 'Sadece JPG, JPEG, PNG ve GIF formatlarına izin verilmektedir.';
            $status = 'NOTOK';
        } else {
            $newName   = uniqid('sup_') . ".{$ext}";
            $uploadDir = __DIR__ . '/../assets/img/tedarikcilerimiz/';
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                // Delete old
                if ($currentImage && file_exists($uploadDir . $currentImage)) {
                    unlink($uploadDir . $currentImage);
                }
                $currentImage = $newName;
            } else {
                $msg    = 'Dosya yükleme sırasında bir hata oluştu.';
                $status = 'NOTOK';
            }
        }
    }
    if ($status === 'OK') {
        $stmt2 = $con->prepare(
            "UPDATE tedarikcilerimiz SET resim = ? WHERE id = ?"
        );
        $stmt2->bind_param('si', $currentImage, $id);
        if ($stmt2->execute()) {
            header('Location: suppliers.php');
            exit;
        } else {
            $msg = 'Veritabanı hatası: ' . htmlspecialchars($stmt2->error, ENT_QUOTES);
        }
        $stmt2->close();
    }
}

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tedarikçi Görselini Güncelle</h5>
            <a href="suppliers" class="btn btn-secondary btn-sm">Geri Dön</a>
          </div>
          <div class="card-body">
            <?php if ($msg): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($msg, ENT_QUOTES) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label">Mevcut Resim</label><br>
                <img
                  src="<?= htmlspecialchars('../assets/img/tedarikcilerimiz/' . $currentImage, ENT_QUOTES) ?>"
                  alt="Tedarikçi"
                  class="img-fluid mb-3"
                  style="max-width:150px;"
                >
              </div>
              <div class="mb-3">
                <label for="resim" class="form-label">Yeni Resim (isteğe bağlı)</label>
                <input
                  type="file"
                  id="resim"
                  name="resim"
                  accept="image/*"
                  class="form-control"
                >
              </div>
              <div class="text-end">
                <button type="submit" name="save" class="btn btn-warning w-10">Güncelle</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
