<?php
include "header.php";
include "sidebar.php";
include "../z_db.php";

// Ürün ID'sini al
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ürün bilgilerini çek
$query = "SELECT id, isim, aciklama, fiyat, stok, resim, kategori_id, alt_kategori_id, alt_kategori_alt_id FROM urunler WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($id, $isim, $aciklama, $fiyat, $stok, $resim, $kategori_id, $alt_kategori_id, $alt_kategori_alt_id);
$stmt->fetch();
$stmt->close();

// Kategorileri çek
$kategori_query = "SELECT * FROM kategoriler ORDER BY isim ASC";
$kategori_result = mysqli_query($con, $kategori_query);

// Alt Kategorileri çek
$alt_kategori_query = "SELECT * FROM alt_kategoriler ORDER BY isim ASC";
$alt_kategori_result = mysqli_query($con, $alt_kategori_query);

// Alt-Alt Kategorileri çek
$alt_alt_kategori_query = "SELECT * FROM alt_kategoriler_alt ORDER BY isim ASC";
$alt_alt_kategori_result = mysqli_query($con, $alt_alt_kategori_query);

// Form gönderildiğinde güncelleme işlemini yap
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isim = $_POST['isim'];
    $aciklama = $_POST['aciklama'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];
    $kategori_id = $_POST['kategori_id'];
    $alt_kategori_id = $_POST['alt_kategori_id'];
    $alt_kategori_alt_id = $_POST['alt_kategori_alt_id'];

    // Resim yükleme işlemi
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
        $upload_dir = "uploads/";
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $file_tmp = $_FILES['resim']['tmp_name'];
        $file_name = basename($_FILES['resim']['name']);
        $file_size = $_FILES['resim']['size'];
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_filename = uniqid('urun_', true) . "." . $ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                // Eski resmi sil
                if (!empty($resim) && file_exists($upload_dir . $resim)) {
                    unlink($upload_dir . $resim);
                }
                $resim = $new_filename;
            }
        }
    }

    $query = "UPDATE urunler SET isim = ?, aciklama = ?, fiyat = ?, stok = ?, resim = ?, kategori_id = ?, alt_kategori_id = ?, alt_kategori_alt_id = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssdisiiii", $isim, $aciklama, $fiyat, $stok, $resim, $kategori_id, $alt_kategori_id, $alt_kategori_alt_id, $product_id);
    $stmt->execute();
    $stmt->close();

    // Güncelleme işlemi tamamlandıktan sonra products.php dosyasına geri dön
    header("Location: products.php");
    exit();
}
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Ürün Güncelle</h5>
                        </div>
                        <div class="card-body">
                            <form action="productupdate.php?id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="isim" class="form-label">Ürün İsmi</label>
                                    <input type="text" class="form-control" id="isim" name="isim" value="<?php echo htmlspecialchars($isim, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="aciklama" class="form-label">Açıklama</label>
                                    <textarea class="form-control" id="aciklama" name="aciklama" rows="3" required><?php echo htmlspecialchars($aciklama, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="fiyat" class="form-label">Fiyat</label>
                                    <input type="number" step="0.01" class="form-control" id="fiyat" name="fiyat" value="<?php echo htmlspecialchars($fiyat, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" value="<?php echo htmlspecialchars($stok, ENT_QUOTES, 'UTF-8'); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resim" class="form-label">Resim</label>
                                    <input type="file" class="form-control" id="resim" name="resim">
                                    <?php if (!empty($resim)): ?>
                                        <img src="../<?php echo htmlspecialchars($resim, ENT_QUOTES, 'UTF-8'); ?>" alt="Ürün Resmi" style="width: 100px; height: auto;">
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori_id" name="kategori_id" required>
                                        <option value="">Seçiniz</option>
                                        <?php while ($kategori = mysqli_fetch_assoc($kategori_result)): ?>
                                            <option value="<?php echo $kategori['id']; ?>" <?php echo ($kategori['id'] == $kategori_id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($kategori['isim'], ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alt_kategori_id" class="form-label">Alt Kategori</label>
                                    <select class="form-select" id="alt_kategori_id" name="alt_kategori_id" required>
                                        <option value="">Seçiniz</option>
                                        <?php while ($alt_kategori = mysqli_fetch_assoc($alt_kategori_result)): ?>
                                            <option value="<?php echo $alt_kategori['id']; ?>" <?php echo ($alt_kategori['id'] == $alt_kategori_id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($alt_kategori['isim'], ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alt_kategori_alt_id" class="form-label">Alt-Alt Kategori</label>
                                    <select class="form-select" id="alt_kategori_alt_id" name="alt_kategori_alt_id">
                                        <option value="">Seçiniz</option>
                                        <?php while ($alt_alt_kategori = mysqli_fetch_assoc($alt_alt_kategori_result)): ?>
                                            <option value="<?php echo $alt_alt_kategori['id']; ?>" <?php echo ($alt_alt_kategori['id'] == $alt_kategori_alt_id) ? 'selected' : ''; ?>><?php echo htmlspecialchars($alt_alt_kategori['isim'], ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Güncelle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>