<?php
// static.php
include "header.php";
include "sidebar.php";
include "../z_db.php";

$action = $_GET['action'] ?? 'list';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

// --- Silme ---
if ($action === 'delete' && $id) {
    mysqli_query($con, "DELETE FROM static WHERE id = $id");
    header("Location: static.php");
    exit;
}

// --- Ekleme ---
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $t = mysqli_real_escape_string($con, $_POST['stitle']);
    $s = mysqli_real_escape_string($con, $_POST['stext']);
    mysqli_query($con, "INSERT INTO static (stitle, stext, updated_at) VALUES ('$t', '$s', NOW())");
    header("Location: static.php");
    exit;
}

// --- Düzenleme ---
if ($action === 'edit' && $id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $t = mysqli_real_escape_string($con, $_POST['stitle']);
        $s = mysqli_real_escape_string($con, $_POST['stext']);
        mysqli_query($con, "UPDATE static SET stitle='$t', stext='$s', updated_at=NOW() WHERE id=$id");
        header("Location: static.php");
        exit;
    }
    $edit = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM static WHERE id=$id"));
}

// --- Listeleme ---
$items = mysqli_query($con, "SELECT * FROM static ORDER BY id DESC");
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- sayfa başlığı -->
      <div class="row mb-4">
        <div class="col-6">
          <h4 class="page-title">Statik İçerikler</h4>
        </div>
        <?php if ($action === 'list'): ?>
        <div class="col-6 text-end">
          <a href="static.php?action=add" class="btn btn-success">
            <i class="ri-add-line"></i> Yeni Ekle
          </a>
        </div>
        <?php endif; ?>
      </div>

      <?php if ($action === 'list'): ?>
      <div class="card">
        <div class="card-body p-0">
          <table class="table table-striped table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Başlık</th>
                <th>Metin</th>
                <th>Güncelleme</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              <?php if (mysqli_num_rows($items) > 0): ?>
                <?php while($r = mysqli_fetch_assoc($items)): ?>
                  <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['stitle']) ?></td>
                    <td><?= nl2br(htmlspecialchars(substr($r['stext'],0,50))) ?>…</td>
                    <td><?= $r['updated_at'] ?></td>
                    <td>
                      <a href="static.php?action=edit&id=<?= $r['id'] ?>"
                         class="btn btn-sm btn-warning">Düzenle</a>
                      <a href="static.php?action=delete&id=<?= $r['id'] ?>"
                         class="btn btn-sm btn-danger"
                         onclick="return confirm('Silinsin mi?')">Sil</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">Kayıt bulunamadı.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <?php elseif (in_array($action, ['add','edit'])): 
        $isEdit = $action === 'edit';
      ?>
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0"><?= $isEdit ? 'Statik İçerik Düzenle' : 'Yeni Statik İçerik Ekle' ?></h5>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="row g-3">
              <div class="col-lg-6">
                <label class="form-label">Başlık</label>
                <input 
                  type="text" 
                  name="stitle" 
                  class="form-control" 
                  required
                  value="<?= $isEdit ? htmlspecialchars($edit['stitle'], ENT_QUOTES) : '' ?>"
                >
              </div>
              <div class="col-lg-12">
                <label class="form-label">Metin</label>
                <textarea 
                  name="stext" 
                  class="form-control" 
                  rows="5" 
                  required
                ><?= $isEdit ? htmlspecialchars($edit['stext'], ENT_QUOTES) : '' ?></textarea>
              </div>
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">
                  <?= $isEdit ? 'Güncelle' : 'Ekle' ?>
                </button>
                <a href="static.php" class="btn btn-secondary ms-2">İptal</a>
              </div>
            </div>
          </form>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>
<?php include "footer.php"; ?>
