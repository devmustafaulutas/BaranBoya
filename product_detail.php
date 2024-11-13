<?php
include "z_db.php";
include "header.php";

// Ürün ID'yi al
$urun_id = isset($_GET['urun_id']) ? (int)$_GET['urun_id'] : 0;

// Ürün bilgilerini al
$product_query = "SELECT * FROM urunler WHERE id = $urun_id";
$product_result = mysqli_query($con, $product_query);
$product = mysqli_fetch_array($product_result);

// Ürün bulunamadıysa yönlendirme yap
if (!$product) {
    echo "<p>Ürün bulunamadı.</p>";
    exit;
}

// Kategori ve alt kategori bilgilerini almak için sorgular
$kategori_id = $product['kategori_id'];
$alt_kategori_id = $product['alt_kategori_id'];
$alt_kategori_alt_id = $product['alt_kategori_alt_id'];

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

?>

<!-- ***** Breadcrumb Area Start ***** -->
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                    <h2 class="text-white text-uppercase mb-3">Ürün Detay</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="urunler.php">Ürünler</a></li>
                        <?php if ($category_name) { ?>
                            <li class="breadcrumb-item text-white"><?php echo $category_name; ?></li>
                        <?php } ?>
                        <?php if ($subcategory_name) { ?>
                            <li class="breadcrumb-item text-white"><?php echo $subcategory_name; ?></li>
                        <?php } ?>
                        <li class="breadcrumb-item text-white active"><?php echo $product['isim']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Breadcrumb Area End ***** -->

<!--====== Products Area Start ======-->
<section class="section products-area ptb_25">
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
<!--====== Product Detail Area Start ======-->
<section class="section product-detail-area ptb_100">
    <div class="container">
        <div class="row">
            <div class="col-md-6 product-image">
                <!-- Ürün görseli -->
                <img src="uploads/<?php echo $product['resim']; ?>" alt="<?php echo $product['isim']; ?>" class="img-fluid">
            </div>
            <div class="col-md-6 product-info">
                <!-- Ürün başlık ve açıklaması -->
                <div class="product-detail">
                    <h2><?php echo $product['isim']; ?></h2>
                </div>
                <div class="product-description">
                    <p><strong>Açıklama: </strong><?php echo $product['aciklama']; ?></p>
                </div>
                <div class="product-price">
                    <p><strong>Fiyat: </strong><?php echo $product['fiyat']; ?> TL</p>
                </div>
                
                <!-- Kategoriler -->
                <?php if ($category_name) { ?>
                    <div class="product-category">
                        <p><strong>Kategori: </strong><?php echo $category_name; ?></p>
                    </div>
                <?php } ?>
                <?php if ($subcategory_name) { ?>
                    <div class="product-subcategory">
                        <p><strong>Alt Kategori: </strong><?php echo $subcategory_name; ?></p>
                    </div>
                <?php } ?>
                <?php if ($subSubcategory_name) { ?>
                    <div class="product-subsubcategory">
                        <p><strong>Alt Alt Kategori: </strong><?php echo $subSubcategory_name; ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!--====== Product Detail Area End ======-->

<?php include "footer.php"; ?>
