<?php
include "z_db.php";

// Kategori, alt kategori ve alt kategori alt parametrelerini al
$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;
$alt_kategori_id = isset($_GET['alt_kategori_id']) ? (int)$_GET['alt_kategori_id'] : 0;
$alt_kategori_alt_id = isset($_GET['alt_kategori_alt_id']) ? (int)$_GET['alt_kategori_alt_id'] : 0;

$product_query = null;

if ($alt_kategori_alt_id > 0) {
    // Alt alt kategoriye göre ürünleri getir
    $product_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_alt_id = $alt_kategori_alt_id");
} elseif ($alt_kategori_id > 0) {
    // Alt kategoriye göre ürünleri getir
    $product_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_id = $alt_kategori_id");
} elseif ($kategori_id > 0) {
    // Kategorilere bağlı alt kategorileri filtrele
    $subcategory_ids = [];
    $subcategory_query = mysqli_query($con, "SELECT id FROM alt_kategoriler WHERE kategori_id = $kategori_id");
    while ($subcategory = mysqli_fetch_array($subcategory_query)) {
        $subcategory_ids[] = $subcategory['id'];
    }
    if (!empty($subcategory_ids)) {
        $ids = implode(',', $subcategory_ids);
        $product_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_id IN ($ids)");
    } else {
        $product_query = mysqli_query($con, "SELECT * FROM urunler WHERE kategori_id = $kategori_id");
    }
} else {
    // Herhangi bir filtre yoksa tüm ürünleri listele
    $product_query = mysqli_query($con, "SELECT * FROM urunler");
}

// Ürünler varsa listele
if (mysqli_num_rows($product_query) > 0) {
    while ($product = mysqli_fetch_array($product_query)) {
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
            <div class="card">
                <img src="<?php echo $product['resim']; ?>" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['isim']; ?></h5>
                    <p class="card-text"><?php echo $product['aciklama']; ?></p>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='col-12'><p>Bu kategoriye ait ürün bulunmamaktadır.</p></div>";
}
?>
