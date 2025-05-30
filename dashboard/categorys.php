<?php
require __DIR__ . '/init.php';

$action = $_GET['action'] ?? '';
$table = $_GET['table'] ?? '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($action === 'delete' && in_array($table, ['kategoriler', 'alt_kategoriler', 'alt_kategoriler_alt'])) {
    $stmt = $con->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: categorys.php');
    exit;
}

// ADD/UPDATE işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tbl = $_POST['table'];
    $is_edit = !empty($_POST['id']);
    $isim = $_POST['isim'];
    $resim = '';

    // Resim upload
// Resim upload
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
        $dir = '../assets/img/categorys/';
        $tmp = $_FILES['resim']['tmp_name'];
        $ext = pathinfo($_FILES['resim']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('cat_', true) . ".{$ext}";
        move_uploaded_file($tmp, "{$dir}{$fileName}");

        // DATABASE'E TAM PATH KAYDEDİLİYOR
        $resim = "assets/img/categorys/{$fileName}";
    }
    // Parent ID (alt- and alt-alt için)
    $parent = $_POST['parent_id'] ?? null;

    // Sorgu hazırlığı
    if ($tbl === 'kategoriler') {
        if ($is_edit) {
            $sql = 'UPDATE kategoriler SET isim = ?, resim = ? WHERE id = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssi', $isim, $resim, $_POST['id']);
        } else {
            $sql = 'INSERT INTO kategoriler (isim, resim) VALUES (?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ss', $isim, $resim);
        }
    } elseif ($tbl === 'alt_kategoriler') {
        if ($is_edit) {
            $sql = 'UPDATE alt_kategoriler SET isim = ?, resim = ?, kategori_id = ? WHERE id = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssii', $isim, $resim, $parent, $_POST['id']);
        } else {
            $sql = 'INSERT INTO alt_kategoriler (isim, resim, kategori_id) VALUES (?, ?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssi', $isim, $resim, $parent);
        }
    } else {
        // alt_kategoriler_alt
        if ($is_edit) {
            $sql = 'UPDATE alt_kategoriler_alt SET isim = ?, resim = ?, alt_kategori_id = ? WHERE id = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssii', $isim, $resim, $parent, $_POST['id']);
        } else {
            $sql = 'INSERT INTO alt_kategoriler_alt (isim, resim, alt_kategori_id) VALUES (?, ?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ssi', $isim, $resim, $parent);
        }
    }
    $stmt->execute();
    header('Location: categorys.php');
    exit;
}

// Verileri çek
$kats = $con->query("SELECT * FROM kategoriler ORDER BY isim ASC");
$sub = $con->query("SELECT ak.*, k.isim AS kategori_adi FROM alt_kategoriler ak JOIN kategoriler k ON ak.kategori_id = k.id ORDER BY ak.isim ASC");
$subsub = $con->query("SELECT aas.*, ak.isim AS alt_kategori_adi FROM alt_kategoriler_alt aas JOIN alt_kategoriler ak ON aas.alt_kategori_id = ak.id ORDER BY aas.isim ASC");

