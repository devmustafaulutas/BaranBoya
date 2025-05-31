<?php
require_once __DIR__ . '/init.php';

$action   = $_GET['action']  ?? '';
$table    = $_GET['table']   ?? '';
$id       = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors   = [];
$messages = [];

if (
    $action === 'delete'
    && in_array($table, ['kategoriler','alt_kategoriler','alt_kategoriler_alt'], true)
    && $id > 0
) {
    $stmtDel = $con->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmtDel->bind_param('i', $id);
    if ($stmtDel->execute()) {
        $messages[] = "Silme işlemi başarılı.";
    } else {
        $errors[] = "Silme sırasında hata: " . $stmtDel->error;
    }
    $stmtDel->close();

    header("Location: categorys.php");
    exit;
}

$edit_tbl = null;
$edit_id  = null;
$r        = []; 

if (
    $action === 'edit'
    && in_array($table, ['kategoriler','alt_kategoriler','alt_kategoriler_alt'], true)
    && $id > 0
) {
    $edit_tbl = $table;
    $edit_id  = $id;

    if ($edit_tbl === 'kategoriler') {
        $safeTable = 'kategoriler';
    }
    elseif ($edit_tbl === 'alt_kategoriler') {
        $safeTable = 'alt_kategoriler';
    }
    else {
        $safeTable = 'alt_kategoriler_alt';
    }

    $sql = "SELECT * FROM `$safeTable` WHERE id = $edit_id LIMIT 1";
    $res = mysqli_query($con, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $r = mysqli_fetch_assoc($res);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tbl       = $_POST['table']      ?? '';
    $is_edit   = !empty($_POST['id']);
    $isim      = trim($_POST['isim']   ?? '');
    $old_resim = trim($_POST['old_resim'] ?? '');
    $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

    if (mb_strlen($isim) < 3) {
        $errors[] = "İsim en az 3 karakter olmalı.";
    }
    if (!in_array($tbl, ['kategoriler','alt_kategoriler','alt_kategoriler_alt'], true)) {
        $errors[] = "Geçersiz tablo seçimi.";
    }

    $resim = $old_resim;
    if (
        isset($_FILES['resim'])
        && $_FILES['resim']['error'] === UPLOAD_ERR_OK
    ) {
        $allowed_ext = ['jpg','jpeg','png','gif'];
        $tmpName = $_FILES['resim']['tmp_name'];
        $ext     = strtolower(pathinfo($_FILES['resim']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext, true)) {
            $errors[] = "Yalnızca JPG, PNG veya GIF dosyaları yükleyebilirsiniz.";
        } else {
            $uploadDir = __DIR__ . '/../assets/img/categorys/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName  = uniqid('cat_', true) . ".{$ext}";
            if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                $resim = $fileName;
            } else {
                $errors[] = "Resim yükleme başarısız.";
            }
        }
    }

    if (count($errors) === 0) {
        if ($tbl === 'kategoriler') {
            if ($is_edit) {
                $parent_kat = $parent_id ?? null;
                $sql      = "UPDATE kategoriler SET isim = ?, resim = ?, kategori_id = ? WHERE id = ?";
                $stmt     = $con->prepare($sql);
                $stmt->bind_param('ssii', $isim, $resim, $parent_kat, $_POST['id']);
                $msg_text = "Kategori güncellendi.";
            } else {
                $parent_kat = $parent_id ?? null;
                $sql      = "INSERT INTO kategoriler (isim, resim, kategori_id) VALUES (?, ?, ?)";
                $stmt     = $con->prepare($sql);
                $stmt->bind_param('ssi', $isim, $resim, $parent_kat);
                $msg_text = "Yeni kategori eklendi.";
            }
        }
        elseif ($tbl === 'alt_kategoriler') {
            if ($parent_id === null || $parent_id <= 0) {
                $errors[] = "Lütfen önce bir üst kategori seçin.";
            } else {
                if ($is_edit) {
                    $parent_alt = $parent_id;
                    $sql      = "UPDATE alt_kategoriler SET isim = ?, resim = ?, kategori_id = ? WHERE id = ?";
                    $stmt     = $con->prepare($sql);
                    $stmt->bind_param('ssii', $isim, $resim, $parent_alt, $_POST['id']);
                    $msg_text = "Alt kategori güncellendi.";
                } else {
                    $parent_alt = $parent_id;
                    $sql      = "INSERT INTO alt_kategoriler (isim, resim, kategori_id) VALUES (?, ?, ?)";
                    $stmt     = $con->prepare($sql);
                    $stmt->bind_param('ssi', $isim, $resim, $parent_alt);
                    $msg_text = "Yeni alt kategori eklendi.";
                }
            }
        }
        else { 
            if ($parent_id === null || $parent_id <= 0) {
                $errors[] = "Lütfen önce bir üst alt kategori seçin.";
            } else {
                if ($is_edit) {
                    $parent_alt_alt = $parent_id;
                    $sql      = "
                        UPDATE alt_kategoriler_alt
                        SET isim = ?, resim = ?, alt_kategori_id = ?
                        WHERE id = ?
                    ";
                    $stmt     = $con->prepare($sql);
                    $stmt->bind_param('ssii', $isim, $resim, $parent_alt_alt, $_POST['id']);
                    $msg_text = "Alt-alt kategori güncellendi.";
                } else {
                    $parent_alt_alt = $parent_id;
                    $sql      = "
                        INSERT INTO alt_kategoriler_alt (isim, resim, alt_kategori_id)
                        VALUES (?, ?, ?)
                    ";
                    $stmt     = $con->prepare($sql);
                    $stmt->bind_param('ssi', $isim, $resim, $parent_alt_alt);
                    $msg_text = "Yeni alt-alt kategori eklendi.";
                }
            }
        }

        if (empty($errors) && isset($stmt)) {
            if ($stmt->execute()) {
                $messages[] = $msg_text;
                header("Location: categorys.php");
                exit;
            } else {
                $errors[] = "Veritabanı hatası: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$kats   = $con->query("SELECT * FROM kategoriler ORDER BY isim ASC");
$sub    = $con->query("
    SELECT ak.id, ak.isim, ak.resim, ak.kategori_id, k.isim AS kategori_adi
    FROM alt_kategoriler ak
    JOIN kategoriler k ON ak.kategori_id = k.id
    ORDER BY ak.isim ASC
");
$subsub = $con->query("
    SELECT aas.id, aas.isim, aas.resim, aas.alt_kategori_id, ak.isim AS alt_kategori_adi
    FROM alt_kategoriler_alt aas
    JOIN alt_kategoriler ak ON aas.alt_kategori_id = ak.id
    ORDER BY aas.isim ASC
");

?>

<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid mt-4">
      <h2 class="mb-4 text-center">Kategori Yönetim Paneli</h2>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
              <li><?php echo htmlspecialchars($e, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
      <?php if (!empty($messages)): ?>
        <div class="alert alert-success">
          <ul class="mb-0">
            <?php foreach ($messages as $m): ?>
              <li><?php echo htmlspecialchars($m, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <button
            class="nav-link <?php echo (!$edit_tbl || $edit_tbl==='kategoriler') ? 'active' : '' ?>"
            data-bs-toggle="tab"
            data-bs-target="#tab-main"
          >
            Kategori
          </button>
        </li>
        <li class="nav-item">
          <button
            class="nav-link <?php echo ($edit_tbl==='alt_kategoriler') ? 'active' : '' ?>"
            data-bs-toggle="tab"
            data-bs-target="#tab-sub"
          >
            Alt Kategori
          </button>
        </li>
        <li class="nav-item">
          <button
            class="nav-link <?php echo ($edit_tbl==='alt_kategoriler_alt') ? 'active' : '' ?>"
            data-bs-toggle="tab"
            data-bs-target="#tab-subsub"
          >
            Alt-Alt Kategori
          </button>
        </li>
      </ul>

      <div class="tab-content bg-white p-4 border border-top-0 rounded-bottom">
        <div
          id="tab-main"
          class="tab-pane fade <?php echo (!$edit_tbl || $edit_tbl==='kategoriler') ? 'show active' : '' ?>"
        >
          <h5 class="mb-3">
            <?php echo ($edit_tbl==='kategoriler' ? 'Kategori Güncelle' : 'Yeni Kategori Ekle'); ?>
          </h5>
          <form method="post" enctype="multipart/form-data" class="row gy-3">
            <input type="hidden" name="table" value="kategoriler">
            <?php if ($edit_tbl==='kategoriler'): ?>
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($r['id'] ?? '', ENT_QUOTES); ?>">
              <input type="hidden" name="old_resim" value="<?php echo htmlspecialchars($r['resim'] ?? '', ENT_QUOTES); ?>">
            <?php endif; ?>

            <div class="col-md-6">
              <label class="form-label">İsim</label>
              <input
                type="text"
                name="isim"
                class="form-control"
                placeholder="Kategori adı"
                value="<?php echo htmlspecialchars($r['isim'] ?? '', ENT_QUOTES); ?>"
                required
              >
            </div>
            <div class="col-md-6">
              <label class="form-label">Resim (JPG/PNG/GIF)</label>
              <input type="file" name="resim" class="form-control">
              <?php if ($edit_tbl==='kategoriler' && !empty($r['resim'])): ?>
                <div class="mt-2">
                  <img
                    src="../assets/img/categorys/<?php echo htmlspecialchars($r['resim'], ENT_QUOTES); ?>"
                    class="rounded"
                    style="width:40px; height:40px; object-fit:cover;"
                    alt="Eski Resim"
                  >
                </div>
              <?php endif; ?>
            </div>
            <div class="col-12">
              <button
                type="submit"
                class="btn <?php echo ($edit_tbl==='kategoriler' ? 'btn-warning' : 'btn-success'); ?>"
              >
                <?php echo ($edit_tbl==='kategoriler' ? 'Güncelle' : 'Ekle'); ?>
              </button>
            </div>
          </form>

          <hr class="my-4">

          <h5 class="mb-3">Mevcut Kategoriler</h5>
          <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:50px">#</th>
                <th>İsim</th>
                <th style="width:70px">Resim</th>
                <th style="width:150px">İşlemler</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($kats->num_rows > 0): ?>
                <?php while ($o = $kats->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo htmlspecialchars($o['isim'], ENT_QUOTES); ?></td>
                    <td>
                      <?php if (!empty($o['resim'])): ?>
                        <img
                          src="../assets/img/categorys/<?php echo htmlspecialchars($o['resim'], ENT_QUOTES); ?>"
                          class="rounded"
                          style="width:40px; height:40px; object-fit:cover;"
                          alt="Logo"
                        >
                      <?php endif; ?>
                    </td>
                    <td>
                      <a
                        href="?action=edit&table=kategoriler&id=<?php echo $o['id']; ?>#tab-main"
                        class="btn btn-sm btn-primary"
                      >
                        Düzenle
                      </a>
                      <a
                        href="?action=delete&table=kategoriler&id=<?php echo $o['id']; ?>"
                        onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');"
                        class="btn btn-sm btn-danger"
                      >
                        Sil
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4" class="text-center">Henüz hiçbir kategori eklenmemiş.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div
          id="tab-sub"
          class="tab-pane fade <?php echo ($edit_tbl==='alt_kategoriler') ? 'show active' : '' ?>"
        >
          <h5 class="mb-3">
            <?php echo ($edit_tbl==='alt_kategoriler' ? 'Alt Kategori Güncelle' : 'Yeni Alt Kategori Ekle'); ?>
          </h5>
          <form method="post" enctype="multipart/form-data" class="row gy-3">
            <input type="hidden" name="table" value="alt_kategoriler">
            <?php if ($edit_tbl==='alt_kategoriler'): ?>
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($r['id'] ?? '', ENT_QUOTES); ?>">
              <input type="hidden" name="old_resim" value="<?php echo htmlspecialchars($r['resim'] ?? '', ENT_QUOTES); ?>">
            <?php endif; ?>

            <div class="col-md-4">
              <label class="form-label">Üst Kategori</label>
              <select name="parent_id" class="form-select" required>
                <option value="">Seçiniz</option>
                <?php
                $kats->data_seek(0);
                while ($c = $kats->fetch_assoc()):
                  $sel = (isset($r['kategori_id']) && $r['kategori_id'] == $c['id']) ? 'selected' : '';
                ?>
                  <option value="<?php echo $c['id']; ?>" <?php echo $sel; ?>>
                    <?php echo htmlspecialchars($c['isim'], ENT_QUOTES); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">İsim</label>
              <input
                type="text"
                name="isim"
                class="form-control"
                placeholder="Alt kategori adı"
                value="<?php echo htmlspecialchars($r['isim'] ?? '', ENT_QUOTES); ?>"
                required
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Resim (JPG/PNG/GIF)</label>
              <input type="file" name="resim" class="form-control">
              <?php if ($edit_tbl==='alt_kategoriler' && !empty($r['resim'])): ?>
                <div class="mt-2">
                  <img
                    src="../assets/img/categorys/<?php echo htmlspecialchars($r['resim'], ENT_QUOTES); ?>"
                    class="rounded"
                    style="width:40px; height:40px; object-fit:cover;"
                    alt="Eski Resim"
                  >
                </div>
              <?php endif; ?>
            </div>
            <div class="col-12">
              <button
                type="submit"
                class="btn <?php echo ($edit_tbl==='alt_kategoriler' ? 'btn-warning' : 'btn-success'); ?>"
              >
                <?php echo ($edit_tbl==='alt_kategoriler' ? 'Güncelle' : 'Ekle'); ?>
              </button>
            </div>
          </form>

          <hr class="my-4">

          <h5 class="mb-3">Mevcut Alt Kategoriler</h5>
          <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:50px">#</th>
                <th>İsim</th>
                <th>Üst Kategori</th>
                <th style="width:70px">Resim</th>
                <th style="width:150px">İşlemler</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($sub->num_rows > 0): ?>
                <?php while ($o = $sub->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo htmlspecialchars($o['isim'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($o['kategori_adi'], ENT_QUOTES); ?></td>
                    <td>
                      <?php if (!empty($o['resim'])): ?>
                        <img
                          src="../assets/img/categorys/<?php echo htmlspecialchars($o['resim'], ENT_QUOTES); ?>"
                          class="rounded"
                          style="width:40px; height:40px; object-fit:cover;"
                          alt="Logo"
                        >
                      <?php endif; ?>
                    </td>
                    <td>
                      <a
                        href="?action=edit&table=alt_kategoriler&id=<?php echo $o['id']; ?>#tab-sub"
                        class="btn btn-sm btn-primary"
                      >
                        Düzenle
                      </a>
                      <a
                        href="?action=delete&table=alt_kategoriler&id=<?php echo $o['id']; ?>"
                        onclick="return confirm('Bu alt kategoriyi silmek istediğinize emin misiniz?');"
                        class="btn btn-sm btn-danger"
                      >
                        Sil
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">Henüz hiçbir alt kategori eklenmemiş.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <!-- 3) ALT-ALT KATEGORİ TAB’I -->
        <div
          id="tab-subsub"
          class="tab-pane fade <?php echo ($edit_tbl==='alt_kategoriler_alt') ? 'show active' : '' ?>"
        >
          <h5 class="mb-3">
            <?php echo ($edit_tbl==='alt_kategoriler_alt' ? 'Alt-Alt Kategori Güncelle' : 'Yeni Alt-Alt Kategori Ekle'); ?>
          </h5>
          <form method="post" enctype="multipart/form-data" class="row gy-3">
            <input type="hidden" name="table" value="alt_kategoriler_alt">
            <?php if ($edit_tbl==='alt_kategoriler_alt'): ?>
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($r['id'], ENT_QUOTES); ?>">
              <input type="hidden" name="old_resim" value="<?php echo htmlspecialchars($r['resim'], ENT_QUOTES); ?>">
            <?php endif; ?>

            <div class="col-md-4">
              <label class="form-label">Üst Alt Kategori</label>
              <select name="parent_id" class="form-select" required>
                <option value="">Seçiniz</option>
                <?php
                $sub->data_seek(0);
                while ($c = $sub->fetch_assoc()):
                  $sel = (isset($r['alt_kategori_id']) && $r['alt_kategori_id'] == $c['id']) ? 'selected' : '';
                ?>
                  <option value="<?php echo $c['id']; ?>" <?php echo $sel; ?>>
                    <?php echo htmlspecialchars($c['isim'], ENT_QUOTES); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">İsim</label>
              <input
                type="text"
                name="isim"
                class="form-control"
                placeholder="Alt-alt kategori adı"
                value="<?php echo htmlspecialchars($r['isim'] ?? '', ENT_QUOTES); ?>"
                required
              >
            </div>
            <div class="col-md-4">
              <label class="form-label">Resim (JPG/PNG/GIF)</label>
              <input type="file" name="resim" class="form-control">
              <?php if ($edit_tbl==='alt_kategoriler_alt' && !empty($r['resim'])): ?>
                <div class="mt-2">
                  <img
                    src="../assets/img/categorys/<?php echo htmlspecialchars($r['resim'], ENT_QUOTES); ?>"
                    class="rounded"
                    style="width:40px; height:40px; object-fit:cover;"
                    alt="Eski Resim"
                  >
                </div>
              <?php endif; ?>
            </div>
            <div class="col-12">
              <button
                type="submit"
                class="btn <?php echo ($edit_tbl==='alt_kategoriler_alt' ? 'btn-warning' : 'btn-success'); ?>"
              >
                <?php echo ($edit_tbl==='alt_kategoriler_alt' ? 'Güncelle' : 'Ekle'); ?>
              </button>
            </div>
          </form>

          <hr class="my-4">

          <h5 class="mb-3">Mevcut Alt-Alt Kategoriler</h5>
          <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:50px">#</th>
                <th>İsim</th>
                <th>Üst Alt Kategori</th>
                <th style="width:70px">Resim</th>
                <th style="width:150px">İşlemler</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($subsub->num_rows > 0): ?>
                <?php while ($o = $subsub->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo $o['id']; ?></td>
                    <td><?php echo htmlspecialchars($o['isim'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($o['alt_kategori_adi'], ENT_QUOTES); ?></td>
                    <td>
                      <?php if (!empty($o['resim'])): ?>
                        <img
                          src="../assets/img/categorys/<?php echo htmlspecialchars($o['resim'], ENT_QUOTES); ?>"
                          class="rounded"
                          style="width:40px; height:40px; object-fit:cover;"
                          alt="Logo"
                        >
                      <?php endif; ?>
                    </td>
                    <td>
                      <a
                        href="?action=edit&table=alt_kategoriler_alt&id=<?php echo $o['id']; ?>#tab-subsub"
                        class="btn btn-sm btn-primary"
                      >
                        Düzenle
                      </a>
                      <a
                        href="?action=delete&table=alt_kategoriler_alt&id=<?php echo $o['id']; ?>"
                        onclick="return confirm('Bu alt-alt kategoriyi silmek istediğinize emin misiniz?');"
                        class="btn btn-sm btn-danger"
                      >
                        Sil
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center">Henüz hiçbir alt-alt kategori eklenmemiş.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
