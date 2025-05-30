<?php
require __DIR__ . '/init.php';

// Kategorileri çek
$kategori_query = "SELECT * FROM kategoriler ORDER BY isim ASC";
$kategori_result = mysqli_query($con, $kategori_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Metin alanları
  $isim = $_POST['isim'];
  $aciklama = $_POST['aciklama'];
  $ozellikler = $_POST['ozellikler'];
  $kimyasalyapi = $_POST['kimyasalyapi'];
  $renk = $_POST['renk'];
  $uygulamasekli = $_POST['uygulamasekli'];
  $kullanimalani = $_POST['kullanimalani'];
  $fiyat = $_POST['fiyat'];
  $stok = $_POST['stok'];
  $kategori_id = $_POST['kategori_id'];
  $alt_kategori_id = $_POST['alt_kategori_id'];
  $alt_kategori_alt_id = isset($_POST['alt_kategori_alt_id']) && $_POST['alt_kategori_alt_id'] !== ''
    ? $_POST['alt_kategori_alt_id']
    : null;

  // Resim yükleme
  $resim = '';
  if (isset($_FILES['resim']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "../assets/img/products/";
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    $tmp = $_FILES['resim']['tmp_name'];
    $name = basename($_FILES['resim']['name']);
    $size = $_FILES['resim']['size'];
    $type = mime_content_type($tmp);

    if (in_array($type, $allowed_types) && $size <= $max_size) {
      $ext = pathinfo($name, PATHINFO_EXTENSION);
      $new_name = uniqid('urun_', true) . ".$ext";
      $dest = $upload_dir . $new_name;
      if (move_uploaded_file($tmp, $dest)) {
        $resim = "assets/img/products/$new_name";
      } else {
        $error_message = "Resim yüklenirken bir hata oluştu.";
      }
    } else {
      $error_message = "Geçersiz dosya türü veya boyut çok büyük.";
    }
  } else {
    $error_message = "Resim yüklenirken bir hata oluştu.";
  }

  // Veritabanına ekle
  if (!isset($error_message)) {
    $sql = "INSERT INTO urunler
            (isim, aciklama, ozellikler, kimyasalyapi, renk, uygulamasekli, kullanimalani,
             fiyat, stok, resim, kategori_id, alt_kategori_id, alt_kategori_alt_id)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $con->prepare($sql);

    $stmt->bind_param(
      "sssssssdisiii",
      $isim,
      $aciklama,
      $ozelikler,
      $kimyasalyapi,
      $renk,
      $uygulamasekli,
      $kullanimalani,
      $fiyat,
      $stok,
      $resim,
      $kategori_id,
      $alt_kategori_id,
      $alt_kategori_alt_id
    );

    $stmt->execute();
    $stmt->close();

    header("Location: products.php");
    exit();
  }
}
include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';
?>


<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Yeni Ürün Ekle</h5>
            <a href="products.php" class="btn btn-primary btn-sm">Geri</a>
          </div>
          <div class="card-body">
            <?php if (!empty($error_message)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error_message, ENT_QUOTES) ?></div>
            <?php endif; ?>

            <form action="productadd.php" method="post" enctype="multipart/form-data">
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <label for="isim" class="form-label">Ürün İsmi</label>
                  <input
                    type="text"
                    id="isim"
                    name="isim"
                    class="form-control"
                    required
                  >
                </div>
                <div class="col-12 col-md-6">
                  <label for="fiyat" class="form-label">Fiyat (₺)</label>
                  <input
                    type="number"
                    step="0.01"
                    id="fiyat"
                    name="fiyat"
                    class="form-control"
                    required
                  >
                </div>
                <div class="col-12">
                  <label for="aciklama" class="form-label">Açıklama</label>
                  <textarea
                    id="aciklama"
                    name="aciklama"
                    class="form-control"
                    rows="2"
                    required
                  ></textarea>
                </div>
                <div class="col-12">
                  <label for="ozellikler" class="form-label">Özellikler</label>
                  <textarea
                    id="ozellikler"
                    name="ozellikler"
                    class="form-control"
                    rows="2"
                  ></textarea>
                </div>
                <div class="col-12 col-md-4">
                  <label for="kimyasalyapi" class="form-label">Kimyasal Yapı</label>
                  <input
                    type="text"
                    id="kimyasalyapi"
                    name="kimyasalyapi"
                    class="form-control"
                  >
                </div>
                <div class="col-12 col-md-4">
                  <label for="renk" class="form-label">Renk</label>
                  <input
                    type="text"
                    id="renk"
                    name="renk"
                    class="form-control"
                  >
                </div>
                <div class="col-12 col-md-4">
                  <label for="uygulamasekli" class="form-label">Uygulama Şekli</label>
                  <input
                    type="text"
                    id="uygulamasekli"
                    name="uygulamasekli"
                    class="form-control"
                  >
                </div>
                <div class="col-12 col-md-6">
                  <label for="kullanimalani" class="form-label">Kullanım Alanı</label>
                  <input
                    type="text"
                    id="kullanimalani"
                    name="kullanimalani"
                    class="form-control"
                  >
                </div>
                <div class="col-12 col-md-6">
                  <label for="stok" class="form-label">Stok</label>
                  <input
                    type="number"
                    id="stok"
                    name="stok"
                    class="form-control"
                    required
                  >
                </div>
                <div class="col-12 col-md-12">
                  <label for="resim" class="form-label">Ürün Resmi</label>
                  <input
                    type="file"
                    id="resim"
                    name="resim"
                    accept="image/*"
                    class="form-control"
                    required
                  >
                </div>
                <div class="col-12 col-md-4">
                  <label for="kategori_id" class="form-label">Kategori</label>
                  <select
                    id="kategori_id"
                    name="kategori_id"
                    class="form-select"
                    required
                  >
                    <option value="">Seçiniz</option>
                    <?php while ($k = mysqli_fetch_assoc($kategori_result)): ?>
                      <option value="<?= $k['id'] ?>">
                        <?= htmlspecialchars($k['isim'], ENT_QUOTES, 'UTF-8') ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="col-12 col-md-4">
                  <label for="alt_kategori_id" class="form-label">Alt Kategori</label>
                  <select
                    id="alt_kategori_id"
                    name="alt_kategori_id"
                    class="form-select"
                    required
                  >
                    <option value="">Seçiniz</option>
                  </select>
                </div>
                <div class="col-12 col-md-4">
                  <label for="alt_kategori_alt_id" class="form-label">Alt-Alt Kategori</label>
                  <select
                    id="alt_kategori_alt_id"
                    name="alt_kategori_alt_id"
                    class="form-select"
                  >
                    <option value="">Seçiniz</option>
                  </select>
                </div>
              </div>
              <div class="mt-4 text-end">
                <button type="submit" name="save" class="btn btn-success">Ekle</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include  __DIR__  . '/footer.php'; ?>


<script>
  // Alt kategori çekme
  document.getElementById('kategori_id').addEventListener('change', function () {
    const cat = this.value,
      alt = document.getElementById('alt_kategori_id'),
      altalt = document.getElementById('alt_kategori_alt_id');
    alt.innerHTML = '<option value="">Seçiniz</option>';
    altalt.innerHTML = '<option value="">Seçiniz</option>';
    if (!cat) return;
    fetch('get_alt_kategoriler.php?kategori_id=' + cat)
      .then(r => r.json())
      .then(j => j.forEach(o => {
        const opt = document.createElement('option');
        opt.value = o.id; opt.text = o.isim;
        alt.appendChild(opt);
      }));
  });

  // Alt-alt kategori çekme
  document.getElementById('alt_kategori_id').addEventListener('change', function () {
    const sub = this.value,
      altalt = document.getElementById('alt_kategori_alt_id');
    altalt.innerHTML = '<option value="">Seçiniz</option>';
    if (!sub) return;
    fetch('get_alt_alt_kategoriler.php?alt_kategori_id=' + sub)
      .then(r => r.json())
      .then(j => {
        if (j.length) j.forEach(o => {
          const opt = document.createElement('option');
          opt.value = o.id; opt.text = o.isim;
          altalt.appendChild(opt);
        });
      });
  });
</script>