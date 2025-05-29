<?php
require __DIR__ . '/init.php';

// 1) Kategori filtreleme
$kategoriId = isset($_GET['kategori']) && is_numeric($_GET['kategori']) ? (int) $_GET['kategori'] : 0;

// 2) Sayfalama
$page    = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$perPage = 20;
$offset  = ($page - 1) * $perPage;

// 3) Toplam kayıt
$totalSql = "SELECT COUNT(*) FROM urunler WHERE 1" . ($kategoriId ? " AND kategori_id = ?" : "");
$stmt = $con->prepare($totalSql);
if ($kategoriId) $stmt->bind_param("i", $kategoriId);
$stmt->execute();
$stmt->bind_result($totalRows);
$stmt->fetch();
$stmt->close();
$totalPages = ceil($totalRows / $perPage);

// 4) Verileri çek
$dataSql = "
SELECT u.id, u.isim, u.aciklama, u.ozellikler, u.kimyasalyapi,
u.renk, u.uygulamasekli, u.kullanimalani,
u.fiyat, u.stok, u.resim,
k.isim AS kategori_adi,
ak.isim AS alt_kategori_adi,
aak.isim AS alt_alt_kategori_adi
FROM urunler u
LEFT JOIN kategoriler k  ON u.kategori_id           = k.id
LEFT JOIN alt_kategoriler ak  ON u.alt_kategori_id     = ak.id
LEFT JOIN alt_kategoriler_alt aak ON u.alt_kategori_alt_id = aak.id
WHERE 1" 
. ($kategoriId ? " AND u.kategori_id = ?" : "")
. " LIMIT ?, ?";
$stmt = $con->prepare($dataSql);
if ($kategoriId) {
  $stmt->bind_param("iii", $kategoriId, $offset, $perPage);
} else {
  $stmt->bind_param("ii", $offset, $perPage);
}
$stmt->execute();
$res = $stmt->get_result();
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      
      <!-- Başlık ve Filtre/Buton -->
      <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
          <h4 class="page-title mb-0">Ürün Listesi</h4>
        </div>
        <div class="col-12 col-md-6">
          <div class="d-flex flex-wrap justify-content-md-end gap-2">
            <form method="get" class="d-flex">
              <select name="kategori" class="form-select me-2" onchange="this.form.submit()">
                <option value="0">Tüm Kategoriler</option>
                <?php
                $katRes = $con->query("SELECT id,isim FROM kategoriler ORDER BY isim");
                while ($k = $katRes->fetch_assoc()):
                  $sel = $k['id'] == $kategoriId ? 'selected' : '';
                ?>
                  <option value="<?= $k['id'] ?>" <?= $sel ?>><?= htmlspecialchars($k['isim']) ?></option>
                <?php endwhile; ?>
              </select>
            </form>
            <a href="productadd.php" class="btn btn-success">
              <i class="ri-add-line"></i> Ürün Ekle
            </a>
          </div>
        </div>
      </div>

      <!-- Ürün Tablosu -->
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-bordered mb-0">
              <thead class="table-light">
                <tr>
                  <th class="d-none d-sm-table-cell">#</th>
                  <th>Resim</th>
                  <th>İsim</th>
                  <th class="d-none d-md-table-cell">Açıklama</th>
                  <th class="d-none d-lg-table-cell">Özellikler</th>
                  <th class="d-none d-lg-table-cell">Kimyasal Yapı</th>
                  <th class="d-none d-xl-table-cell">Renk</th>
                  <th class="d-none d-xl-table-cell">Uygulama Şekli</th>
                  <th class="d-none d-xl-table-cell">Kullanım Alanı</th>
                  <th class="d-none d-md-table-cell">Kategori</th>
                  <th class="d-none d-lg-table-cell">Alt Kategori</th>
                  <th class="d-none d-xl-table-cell">Alt-Alt Kategori</th>
                  <th class="d-none d-sm-table-cell">Fiyat</th>
                  <th class="d-none d-sm-table-cell">Stok</th>
                  <th>İşlemler</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($res->num_rows):
                  while ($u = $res->fetch_assoc()): ?>
                    <tr>
                      <td class="d-none d-sm-table-cell"><?= $u['id'] ?></td>
                      <td>
                        <img src="../<?= htmlspecialchars($u['resim']) ?>"
                             class="img-fluid"
                             style="max-width:50px;"
                             alt="">
                      </td>
                      <td><?= htmlspecialchars($u['isim']) ?></td>
                      <td class="d-none d-md-table-cell">
                        <?= nl2br(htmlspecialchars(substr($u['aciklama'], 0, 50))) ?>…
                      </td>
                      <td class="d-none d-lg-table-cell">
                        <?= nl2br(htmlspecialchars(substr($u['ozellikler'], 0, 30))) ?>…
                      </td>
                      <td class="d-none d-lg-table-cell"><?= htmlspecialchars($u['kimyasalyapi']) ?></td>
                      <td class="d-none d-xl-table-cell"><?= htmlspecialchars($u['renk']) ?></td>
                      <td class="d-none d-xl-table-cell"><?= htmlspecialchars($u['uygulamasekli']) ?></td>
                      <td class="d-none d-xl-table-cell"><?= htmlspecialchars($u['kullanimalani']) ?></td>
                      <td class="d-none d-md-table-cell"><?= htmlspecialchars($u['kategori_adi']) ?></td>
                      <td class="d-none d-lg-table-cell"><?= htmlspecialchars($u['alt_kategori_adi']) ?></td>
                      <td class="d-none d-xl-table-cell"><?= htmlspecialchars($u['alt_alt_kategori_adi']) ?></td>
                      <td class="d-none d-sm-table-cell"><?= number_format($u['fiyat'], 2) ?> ₺</td>
                      <td class="d-none d-sm-table-cell"><?= htmlspecialchars($u['stok']) ?></td>
                      <td>
                        <a href="productupdate.php?id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-warning mb-1">Güncelle</a>
                        <form method="post" action="productdel.php" class="d-inline"
                              onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                          <input type="hidden" name="product_id" value="<?= $u['id'] ?>">
                          <button name="delete" class="btn btn-sm btn-danger">Sil</button>
                        </form>
                      </td>
                    </tr>
                  <?php endwhile;
                else: ?>
                  <tr>
                    <td colspan="15" class="text-center">Hiç ürün bulunamadı.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Sayfalama -->
      <nav class="mt-3">
        <ul class="pagination justify-content-center flex-wrap gap-1">
          <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link"
               href="products.php?page=<?= $page - 1 ?>&kategori=<?= $kategoriId ?>">&laquo;</a>
          </li>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link"
                 href="products.php?page=<?= $i ?>&kategori=<?= $kategoriId ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link"
               href="products.php?page=<?= $page + 1 ?>&kategori=<?= $kategoriId ?>">&raquo;</a>
          </li>
        </ul>
      </nav>

    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
