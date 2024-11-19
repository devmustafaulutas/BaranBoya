<?php
include "z_db.php";
include "header.php";

// Kategori ve alt kategori parametrelerini alın
$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;
$alt_kategori_id = isset($_GET['alt_kategori_id']) ? (int)$_GET['alt_kategori_id'] : 0;
$alt_kategori_alt_id = isset($_GET['alt_kategori_alt_id']) ? (int)$_GET['alt_kategori_alt_id'] : 0;

// Breadcrumb'da kullanılacak değişkenler
$category_name = '';
$subcategory_name = '';
$subSubcategory_name = '';

// Eğer kategori id varsa, kategori adını al
if ($kategori_id) {
    $category_query = mysqli_query($con, "SELECT * FROM kategoriler WHERE id = $kategori_id");
    $category = mysqli_fetch_array($category_query);
    // Null kontrolü ekleyin
    if ($category) {
        $category_name = $category['isim'] ?? '';
    } else {
        $category_name = 'Kategori Bulunamadı'; // Yedek bir değer verin
    }
}

if ($alt_kategori_id) {
    $subcategory_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE id = $alt_kategori_id");
    $subcategory = mysqli_fetch_array($subcategory_query);
    // Null kontrolü
    if ($subcategory) {
        $subcategory_name = $subcategory['isim'] ?? '';
    } else {
        $subcategory_name = 'Alt Kategori Bulunamadı';
    }
}

