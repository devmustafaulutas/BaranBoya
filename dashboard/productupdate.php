<?php
require __DIR__ . '/init.php';

$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$oldResStmt = $con->prepare("SELECT resim FROM urunler WHERE id = ?");
$oldResStmt->bind_param("i", $product_id);
$oldResStmt->execute();
$oldResStmt->bind_result($resim);
$oldResStmt->fetch();
$oldResStmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isim               = $_POST['isim'];
    $aciklama           = $_POST['aciklama'];
    $ozellikler         = $_POST['ozellikler'];
    $kimyasalyapi       = $_POST['kimyasalyapi'];
    $renk               = $_POST['renk'];
    $uygulamasekli      = $_POST['uygulamasekli'];
    $kullanimalani      = $_POST['kullanimalani'];
    $fiyat              = $_POST['fiyat'];
    $stok               = $_POST['stok'];
    $kategori_id        = intval($_POST['kategori_id']);

    $alt_kategori_id = (isset($_POST['alt_kategori_id']) && $_POST['alt_kategori_id'] !== '')
        ? intval($_POST['alt_kategori_id'])
        : null;

    $alt_kategori_alt_id = (isset($_POST['alt_kategori_alt_id']) && $_POST['alt_kategori_alt_id'] !== '')
        ? intval($_POST['alt_kategori_alt_id'])
        : null;

    if (isset($_FILES['resim']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
        $upload_dir    = "../assets/img/products/";
        $allowed_types = ['image/jpeg','image/png','image/gif'];
        $tmp           = $_FILES['resim']['tmp_name'];
        $name          = basename($_FILES['resim']['name']);
        $type          = mime_content_type($tmp);
        $size          = $_FILES['resim']['size'];

        if (in_array($type, $allowed_types) && $size <= 2*1024*1024) {
            $ext      = pathinfo($name, PATHINFO_EXTENSION);
            $newName  = uniqid('urun_', true) . ".$ext";
            $dest     = $upload_dir . $newName;
            if (move_uploaded_file($tmp, $dest)) {
                if (!empty($resim) && file_exists($upload_dir . $resim)) {
                    unlink($upload_dir . $resim);
                }
                $resim = "assets/img/products/$newName";
            }
        }
    }

    $sql = "
      UPDATE urunler SET
        isim                 = ?,
        aciklama             = ?,
        ozellikler           = ?,
        kimyasalyapi         = ?,
        renk                 = ?,
        uygulamasekli        = ?,
        kullanimalani        = ?,
        fiyat                = ?,
        stok                 = ?,
        resim                = ?,
        kategori_id          = ?,
        alt_kategori_id      = ?,
        alt_kategori_alt_id  = ?
      WHERE id = ?
    ";
    $stmt = $con->prepare($sql);

    $stmt->bind_param(
        "sssssssdisiiii",
        $isim,
        $aciklama,
        $ozellikler,
        $kimyasalyapi,
        $renk,
        $uygulamasekli,
        $kullanimalani,
        $fiyat,
        $stok,
        $resim,
        $kategori_id,
        $alt_kategori_id,
        $alt_kategori_alt_id,
        $product_id
    );

    if (!$stmt->execute()) {
        die("Güncelleme hatası: " . $stmt->error);
    }
    $stmt->close();

    header("Location: products.php");
    exit();
}

$query = "
  SELECT 
    isim, aciklama, ozellikler, kimyasalyapi, renk, uygulamasekli, kullanimalani,
    fiyat, stok, resim, kategori_id, alt_kategori_id, alt_kategori_alt_id
  FROM urunler 
  WHERE id = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result(
    $isim, $aciklama, $ozellikler, $kimyasalyapi, $renk,
    $uygulamasekli, $kullanimalani, $fiyat, $stok, $resim,
    $kategori_id, $alt_kategori_id, $alt_kategori_alt_id
);
$stmt->fetch();
$stmt->close();

$kategori_result      = mysqli_query($con, "SELECT * FROM kategoriler ORDER BY isim ASC");
$alt_kategori_result  = mysqli_query($con, "SELECT * FROM alt_kategoriler ORDER BY isim ASC");
$alt_alt_result       = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt ORDER BY isim ASC");

include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <h5 class="mb-3">Ürün Güncelle</h5>
      <form action="productupdate.php?id=<?= $product_id ?>" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Ürün İsmi</label>
            <input type="text" name="isim" class="form-control" value="<?= htmlspecialchars($isim) ?>" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Fiyat</label>
            <input type="number" step="0.01" name="fiyat" class="form-control" value="<?= htmlspecialchars($fiyat) ?>" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($stok) ?>" required>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="aciklama" class="form-control" rows="2" required><?= htmlspecialchars($aciklama) ?></textarea>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Özellikler</label>
            <textarea name="ozellikler" class="form-control" rows="2"><?= htmlspecialchars($ozellikler) ?></textarea>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Kimyasal Yapı</label>
            <input type="text" name="kimyasalyapi" class="form-control" value="<?= htmlspecialchars($kimyasalyapi) ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Renk</label>
            <input type="text" name="renk" class="form-control" value="<?= htmlspecialchars($renk) ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Uygulama Şekli</label>
            <input type="text" name="uygulamasekli" class="form-control" value="<?= htmlspecialchars($uygulamasekli) ?>">
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Kullanım Alanı</label>
            <input type="text" name="kullanimalani" class="form-control" value="<?= htmlspecialchars($kullanimalani) ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Resim</label>
            <input type="file" name="resim" class="form-control">
            <?php if ($resim): ?>
              <img src="../<?= htmlspecialchars($resim) ?>" style="width:100px; margin-top:8px;" alt="Mevcut Resim">
            <?php endif; ?>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
              <option value="">Seçiniz</option>
              <?php while($k = mysqli_fetch_assoc($kategori_result)): ?>
                <option value="<?= $k['id'] ?>" <?= $k['id'] == $kategori_id ? 'selected' : '' ?>>
                  <?= htmlspecialchars($k['isim']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Alt Kategori</label>
            <select name="alt_kategori_id" class="form-select">
              <option value="">Seçiniz</option>
              <?php while($a = mysqli_fetch_assoc($alt_kategori_result)): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id'] == $alt_kategori_id ? 'selected' : '' ?>>
                  <?= htmlspecialchars($a['isim']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Alt-Alt Kategori</label>
            <select name="alt_kategori_alt_id" class="form-select">
              <option value="">Seçiniz</option>
              <?php while($b = mysqli_fetch_assoc($alt_alt_result)): ?>
                <option value="<?= $b['id'] ?>" <?= $b['id'] == $alt_kategori_alt_id ? 'selected' : '' ?>>
                  <?= htmlspecialchars($b['isim']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>
        <button class="btn btn-primary">Güncelle</button>
      </form>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>
