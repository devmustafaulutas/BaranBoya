<?php include "../z_db.php"; ?>

<?php
// Kategorileri çek
$kategori_query = "SELECT * FROM kategoriler ORDER BY isim ASC";
$kategori_result = mysqli_query($con, $kategori_query);

// Form gönderildiğinde ekleme işlemini yap
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isim = $_POST['isim'];
    $aciklama = $_POST['aciklama'];
    $fiyat = $_POST['fiyat'];
    $stok = $_POST['stok'];
    $kategori_id = $_POST['kategori_id'];
    $alt_kategori_id = $_POST['alt_kategori_id'];
    $alt_kategori_alt_id = isset($_POST['alt_kategori_alt_id']) ? $_POST['alt_kategori_alt_id'] : null;
    // Resim yükleme işlemi
    $resim = '';
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] == 0) {
        $upload_dir = "../assets/img/products/";
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
                $resim = "assets/img/products/" . $new_filename;
            } else {
                $error_message = "Resim yüklenirken bir hata oluştu.";
            }
        } else {
            $error_message = "Geçersiz dosya türü veya dosya boyutu çok büyük.";
        }
    } else {
        $error_message = "Resim yüklenirken bir hata oluştu.";
    }

    if (!isset($error_message)) {
        $query = "INSERT INTO urunler (isim, aciklama, fiyat, stok, resim, kategori_id, alt_kategori_id, alt_kategori_alt_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssdisiii", $isim, $aciklama, $fiyat, $stok, $resim, $kategori_id, $alt_kategori_id, $alt_kategori_alt_id);
        $stmt->execute();
        $stmt->close();

        // Ekleme işlemi tamamlandıktan sonra products.php dosyasına yönlendir
        header("Location: products.php");
        exit();
    }
}
?>
<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Ürün Ekle</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                            <form action="productadd.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="isim" class="form-label">Ürün İsmi</label>
                                    <input type="text" class="form-control" id="isim" name="isim" required>
                                </div>
                                <div class="mb-3">
                                    <label for="aciklama" class="form-label">Açıklama</label>
                                    <textarea class="form-control" id="aciklama" name="aciklama" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="fiyat" class="form-label">Fiyat</label>
                                    <input type="number" step="0.01" class="form-control" id="fiyat" name="fiyat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" required>
                                </div>
                                <div class="mb-3">
                                    <label for="resim" class="form-label">Resim</label>
                                    <input type="file" class="form-control" id="resim" name="resim" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori</label>
                                    <select class="form-select" id="kategori_id" name="kategori_id" required>
                                        <option value="">Seçiniz</option>
                                        <?php while ($kategori = mysqli_fetch_assoc($kategori_result)): ?>
                                            <option value="<?php echo $kategori['id']; ?>"><?php echo htmlspecialchars($kategori['isim'], ENT_QUOTES, 'UTF-8'); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alt_kategori_id" class="form-label">Alt Kategori</label>
                                    <select class="form-select" id="alt_kategori_id" name="alt_kategori_id" required>
                                        <option value="">Seçiniz</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alt_kategori_alt_id" class="form-label">Alt-Alt Kategori</label>
                                    <select class="form-select" id="alt_kategori_alt_id" name="alt_kategori_alt_id">
                                        <option value="">Seçiniz</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

<script>
document.getElementById('kategori_id').addEventListener('change', function() {
    var kategori_id = this.value;
    var alt_kategori_select = document.getElementById('alt_kategori_id');
    var alt_alt_kategori_select = document.getElementById('alt_kategori_alt_id');

    // Alt kategorileri temizle
    alt_kategori_select.innerHTML = '<option value="">Seçiniz</option>';
    alt_alt_kategori_select.innerHTML = '<option value="">Seçiniz</option>';

    if (kategori_id) {
        // Alt kategorileri yükle
        fetch('get_alt_kategoriler.php?kategori_id=' + kategori_id)
            .then(response => response.json())
            .then(data => {
                data.forEach(function(alt_kategori) {
                    var option = document.createElement('option');
                    option.value = alt_kategori.id;
                    option.text = alt_kategori.isim;
                    alt_kategori_select.appendChild(option);
                });
            });
    }
});

document.getElementById('alt_kategori_id').addEventListener('change', function() {
    var alt_kategori_id = this.value;
    var alt_alt_kategori_select = document.getElementById('alt_kategori_alt_id');

    // Alt-alt kategorileri temizle
    alt_alt_kategori_select.innerHTML = '<option value="">Seçiniz</option>';

    if (alt_kategori_id) {
        // Alt-alt kategorileri yükle
        fetch('get_alt_alt_kategoriler.php?alt_kategori_id=' + alt_kategori_id)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(function(alt_alt_kategori) {
                        var option = document.createElement('option');
                        option.value = alt_alt_kategori.id;
                        option.text = alt_alt_kategori.isim;
                        alt_alt_kategori_select.appendChild(option);
                    });
                } else {
                    // Alt-alt kategori yoksa "Seçiniz" seçeneğini kaldır
                    alt_alt_kategori_select.innerHTML = '';
                }
            });
    }
});
</script>
