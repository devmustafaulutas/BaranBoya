<?php
include "z_db.php";
include "header.php";

// Kategori ve alt kategori parametrelerini alın
$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;
$alt_kategori_id = isset($_GET['alt_kategori_id']) ? (int)$_GET['alt_kategori_id'] : 0;

// Breadcrumb'da kullanılacak değişkenler
$category_name = '';
$subcategory_name = '';

// Eğer kategori id varsa, kategori adını al
if ($kategori_id) {
    $category_query = mysqli_query($con, "SELECT * FROM kategoriler WHERE id = $kategori_id");
    $category = mysqli_fetch_array($category_query);
    $category_name = $category['isim'] ?? '';
}

// Eğer alt kategori id varsa, alt kategori adını al
if ($alt_kategori_id) {
    $subcategory_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE id = $alt_kategori_id");
    $subcategory = mysqli_fetch_array($subcategory_query);
    $subcategory_name = $subcategory['isim'] ?? '';
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
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Breadcrumb Area End ***** -->

<!--====== Products Area Start ======-->
<section class="section products-area ptb_50">
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
        <div class="row">
            <?php
            // Eğer kategori ve alt kategori yoksa ana kategorileri göster
            if (!$kategori_id && !$alt_kategori_id) {
                $categories_query = mysqli_query($con, "SELECT * FROM kategoriler");
                while ($category = mysqli_fetch_array($categories_query)) {
                    echo '<div class="col-md-4 mb-4 category-card">';  
                    echo '<a href="urunler.php?kategori_id=' . $category['id'] . '">';
                    echo '<div class="product-category-box">';
                    echo '<h5>' . $category['isim'] . '</h5>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            }
            // Eğer kategori varsa ve alt kategori yoksa alt kategorileri göster
            elseif ($kategori_id && !$alt_kategori_id) {
                $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = $kategori_id");
                while ($subcategory = mysqli_fetch_array($subcategories_query)) {
                    echo '<div class="col-md-4 mb-4 subcategory-card">'; 
                    echo '<a href="urunler.php?kategori_id=' . $kategori_id . '&alt_kategori_id=' . $subcategory['id'] . '">';
                    echo '<div class="product-category-box">';
                    echo '<h5>' . $subcategory['isim'] . '</h5>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            }
            // Eğer alt kategori varsa, bu alt kategoriye ait ürünleri göster
            elseif ($alt_kategori_id) {
                $products_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_id = $alt_kategori_id");
                if (mysqli_num_rows($products_query) > 0) {
                    while ($product = mysqli_fetch_array($products_query)) {
                        echo '<div class="col-md-4 mb-4 product-card">';
                        echo '<a href="product_detail?urun_id=' . $product['id'] . '" class="product-card-link">';
                        echo '<div class="product-item">';
                        echo '<img src="' . $product['resim'] .'" class="img-fluid">';
                        echo '<h4>' . $product['isim'] . '</h4>';
                        echo '<p>' . $product['aciklama'] . '</p>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Bu alt kategoride ürün bulunmamaktadır.</p>';
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- Ürünler Listesi Bitiş -->

<?php include "footer.php"; ?>