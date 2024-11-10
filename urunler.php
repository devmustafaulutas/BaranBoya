<?php
include "z_db.php";
include "header.php";

// Kategori ve alt kategori parametrelerini alın
$kategori_id = isset($_GET['kategori_id']) && $_GET['kategori_id'] != 'undefined' ? (int)$_GET['kategori_id'] : 0;
$alt_kategori_id = isset($_GET['alt_kategori_id']) ? (int)$_GET['alt_kategori_id'] : 0;
$alt_kategori_alt_id = isset($_GET['alt_kategori_alt_id']) ? (int)$_GET['alt_kategori_alt_id'] : 0;

// Kategori ve alt kategori adlarını almak için
$category_name = '';
$subcategory_name = '';
$subSubcategory_name = '';

if ($kategori_id > 0) {
    $category_query = mysqli_query($con, "SELECT * FROM kategoriler WHERE id = $kategori_id");
    if ($category = mysqli_fetch_array($category_query)) {
        $category_name = $category['isim'];
    }
}

if ($alt_kategori_id > 0) {
    $subcategory_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE id = $alt_kategori_id");
    if ($subcategory = mysqli_fetch_array($subcategory_query)) {
        $subcategory_name = $subcategory['isim'];
    }
}

if ($alt_kategori_alt_id > 0) {
    $subSubcategory_query = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt WHERE id = $alt_kategori_alt_id");
    if ($subSubcategory = mysqli_fetch_array($subSubcategory_query)) {
        $subSubcategory_name = $subSubcategory['isim'];
    }
}
?>

<!-- ***** Breadcrumb Area Start ***** -->
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                    <h2 class="text-white text-uppercase mb-3">Ürünler</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item text-white active">Ürünler</li>
                        <?php if ($category_name) { ?>
                            <li class="breadcrumb-item text-white"><?php echo $category_name; ?></li>
                        <?php } ?>
                        <?php if ($subcategory_name) { ?>
                            <li class="breadcrumb-item text-white"><?php echo $subcategory_name; ?></li>
                        <?php } ?>
                        <?php if ($subSubcategory_name) { ?>
                            <li class="breadcrumb-item text-white"><?php echo $subSubcategory_name; ?></li>
                        <?php } ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Breadcrumb Area End ***** -->

<!--====== Products Area Start ======-->
<section class="section products-area ptb_100">
    <div class="container">
        <div class="category-h4-container">
            <h4 id="urunler-category-h4">Kategoriler</h4>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="category-list">
                    <ul class="list-group">
                        <?php
                        // Ana kategorileri listele
                        $categories_query = mysqli_query($con, "SELECT * FROM kategoriler");
                        while ($category = mysqli_fetch_array($categories_query)) {
                            ?>
                            <li class="list-group-item">
                                <a href="urunler.php?kategori_id=<?php echo $category['id']; ?>" class="category-toggle"><?php echo $category['isim']; ?></a>
                                <?php if ($kategori_id == $category['id']) { ?>
                                    <!-- Alt kategorileri göster -->
                                    <ul class="list-group subcategory-list" style="display: block;">
                                        <?php
                                        $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = " . $category['id']);
                                        while ($subcategory = mysqli_fetch_array($subcategories_query)) {
                                            ?>
                                            <li class="list-group-item">
                                                <a href="urunler.php?kategori_id=<?php echo $category['id']; ?>&alt_kategori_id=<?php echo $subcategory['id']; ?>" class="subcategory-toggle">
                                                    <?php echo $subcategory['isim']; ?>
                                                </a>
                                                <?php if ($alt_kategori_id == $subcategory['id']) { ?>
                                                    <!-- Alt alt kategorileri göster -->
                                                    <ul class="list-group subcategory-alt-list" style="display: block;">
                                                        <?php
                                                        $subSubcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt WHERE alt_kategori_id = " . $subcategory['id']);
                                                        while ($subSubcategory = mysqli_fetch_array($subSubcategories_query)) {
                                                            ?>
                                                            <li class="list-group-item">
                                                                <a href="urunler.php?kategori_id=<?php echo $category['id']; ?>&alt_kategori_id=<?php echo $subcategory['id']; ?>&alt_kategori_alt_id=<?php echo $subSubcategory['id']; ?>">
                                                                    <?php echo $subSubcategory['isim']; ?>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
 
                        <!-- Ürünler Listesi -->
            <div id="urunler-category-productlist" class="col-lg-9">
                <div class="row">
                    <?php
                    // Ürünleri filtrele
                    if ($alt_kategori_id > 0) {
                        $product_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_id = $alt_kategori_id");
                    } elseif ($kategori_id > 0) {
                        $subcategory_ids = [];
                        $subcategory_query = mysqli_query($con, "SELECT id FROM alt_kategoriler WHERE kategori_id = $kategori_id");
                        while ($subcategory = mysqli_fetch_array($subcategory_query)) {
                            $subcategory_ids[] = $subcategory['id'];
                        }
                        $ids = implode(',', $subcategory_ids);
                        $product_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_id IN ($ids)");
                    } else {
                        $product_query = mysqli_query($con, "SELECT * FROM urunler");
                    }

                    // Ürün varsa listele, yoksa mesaj göster
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
                        // Ürün yoksa uyarı mesajı göster
                        echo "<div class='col-12'><p>Bu kategoriye ait ürün bulunmamaktadır.</p></div>";
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</section>
<!--====== Products Area End ======-->

<?php include "footer.php"; ?>
