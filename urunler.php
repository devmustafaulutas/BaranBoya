<?php
include "z_db.php";
include "header.php";

// Hata Raporlamayı Etkinleştirme
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Şifreleme ve Deşifreleme Fonksiyonları
function encrypt_id($id)
{
    $key = 'gizli-anahtar';
    $cipher = openssl_encrypt($id, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    return base64_encode($cipher);
}


function decrypt_id($encrypted_id)
{
    $key = 'gizli-anahtar';
    // rawurldecode, + işaretini bozmadan sadece %XX kodlarını çözer
    $data = rawurldecode($encrypted_id);
    $decrypted = openssl_decrypt(
        base64_decode($data),
        'AES-128-ECB',
        $key,
        OPENSSL_RAW_DATA
    );
    return intval($decrypted);
}

// Kategori ve alt kategori parametrelerini alın
$kategori_id = isset($_GET['kategori_id']) ? intval(decrypt_id($_GET['kategori_id'])) : 0;
$alt_kategori_id = isset($_GET['alt_kategori_id']) ? intval(decrypt_id($_GET['alt_kategori_id'])) : 0;
$alt_kategori_alt_id = isset($_GET['alt_kategori_alt_id']) ? intval(decrypt_id($_GET['alt_kategori_alt_id'])) : 0;

// Breadcrumb'da kullanılacak değişkenler
$category_name = '';
$subcategory_name = '';
$subSubcategory_name = '';

// Eğer kategori id varsa, kategori adını al
if ($kategori_id) {
    $stmt = $con->prepare("SELECT id, isim FROM kategoriler WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $kategori_id);
        $stmt->execute();
        $stmt->bind_result($id, $isim);
        if ($stmt->fetch()) {
            $category_name = $isim ?? '';
        } else {
            $category_name = 'Kategori Bulunamadı';
        }
        $stmt->close();
    } else {
        // Hazırlama hatasını kontrol et
        echo "Hata: " . $con->error;
    }
}

if ($alt_kategori_id) {
    $stmt = $con->prepare("SELECT id, isim FROM alt_kategoriler WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $alt_kategori_id);
        $stmt->execute();
        $stmt->bind_result($id, $isim);
        if ($stmt->fetch()) {
            $subcategory_name = $isim ?? '';
        } else {
            $subcategory_name = 'Alt Kategori Bulunamadı';
        }
        $stmt->close();
    } else {
        // Hazırlama hatasını kontrol et
        echo "Hata: " . $con->error;
    }
}

if ($alt_kategori_alt_id) {
    $stmt = $con->prepare("SELECT id, isim FROM alt_kategoriler_alt WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $alt_kategori_alt_id);
        $stmt->execute();
        // SELECT ifadesinde iki sütun seçildiğinden bind_result da iki değişken kullanılmalı
        $stmt->bind_result($id, $isim);
        if ($stmt->fetch()) {
            $subSubcategory_name = $isim ?? '';
        } else {
            $subSubcategory_name = 'Alt Alt Kategori Bulunamadı';
        }
        $stmt->close();
    } else {
        // Hazırlama hatasını kontrol et
        echo "Hata: " . $con->error;
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
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="urunler.php">Ürünler</a>
                        </li>
                        <?php if ($category_name) { ?>
                            <li class="breadcrumb-item"><a class="text-uppercase text-white"
                                    href="urunler.php?kategori_id=<?php echo encrypt_id($kategori_id); ?>"><?php echo $category_name; ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($subcategory_name) { ?>
                            <li class="breadcrumb-item"><a class="text-uppercase text-white"
                                    href="urunler.php?kategori_id=<?php echo encrypt_id($kategori_id); ?>&alt_kategori_id=<?php echo encrypt_id($alt_kategori_id); ?>"><?php echo $subcategory_name; ?></a>
                            </li>
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
<section class="section products-area ptb_50">
    <div class="container">
        <div class="category-h4-container">
            <h4 id="urunler-category-h4">Kategoriler</h4>
        </div>
        <div class="row">
            <!-- ANA KATEGORİ PANELİ -->
            <div class="col-md-3">
                <div class="products-category-box">
                    <h3>
                        <div class="icon-container">
                            <!-- svg ikonu -->
                        </div>
                        <div class="text-alan">
                            <small>Kategori</small>
                            <?= htmlspecialchars($category_name ?: 'Tüm Kategoriler') ?>
                        </div>
                        <span class="dropdown-toggle" id="dropdownToggle1"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle1">
                        <ul>
                            <?php
                            //  ➤ Burada tüm ana kategorileri çekiyoruz
                            $catsRes = mysqli_query($con, "SELECT * FROM kategoriler");
                            while ($catRow = mysqli_fetch_assoc($catsRes)):
                                ?>
                                <li>
                                    <a class="products-dropdown-item"
                                        href="urunler.php?kategori_id=<?= rawurlencode(encrypt_id($catRow['id'])) ?>">
                                        <?= htmlspecialchars($catRow['isim']) ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </div>
            </div>


            <!-- İkinci Kategori -->
            <div class="col-md-5">
                <div class="products-category-box">
                    <h3>
                        <div class="icon-container">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 41.644 41.644">
                                <path id="Path_236" data-name="Path 236"
                                    d="M18.249,41.839a.693.693,0,0,0,.981,0l11.8-11.8,2.98-2.98.9.9a.693.693,0,0,0,.981,0l1.592-1.591v5.959a2.082,2.082,0,0,0,4.164,0V22.609a5.559,5.559,0,0,0-5.552-5.552H32.908L24.292,8.44V1.093a.694.694,0,1,0-1.388,0V7.052l-2.98-2.98a.694.694,0,1,0-.981.981L22.9,9.015v.813L17.148,4.072a.694.694,0,0,0-.981,0L14.085,6.155a.694.694,0,0,0,0,.981l.9.9L.2,22.812a.694.694,0,0,0,0,.981Zm6.737-7.719-7.348-7.347L22.21,22.2l7.347,7.347ZM35.4,26.486l-.9-.9L24.292,15.381v-2.2L36.5,25.385Zm.694-8.041a4.169,4.169,0,0,1,4.164,4.164v9.717a.694.694,0,0,1-1.388,0V23.3a3.475,3.475,0,0,0-3.17-3.457l-1.4-1.4ZM24.292,10.4l12,12a.694.694,0,0,0,.975,0,2.057,2.057,0,0,1,.21.893v1.1L24.292,11.216ZM16.658,5.544,22.9,11.791v2.2l-6.45-6.45-.9-.9Zm-.694,3.47L22.9,15.955v2.489a.694.694,0,1,0,1.388,0v-1.1l8.735,8.735-2.489,2.489L22.7,20.73a.694.694,0,0,0-.981,0l-5.552,5.552a.694.694,0,0,0,0,.981L24,35.1,18.74,40.367,1.675,23.3Zm0,0"
                                    transform="translate(0 -0.399)" fill="#707070"></path>
                            </svg>
                        </div>
                        <div class="text-alan">
                            <small>Alt Kategori</small>
                            <?php echo htmlspecialchars($subcategory_name); ?>
                        </div>
                        <span class="dropdown-toggle" id="dropdownToggle2"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle2">
                        <?php
                        if ($kategori_id) {
                            $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = " . intval($kategori_id));
                            if (mysqli_num_rows($subcategories_query) > 0) {
                                echo '<ul>';
                                while ($subcategory = mysqli_fetch_array($subcategories_query)) {
                                    echo '<li><a class="products-dropdown-item" href="urunler.php?kategori_id=' . rawurlencode(encrypt_id($kategori_id)) . '&alt_kategori_id=' . rawurlencode(encrypt_id($subcategory['id'])) . '">' . htmlspecialchars($subcategory['isim']) . '</a></li>';
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
                                <path id="Path_236" data-name="Path 236"
                                    d="M18.249,41.839a.693.693,0,0,0,.981,0l11.8-11.8,2.98-2.98.9.9a.693.693,0,0,0,.981,0l1.592-1.591v5.959a2.082,2.082,0,0,0,4.164,0V22.609a5.559,5.559,0,0,0-5.552-5.552H32.908L24.292,8.44V1.093a.694.694,0,1,0-1.388,0V7.052l-2.98-2.98a.694.694,0,1,0-.981.981L22.9,9.015v.813L17.148,4.072a.694.694,0,0,0-.981,0L14.085,6.155a.694.694,0,0,0,0,.981l.9.9L.2,22.812a.694.694,0,0,0,0,.981Zm6.737-7.719-7.348-7.347L22.21,22.2l7.347,7.347ZM35.4,26.486l-.9-.9L24.292,15.381v-2.2L36.5,25.385Zm.694-8.041a4.169,4.169,0,0,1,4.164,4.164v9.717a.694.694,0,0,1-1.388,0V23.3a3.475,3.475,0,0,0-3.17-3.457l-1.4-1.4ZM24.292,10.4l12,12a.694.694,0,0,0,.975,0,2.057,2.057,0,0,1,.21.893v1.1L24.292,11.216ZM16.658,5.544,22.9,11.791v2.2l-6.45-6.45-.9-.9Zm-.694,3.47L22.9,15.955v2.489a.694.694,0,1,0,1.388,0v-1.1l8.735,8.735-2.489,2.489L22.7,20.73a.694.694,0,0,0-.981,0l-5.552,5.552a.694.694,0,0,0,0,.981L24,35.1,18.74,40.367,1.675,23.3Zm0,0"
                                    transform="translate(0 -0.399)" fill="#707070"></path>
                            </svg>
                        </div>
                        <div class="text-alan">
                            <small>Döküm Tipi</small>
                            <?php echo htmlspecialchars($subSubcategory_name); ?>
                        </div>
                        <span class="dropdown-toggle" id="dropdownToggle3"></span>
                    </h3>
                    <div class="products-dropdown-menu" aria-labelledby="dropdownToggle3">
                        <?php
                        if ($kategori_id && $alt_kategori_id) {
                            $subSubcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt WHERE alt_kategori_id = " . intval($alt_kategori_id));
                            if (mysqli_num_rows($subSubcategories_query) > 0) {
                                echo '<ul>';
                                while ($subSubcategory = mysqli_fetch_array($subSubcategories_query)) {
                                    echo '<li><a class="products-dropdown-item" href="urunler.php?kategori_id=' . rawurlencode(encrypt_id($kategori_id)) . '&alt_kategori_id=' . rawurlencode(encrypt_id($alt_kategori_id)) . '&alt_kategori_alt_id=' . rawurlencode(encrypt_id($subSubcategory['id'])) . '">' . htmlspecialchars($subSubcategory['isim']) . '</a></li>';
                                }
                                echo '</ul>';
                            }
                        }
                        ?>
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
            // Ana kategori ve alt kategori ID'si olmayan durum
            if (!$kategori_id && !$alt_kategori_id) {
                $categories_query = mysqli_query($con, "SELECT * FROM kategoriler");
                while ($category = mysqli_fetch_array($categories_query)) {
                    echo '<div class="col-md-4 mb-4 category-card">';
                    echo '<a href="urunler.php?kategori_id=' . rawurlencode(encrypt_id($category['id'])) . '">';
                    echo '<div class="product-a" class="product-category-box">';
                    echo '<img src="' . $category['resim'] . '" class="category-img img-fluid">';
                    echo '<div class="product-category-box-text">';
                    echo '<h5>' . $category['isim'] . '</h5>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            }

            // Kategori seçildi ve alt kategori yok
            elseif ($kategori_id && !$alt_kategori_id) {
                $subcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler WHERE kategori_id = $kategori_id");
                while ($subcategory = mysqli_fetch_array($subcategories_query)) {
                    echo '<div class="col-md-4 mb-4 subcategory-card">';
                    echo '<a class="product-a" href="urunler.php?kategori_id=' . rawurlencode(encrypt_id($kategori_id)) . '&alt_kategori_id=' . rawurlencode(encrypt_id($subcategory['id'])) . '">';
                    echo '<div class="product-category-box">';
                    echo '<img src="' . $subcategory['resim'] . '" class="category-img img-fluid">';
                    echo '<div class="product-subcategory-box-text">';
                    echo '<h5>' . $subcategory['isim'] . '</h5>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
            }

            // Alt kategori seçildi ve alt kategori altı var
            elseif ($alt_kategori_id && !$alt_kategori_alt_id) {
                // Alt kategori altı var mı kontrol et
                $subSubcategories_query = mysqli_query($con, "SELECT * FROM alt_kategoriler_alt WHERE alt_kategori_id = $alt_kategori_id");

                // Eğer alt kategori altı varsa
                if (mysqli_num_rows($subSubcategories_query) > 0) {
                    while ($subSubcategory = mysqli_fetch_array($subSubcategories_query)) {
                        echo '<div class="col-md-4 mb-4 subcategory-card">';
                        echo '<a class="product-a" href="urunler.php?kategori_id=' . rawurlencode(encrypt_id($kategori_id)) . '&alt_kategori_id=' . rawurlencode(encrypt_id($alt_kategori_id)) . '&alt_kategori_alt_id=' . rawurlencode(encrypt_id($subSubcategory['id'])) . '">';
                        echo '<div class="product-category-box">';
                        echo '<img src="' . $subSubcategory['resim'] . '" class="category-img img-fluid">';
                        echo '<div class="product-subcategory-box-text">';
                        echo '<h5>' . $subSubcategory['isim'] . '</h5>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                    }
                }
                // Eğer alt kategori altı yoksa, direkt ürünleri listele
                else {
                    $products_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_id = $alt_kategori_id");
                    if (mysqli_num_rows($products_query) > 0) {
                        while ($product = mysqli_fetch_array($products_query)) {
                            echo '<div class="col-md-4 mb-4 product-card">';
                            echo '<a class="product-a" href="product_detail?urun_id=' . rawurlencode(encrypt_id($product['id'])) . '" class="product-card-link">';
                            echo '<div class="product-item">';
                            echo '<div class="product-item-text">';
                            echo '<h5>' . $product['isim'] . '</h5>';
                            echo '</div>';
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Bu alt kategoride ürün bulunmamaktadır.</p>';
                    }
                }
            }

            // Eğer alt kategori alt ID'si de varsa, ürünleri listele
            elseif ($alt_kategori_alt_id) {
                $products_query = mysqli_query($con, "SELECT * FROM urunler WHERE alt_kategori_alt_id = $alt_kategori_alt_id");
                if (mysqli_num_rows($products_query) > 0) {
                    while ($product = mysqli_fetch_array($products_query)) {
                        echo '<div class="col-md-4 mb-4 product-card">';
                        echo '<a class="product-a" href="product_detail?urun_id=' . rawurlencode(encrypt_id($product['id'])) . '" class="product-card-link">';
                        echo '<div class="product-item">';
                        echo '<div class="product-item-text">';
                        echo '<h5>' . $product['isim'] . '</h5>';
                        echo '</div>';
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