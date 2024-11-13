<?php
include "z_db.php";
include "header.php";

// Kategori ve alt kategori parametrelerini alın
$kategori_id = isset($_GET['kategori_id']) && $_GET['kategori_id'] != 'undefined' ? (int)$_GET['kategori_id'] : 0;
$alt_kategori_id = isset($_GET['alt_kategori_id']) ? (int)$_GET['alt_kategori_id'] : 0;
$alt_kategori_alt_id = isset($_GET['alt_kategori_alt_id']) ? (int)$_GET['alt_kategori_alt_id'] : 0;

// Kategoriyi al
$category_name = '';
$subcategory_name = '';
$subSubcategory_name = '';

$category_query = mysqli_query($con, "SELECT * FROM kategoriler WHERE id = $kategori_id");
$category = mysqli_fetch_array($category_query);
if ($category) {
    $category_name = $category['isim'];
}

// Alt kategoriyi al
$subcategory_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE id = $alt_kategori_id");
$subcategory = mysqli_fetch_array($subcategory_query);
if ($subcategory) {
    $subcategory_name = $subcategory['isim'];
}

// Alt alt kategoriyi al
$subSubcategory_query = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt WHERE id = $alt_kategori_alt_id");
$subSubcategory = mysqli_fetch_array($subSubcategory_query);
if ($subSubcategory) {
    $subSubcategory_name = $subSubcategory['isim'];
}

// Kategorileri listele
$categories_query = mysqli_query($con, "SELECT * FROM kategoriler");

// Ürünleri çekmek için sorgu
$product_query = "SELECT * FROM urunler WHERE 1=1";

if ($kategori_id) {
    $product_query .= " AND kategori_id = $kategori_id";
}
if ($alt_kategori_id) {
    $product_query .= " AND alt_kategori_id = $alt_kategori_id";
}
if ($alt_kategori_alt_id) {
    $product_query .= " AND alt_kategori_alt_id = $alt_kategori_alt_id";
}

// Sorguyu çalıştır ve sonucu al
$product_result = mysqli_query($con, $product_query);


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
            <!-- Ana Kategori -->
            <div class="col-md-3">
                <div class="products-category-box">
                    <h3>
                        <?php echo $category_name; ?>
                        <span class="dropdown-toggle" id="dropdownToggle1"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle1">
                        <?php
                        if ($kategori_id) {
                            $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = $kategori_id");
                            if (mysqli_num_rows($subcategories_query) > 0) {
                                echo '<ul>';
                                while ($subcategory = mysqli_fetch_array($subcategories_query)) {
                                    echo '<li><a class="dropdown-item" href="urunler.php?kategori_id='.$kategori_id.'&alt_kategori_id='.$subcategory['id'].'">'.$subcategory['isim'].'</a></li>';
                                }
                                echo '</ul>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- İkinci Kategori -->
            <div class="col-md-5">
                <div class="products-category-box">
                    <h3>
                        <?php echo $subcategory_name; ?>
                        <span class="dropdown-toggle" id="dropdownToggle2"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle2">
                        <?php
                        if ($kategori_id && $alt_kategori_id) {
                            $subSubcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt WHERE alt_kategori_id = $alt_kategori_id");
                            if (mysqli_num_rows($subSubcategories_query) > 0) {
                                echo '<ul>';
                                while ($subSubcategory = mysqli_fetch_array($subSubcategories_query)) {
                                    echo '<li><a class="products-dropdown-item" href="urunler.php?kategori_id='.$kategori_id.'&alt_kategori_id='.$alt_kategori_id.'&alt_kategori_alt_id='.$subSubcategory['id'].'">'.$subSubcategory['isim'].'</a></li>';
                                }
                                echo '</ul>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Üçüncü Kategori -->
            <div class="col-md-4">
                <div class="products-category-box">
                    <h3>
                        Döküm Tipi
                        <span class="dropdown-toggle" id="dropdownToggle3"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle3">
                        <ul>
                            <!-- Döküm tipi alt kategoriler burada olacak -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Ürünler Listesi Başlangıç -->
<section class="product-list-area">
    <div class="container">
        <div class="row product-category-products">
            <?php
            if ($product_result && mysqli_num_rows($product_result) > 0) {
                // Ürünleri listele
                while ($product = mysqli_fetch_array($product_result)) {
                    echo '<div class="col-md-4 product-card">';
                    echo '<a href="urunler.php?urun_id=' . $product['id'] . '" class="product-card-link">';
                    echo '<div class="product-item">';
                    echo '<img src="' . $product['resim'] . '" alt="' . $product['isim'] . '" class="img-fluid">';
                    echo '<h4>' . $product['isim'] . '</h4>';
                    echo '<p>' . $product['aciklama'] . '</p>';
                    echo '<a href="urun-detay.php?urun_id=' . $product['id'] . '" >Detay</a>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">Bu kategoride ürün bulunmamaktadır.</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Ürünler Listesi Bitiş -->

<?php include "footer.php"; ?>