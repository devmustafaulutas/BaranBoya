<?php
// dashboard/blog_edit.php
require __DIR__ . '/init.php';

// ID from query string (sanitize)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: blog.php');
    exit;
}

// Fetch existing blog entry
$stmt = $con->prepare("SELECT blog_title, blog_desc, blog_detail FROM blog WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($blog_title, $blog_desc, $blog_detail);
if (!$stmt->fetch()) {
    $stmt->close();
    header('Location: blog.php');
    exit;
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $title  = trim($_POST['blog_title']);
    $desc   = trim($_POST['blog_desc']);
    $detail = trim($_POST['blog_detail']);
    $errors = [];

    // Validation rules
    if (strlen($title) < 5) {
        $errors[] = 'Başlık en az 5 karakter olmalı.';
    }
    if (strlen($desc) > 150) {
        $errors[] = 'Açıklama 150 karakterden uzun olamaz.';
    }
    if (strlen($detail) < 15) {
        $errors[] = 'Detay en az 15 karakter olmalı.';
    }

    if (empty($errors)) {
        $upd = $con->prepare(
            "UPDATE blog
                SET blog_title = ?, blog_desc = ?, blog_detail = ?, updated_at = NOW()
              WHERE id = ?"
        );
        $upd->bind_param('sssi', $title, $desc, $detail, $id);
        if ($upd->execute()) {
            $success = 'Güncelleme başarılı.';
            $blog_title  = $title;
            $blog_desc   = $desc;
            $blog_detail = $detail;
        } else {
            $errors[] = 'DB Hatası: ' . htmlspecialchars($upd->error);
        }
        $upd->close();
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
            <h5 class="mb-0">Blog Düzenle</h5>
            <a href="blog.php" class="btn btn-secondary btn-sm">Geri</a>
          </div>
          <div class="card-body">
            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                <ul class="mb-0">
                  <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php elseif (!empty($success)): ?>
              <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES) ?></div>
            <?php endif; ?>

            <form method="post">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="blog_title" class="form-label">Blog Başlığı</label>
                  <input
                    type="text"
                    id="blog_title"
                    name="blog_title"
                    class="form-control"
                    value="<?= htmlspecialchars($blog_title, ENT_QUOTES) ?>"
                    required
                  >
                </div>
                <div class="col-md-6">
                  <label for="blog_desc" class="form-label">Kısa Açıklama</label>
                  <textarea
                    id="blog_desc"
                    name="blog_desc"
                    class="form-control"
                    rows="2"
                    required
                  ><?= htmlspecialchars($blog_desc, ENT_QUOTES) ?></textarea>
                </div>
                <div class="col-12">
                  <label for="blog_detail" class="form-label">Blog Detay</label>
                  <textarea
                    id="blog_detail"
                    name="blog_detail"
                    class="form-control"
                    rows="5"
                    required
                  ><?= htmlspecialchars($blog_detail, ENT_QUOTES) ?></textarea>
                </div>
              </div>
              <div class="mt-4 text-end">
                <button type="submit" name="save" class="btn btn-success">Güncelle</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
