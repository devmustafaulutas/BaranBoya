<?php
// dashboard/logo.php
require __DIR__ . '/init.php';

// Mevcut logo bilgisini çek
$row = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT * FROM logo WHERE id=1")
);

// Form gönderimi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['logo'])) {
    $uploadDir = __DIR__ . '/../assets/img/logo/';
    $file      = $_FILES['logo'];
    $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName   = uniqid('logo_') . ".{$ext}";

    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        // Eski dosyayı sil
        if ($row['logo'] && file_exists($uploadDir . $row['logo'])) {
            unlink($uploadDir . $row['logo']);
        }
        // DB güncelle
        $stmt = $con->prepare(
            "UPDATE logo SET logo = ?, updated_at = NOW() WHERE id = 1"
        );
        $stmt->bind_param('s', $newName);
        $stmt->execute();
        $stmt->close();

        $msg      = '<div class="alert alert-success">Logo başarıyla güncellendi.</div>';
        $row['logo'] = $newName;
    } else {
        $msg = '<div class="alert alert-danger">Logo yükleme hatası.</div>';
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
          <div class="card-header text-white">
            <h5 class="mb-0">Logo Yükleme</h5>
          </div>
          <div class="card-body">
            <?= $msg ?? '' ?>
            <form method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="logo" class="form-label">Yeni Logo Seç</label>
                <input
                  class="form-control"
                  type="file"
                  id="logo"
                  name="logo"
                  accept="image/*"
                  required
                >
              </div>
              <button type="submit" class="btn btn-success">Yükle</button>
            </form>
            <?php if (!empty($row['logo'])): ?>
              <hr>
              <p class="mt-3 mb-1">Mevcut Logo:</p>
              <img
                src="<?= htmlspecialchars('../assets/img/logo/' . $row['logo'], ENT_QUOTES) ?>"
                alt="Mevcut Logo"
                class="img-fluid"
                style="max-height:80px;"
              >
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