if ($alt_kategori_alt_id) {
    $subSubcategory_query = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt WHERE id = $alt_kategori_alt_id");
    $subSubcategory = mysqli_fetch_array($subSubcategory_query);
    // Null kontrolü
    if ($subSubcategory) {
        $subSubcategory_name = $subSubcategory['isim'] ?? '';
    } else {
        $subSubcategory_name = 'Alt Kategori Alt Bulunamadı';
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
                        <div class="text-alan">
                            <small>Kategori</small>
                            <?php echo $category_name; ?>
                        </div>
                        <span class="dropdown-toggle" id="dropdownToggle1"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle1">
                        <?php
                        if ($kategori_id) {
                            $subcategories_query = mysqli_query($con, "SELECT * FROM kategoriler");
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
                    <!-- SVG İkon -->
                    <!-- Kategori Başlığı -->
                    <h3>
                        <div class="icon-container">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 41.644 41.644">
                                <path id="Path_236" data-name="Path 236" d="M18.249,41.839a.693.693,0,0,0,.981,0l11.8-11.8,2.98-2.98.9.9a.693.693,0,0,0,.981,0l1.592-1.591v5.959a2.082,2.082,0,0,0,4.164,0V22.609a5.559,5.559,0,0,0-5.552-5.552H32.908L24.292,8.44V1.093a.694.694,0,1,0-1.388,0V7.052l-2.98-2.98a.694.694,0,1,0-.981.981L22.9,9.015v.813L17.148,4.072a.694.694,0,0,0-.981,0L14.085,6.155a.694.694,0,0,0,0,.981l.9.9L.2,22.812a.694.694,0,0,0,0,.981Zm6.737-7.719-7.348-7.347L22.21,22.2l7.347,7.347ZM35.4,26.486l-.9-.9L24.292,15.381v-2.2L36.5,25.385Zm.694-8.041a4.169,4.169,0,0,1,4.164,4.164v9.717a.694.694,0,0,1-1.388,0V23.3a3.475,3.475,0,0,0-3.17-3.457l-1.4-1.4ZM24.292,10.4l12,12a.694.694,0,0,0,.975,0,2.057,2.057,0,0,1,.21.893v1.1L24.292,11.216ZM16.658,5.544,22.9,11.791v2.2l-6.45-6.45-.9-.9Zm-.694,3.47L22.9,15.955v2.489a.694.694,0,1,0,1.388,0v-1.1l8.735,8.735-2.489,2.489L22.7,20.73a.694.694,0,0,0-.981,0l-5.552,5.552a.694.694,0,0,0,0,.981L24,35.1,18.74,40.367,1.675,23.3Zm0,0" transform="translate(0 -0.399)" fill="#707070"></path>
                            </svg>
                        </div>
                        <div class="text-alan">
                            <small>Alt Kategori</small>
                            <?php echo $subcategory_name; ?>
                        </div>
                        <span class="dropdown-toggle" id="dropdownToggle2"></span>
                    </h3>
                    <!-- Alt Kategoriler -->
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


            <!-- Üçüncü Kategori -->
            <div class="col-md-4">
                <div class="products-category-box">
                    <h3>
                        <div class="icon-container">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 41.644 41.644">
                                <path id="Path_236" data-name="Path 236" d="M18.249,41.839a.693.693,0,0,0,.981,0l11.8-11.8,2.98-2.98.9.9a.693.693,0,0,0,.981,0l1.592-1.591v5.959a2.082,2.082,0,0,0,4.164,0V22.609a5.559,5.559,0,0,0-5.552-5.552H32.908L24.292,8.44V1.093a.694.694,0,1,0-1.388,0V7.052l-2.98-2.98a.694.694,0,1,0-.981.981L22.9,9.015v.813L17.148,4.072a.694.694,0,0,0-.981,0L14.085,6.155a.694.694,0,0,0,0,.981l.9.9L.2,22.812a.694.694,0,0,0,0,.981Zm6.737-7.719-7.348-7.347L22.21,22.2l7.347,7.347ZM35.4,26.486l-.9-.9L24.292,15.381v-2.2L36.5,25.385Zm.694-8.041a4.169,4.169,0,0,1,4.164,4.164v9.717a.694.694,0,0,1-1.388,0V23.3a3.475,3.475,0,0,0-3.17-3.457l-1.4-1.4ZM24.292,10.4l12,12a.694.694,0,0,0,.975,0,2.057,2.057,0,0,1,.21.893v1.1L24.292,11.216ZM16.658,5.544,22.9,11.791v2.2l-6.45-6.45-.9-.9Zm-.694,3.47L22.9,15.955v2.489a.694.694,0,1,0,1.388,0v-1.1l8.735,8.735-2.489,2.489L22.7,20.73a.694.694,0,0,0-.981,0l-5.552,5.552a.694.694,0,0,0,0,.981L24,35.1,18.74,40.367,1.675,23.3Zm0,0" transform="translate(0 -0.399)" fill="#707070"></path>
                            </svg>
                        </div>
                        <div class="text-alan">
                            <small>Döküm Tipi</small>
                            <?php echo $subSubcategory_name; ?>
                        </div>
                        <span class="dropdown-toggle" id="dropdownToggle3"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle3">
                        <ul>
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
        <div class="row product-card-row">
            <?php
                // Kategoriler
                if (!$kategori_id && !$alt_kategori_id) {
                    $categories_query = mysqli_query($con, "SELECT * FROM kategoriler");
                    while ($category = mysqli_fetch_array($categories_query)) {
                        echo '<div class="col-md-4 mb-4 category-card">';
                        echo '<a href="urunler.php?kategori_id=' . $category['id'] . '">';
                        echo '<div class="product-category-box">';
                        echo '<img src="' . $category['resim'] . '" class="category-img img-fluid">'; // Görüntü için kategori resmi
                        echo '<div class="product-category-box-text">';
                        echo '<h5>' . $category['isim'] . '</h5>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                }
                
                // Alt Kategoriler
                elseif ($kategori_id && !$alt_kategori_id) {
                    $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = $kategori_id");
                    while ($subcategory = mysqli_fetch_array($subcategories_query)) {
                        echo '<div class="col-md-4 mb-4 subcategory-card">';
                        echo '<a href="urunler.php?kategori_id=' . $kategori_id . '&alt_kategori_id=' . $subcategory['id'] . '">';
                        echo '<div class="product-category-box">';
                        echo '<img src="' . $subcategory['resim'] . '" class="subcategory-img img-fluid">'; // Görüntü için alt kategori resmi
                        echo '<div class="product-subcategory-box-text">';
                        echo '<h5>' . $subcategory['isim'] . '</h5>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                }

                // Ürünler
                elseif ($alt_kategori_id && !$alt_kategori_alt_id) {
                    $products_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_id = $alt_kategori_id");
                    if (mysqli_num_rows($products_query) > 0) {
                        while ($product = mysqli_fetch_array($products_query)) {
                            echo '<div class="col-md-4 mb-4 product-card">';
                            echo '<a href="product_detail?urun_id=' . $product['id'] . '" class="product-card-link">';
                            echo '<div class="product-item">';
                            echo '<img src="' . $product['resim'] . '" class="product-img img-fluid">'; // Görüntü için ürün resmi
                            echo '<div class="product-item-text">';
                            echo '<h4>' . $product['isim'] . '</h4>';
                            echo '<p>' . $product['aciklama'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                        }
                    }
                }
            ?>
        </div>
    </div>
</section>


<!-- Ürünler Listesi Bitiş -->

<?php include "footer.php"; ?>