// Düzenleme için GET parametreleri
$edit_tbl = ($_GET['action'] === 'edit') ? $_GET['table'] : null;
$edit_id = $edit_tbl ? $id : null;
if ($edit_tbl) {
    $r = $con->query("SELECT * FROM $edit_tbl WHERE id = $edit_id")->fetch_assoc();
}
include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid mt-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><button
                        class="nav-link <?= (!$edit_tbl || $edit_tbl === 'kategoriler') ? 'active' : '' ?>"
                        data-bs-toggle="tab" data-bs-target="#main">Kategori</button></li>
                <li class="nav-item"><button class="nav-link <?= ($edit_tbl === 'alt_kategoriler') ? 'active' : '' ?>"
                        data-bs-toggle="tab" data-bs-target="#sub">Alt Kategori</button></li>
                <li class="nav-item"><button
                        class="nav-link <?= ($edit_tbl === 'alt_kategoriler_alt') ? 'active' : '' ?>"
                        data-bs-toggle="tab" data-bs-target="#subsub">Alt-Alt Kategori</button></li>
            </ul>
            <div class="tab-content p-3">
                <!-- Ana Kategori -->
                <div id="main"
                    class="tab-pane fade <?= (!$edit_tbl || $edit_tbl === 'kategoriler') ? 'show active' : '' ?>">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="table" value="kategoriler">
                        <?php if ($edit_id): ?><input type="hidden" name="id" value="<?= $edit_id ?>"><?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">İsim</label>
                            <input type="text" name="isim" class="form-control" value="<?= $r['isim'] ?? '' ?>"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resim</label>
                            <input type="file" name="resim" class="form-control">
                        </div>
                        <button type="submit"
                            class="btn btn-<?= $edit_id ? 'warning' : 'success' ?>"><?= $edit_id ? 'Güncelle' : 'Ekle' ?></button>
                    </form>
                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>İsim</th>
                                <th>Resim</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($o = $kats->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $o['id'] ?></td>
                                    <td><?= htmlspecialchars($o['isim']) ?></td>
                                    <td><?php if ($o['resim']): ?><img src="../<?= $o['resim'] ?>"
                                                style="width:40px"><?php endif; ?></td>
                                    <td>
                                        <a href="?action=edit&table=kategoriler&id=<?= $o['id'] ?>#main"
                                            class="btn btn-sm btn-primary">Düzenle</a>
                                        <a href="?action=delete&table=kategoriler&id=<?= $o['id'] ?>"
                                            class="btn btn-sm btn-danger">Sil</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Alt Kategori -->
                <div id="sub" class="tab-pane fade <?= ($edit_tbl === 'alt_kategoriler') ? 'show active' : '' ?>">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="table" value="alt_kategoriler">
                        <?php if ($edit_tbl === 'alt_kategoriler'): ?><input type="hidden" name="id"
                                value="<?= $edit_id ?>"><?php endif; ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Üst Kategori</label>
                                <select name="parent_id" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    <?php $kats->data_seek(0);
                                    while ($c = $kats->fetch_assoc()): ?>
                                        <option value="<?= $c['id'] ?>" <?= (($r['kategori_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['isim']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">İsim</label>
                                <input type="text" name="isim" class="form-control" value="<?= $r['isim'] ?? '' ?>"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resim</label>
                            <input type="file" name="resim" class="form-control">
                        </div>
                        <button type="submit"
                            class="btn btn-<?= ($edit_tbl === 'alt_kategoriler') ? 'warning' : 'success' ?>"><?= ($edit_tbl === 'alt_kategoriler') ? 'Güncelle' : 'Ekle' ?></button>
                    </form>
                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>İsim</th>
                                <th>Üst Kategori</th>
                                <th>Resim</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($o = $sub->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $o['id'] ?></td>
                                    <td><?= htmlspecialchars($o['isim']) ?></td>
                                    <td><?= htmlspecialchars($o['kategori_adi']) ?></td>
                                    <td><?php if ($o['resim']): ?><img src="../assets/img/categorys/<?= $o['resim'] ?>"
                                                style="width:40px"><?php endif; ?></td>
                                    <td>
                                        <a href="?action=edit&table=alt_kategoriler&id=<?= $o['id'] ?>#sub"
                                            class="btn btn-sm btn-primary">Düzenle</a>
                                        <a href="?action=delete&table=alt_kategoriler&id=<?= $o['id'] ?>"
                                            class="btn btn-sm btn-danger">Sil</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Alt-Alt Kategori -->
                <div id="subsub"
                    class="tab-pane fade <?= ($edit_tbl === 'alt_kategoriler_alt') ? 'show active' : '' ?>">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="table" value="alt_kategoriler_alt">
                        <?php if ($edit_tbl === 'alt_kategoriler_alt'): ?><input type="hidden" name="id"
                                value="<?= $edit_id ?>"><?php endif; ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Üst Alt Kategori</label>
                                <select name="parent_id" class="form-select" required>
                                    <option value="">Seçiniz</option>
                                    <?php $sub->data_seek(0);
                                    while ($c = $sub->fetch_assoc()): ?>
                                        <option value="<?= $c['id'] ?>" <?= (($r['alt_kategori_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['isim']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">İsim</label>
                                <input type="text" name="isim" class="form-control" value="<?= $r['isim'] ?? '' ?>"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resim</label>
                            <input type="file" name="resim" class="form-control">
                        </div>
                        <button type="submit"
                            class="btn btn-<?= ($edit_tbl === 'alt_kategoriler_alt') ? 'warning' : 'success' ?>"><?= ($edit_tbl === 'alt_kategoriler_alt') ? 'Güncelle' : 'Ekle' ?></button>
                    </form>
                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>İsim</th>
                                <th>Üst Alt Kategori</th>
                                <th>Resim</th>
                                <th>İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($o = $subsub->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $o['id'] ?></td>
                                    <td><?= htmlspecialchars($o['isim']) ?></td>
                                    <td><?= htmlspecialchars($o['alt_kategori_adi']) ?></td>
                                    <td><?php if ($o['resim']): ?><img src="../assets/img/categorys/<?= $o['resim'] ?>"
                                                style="width:40px"><?php endif; ?></td>
                                    <td>
                                        <a href="?action=edit&table=alt_kategoriler_alt&id=<?= $o['id'] ?>#subsub"
                                            class="btn btn-sm btn-primary">Düzenle</a>
                                        <a href="?action=delete&table=alt_kategoriler_alt&id=<?= $o['id'] ?>"
                                            class="btn btn-sm btn-danger">Sil</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

  <?php include "footer.php"; ?>
