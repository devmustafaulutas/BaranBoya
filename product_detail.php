<?php
include "z_db.php";
include "header.php";

// Şifreleme ve Deşifreleme Fonksiyonları
function encrypt_id($id) {
    $key = 'gizli-anahtar'; // Anahtarınızı güvenli bir yerde saklayın
    return urlencode(base64_encode(openssl_encrypt($id, 'AES-128-ECB', $key, OPENSSL_RAW_DATA)));
}

function decrypt_id($encrypted_id) {
    $key = 'gizli-anahtar'; // Anahtarınızı güvenli bir yerde saklayın
    $decrypted = openssl_decrypt(base64_decode($encrypted_id), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    return intval($decrypted);
}

// Ürün ID'yi al
$urun_id = isset($_GET['urun_id']) ? decrypt_id($_GET['urun_id']) : 0;

// Ürün bilgilerini al
$stmt = $con->prepare("SELECT * FROM urunler WHERE id = ?");
$stmt->bind_param("i", $urun_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

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
                                    echo '<li><a class="dropdown-item" href="urunler.php?kategori_id='.encrypt_id($kategori_id).'&alt_kategori_id='.encrypt_id($subcategory['id']).'">'.$subcategory['isim'].'</a></li>';
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
                                    echo '<li><a class="dropdown-item" href="urunler.php?kategori_id='.encrypt_id($kategori_id).'&alt_kategori_id='.encrypt_id($subcategory['id']).'">'.$subcategory['isim'].'</a></li>';
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
                                    echo '<li><a class="products-dropdown-item" href="urunler.php?kategori_id='.encrypt_id($kategori_id).'&alt_kategori_id='.encrypt_id($alt_kategori_id).'&alt_kategori_alt_id='.encrypt_id($subSubcategory['id']).'">'.$subSubcategory['isim'].'</a></li>';
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
<!--====== Product Detail Area Start ======-->
<section class="section product-detail-area ptb_25">
    <div class="container">
        <div class="row">
            <div class="col-md-6 product-image">
                <!-- Ürün görseli -->
                <img src="<?php echo $product['resim']; ?>"  id="product-detail-img" class="img-fluid">
            </div>
            <div class="col-md-6 product-info">
                <!-- Ürün başlık ve açıklaması -->
                <div class="product-info-item">
                    <h2><?php echo $product['isim']; ?></h2>
                </div>
                <div class="product-info-item">
                    <p><strong>Öne Çıkan Özellikler</strong></p>
                    <p>.</p>
                </div>
                <div class="product-info-item">
                   <p><strong>Kimyasal Yapı </strong></p>
                   <p><?php echo $product['fiyat']; ?> TL</p>
                </div>
                <div class="product-info-item">
                    <p><strong>Renk</strong></p>
                    <p>.</p>
                </div>
                <div class="product-info-item">
                    <p><strong>Uygulama Şekli </strong></p>
                    <p><?php echo $product['fiyat']; ?> TL</p>
                </div>
                <div class="product-info-item">
                    <p><strong>Kullanım Alanı</strong></p>
                    <p>.</p>
                </div>
                <div class="product-info-item">
                    <p><strong>Ambalaj</strong></p>
                    <div class="ambalaj-detail">
                        <div class="ambalaj">
                            <svg id="paint-bucket-with-paint-mess" xmlns="http://www.w3.org/2000/svg" width="29.994" height="25.299" viewBox="0 0 29.994 25.299"><path id="Path_233" data-name="Path 233" d="M7.451,11.911V7.7h-.1a.715.715,0,1,1,0-1.431h.1v-.01H29.135v.01h.143a.715.715,0,0,1,0,1.431h-.143V31.56H7.451V19.443a16.033,16.033,0,0,1-4.726.932c-1.273,0-2.213-.33-2.579-1.1C-.806,17.273,3.022,14.122,7.451,11.911ZM8.535,30.475H28.051V11.6a5.481,5.481,0,0,1-1.13.422V19.5a.587.587,0,1,1-1.174,0V12.015c-.114-.378.022-1.281-1.13-.6-2.008,1.18-1.333-1.513-1.536-1.056v6.257a.587.587,0,1,1-1.174,0V9.85c.18-.426-.539-.624-1.245-.406a2.722,2.722,0,0,0-1.177.8l0,0a1.095,1.095,0,0,1-.508,1.316,6.077,6.077,0,0,1-1.889,2.573A25.581,25.581,0,0,1,11.041,18c-.827.393-1.67.748-2.507,1.061V30.475Zm0-12.574c.653-.256,1.334-.546,2.04-.883a24.493,24.493,0,0,0,5.779-3.69,6.061,6.061,0,0,0,1.47-1.791,1.12,1.12,0,0,1,.582-2.071L16.937,7.345h-8.4Zm-7.409.909c.336.7,2.805.7,6.325-.513V13.127C3,15.44.728,17.976,1.126,18.811Z" transform="translate(0 -6.261)" fill="#707070"></path></svg><span class="icon-span">18 KG</span>
                        </div>
                        <div class="ambalaj">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="32" viewBox="0 0 23 32"><g id="oil-barrel" transform="translate(0)"><path id="Path_234" data-name="Path 234" d="M20.5,32c4.27,0,11.5-.58,11.5-2.75v-.1a.9.9,0,0,0-.44-.775A2.71,2.71,0,0,0,31,28V25.5a.5.5,0,0,0-1,0V28a.937.937,0,0,1,.03.355.5.5,0,0,0,.29.5l.15.065c.175.08.38.17.5.235l.035.04c-.335.625-4.03,1.7-10.5,1.7S10.36,29.82,10.03,29.2l.04-.05a4.058,4.058,0,0,1,.5-.23l.15-.065A.758.758,0,0,0,11,28.16.5.5,0,0,0,11,28V17.915A35.348,35.348,0,0,0,20.5,19c.85,0,1.7,0,2.5-.06a.5.5,0,1,0-.045-1c-.81.04-1.64.06-2.5.06-4.97,0-8.3-.64-9.725-1.22a.5.5,0,0,0-.17-.075,1.22,1.22,0,0,1-.56-.42A4.4,4.4,0,0,1,10.555,16l.14-.06h0a.47.47,0,0,0,.055-.04.5.5,0,0,0,.1-.07q.055-.078.105-.16a.5.5,0,0,0,0-.12A.47.47,0,0,0,11,15.5h0V4.415A35.348,35.348,0,0,0,20.5,5.5,35.348,35.348,0,0,0,30,4.415V15.5h0a.47.47,0,0,0,0,.065.5.5,0,0,0,0,.12q.05.082.1.16a.5.5,0,0,0,.1.07.471.471,0,0,0,.055.04h0l.14.06a4.6,4.6,0,0,1,.54.26,1.635,1.635,0,0,1-.67.44h0a16.492,16.492,0,0,1-4.845,1,.5.5,0,0,0,.05,1h.05A22.28,22.28,0,0,0,30,17.91V23.5a.5.5,0,0,0,1,0v-6c.615-.335,1-.74,1-1.24,0-.58-.45-.83-1-1.075V4c.625-.34,1-.75,1-1.245C32,.58,24.77,0,20.5,0c-1.21,0-2.4.04-3.535.115a.5.5,0,1,0,.07,1C18.145,1.04,19.315,1,20.5,1,27.135,1,30.865,2.14,31,2.745c-.03.135-.245.295-.62.465a.5.5,0,0,0-.16.07C28.8,3.86,25.465,4.5,20.5,4.5s-8.28-.635-9.71-1.21A.5.5,0,0,0,10.6,3.2c-.36-.165-.57-.32-.6-.455s.925-.91,4.565-1.395a.5.5,0,0,0-.13-1C10.83.85,9,1.65,9,2.75c0,.5.375.9,1,1.245v11.18c-.55.245-1,.5-1,1.075,0,.5.375.9,1,1.245V28a2.7,2.7,0,0,0-.56.385.9.9,0,0,0-.44.77v.1C9,31.42,16.23,32,20.5,32Z" transform="translate(-9 0)" fill="#707070"></path></g></svg><span class="icon-span">230 KG</span>
                        </div>
                        <div class="ambalaj">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36"><g id="Group_3821" data-name="Group 3821" transform="translate(0)"><g id="Rectangle_523" data-name="Rectangle 523" transform="translate(0 36) rotate(-90)" fill="#fff" stroke="#707070" stroke-width="1"><path d="M8,0H27a8,8,0,0,1,8,8V8a0,0,0,0,1,0,0H0A0,0,0,0,1,0,8V8A8,8,0,0,1,8,0Z" stroke="none"></path><path d="M7.111.5H27.889A6.611,6.611,0,0,1,34.5,7.111v0a.389.389,0,0,1-.389.389H.889A.389.389,0,0,1,.5,7.111v0A6.611,6.611,0,0,1,7.111.5Z" fill="none"></path></g><g id="Rectangle_524" data-name="Rectangle 524" transform="translate(28 36) rotate(-90)" fill="#fff" stroke="#707070" stroke-width="1"><path d="M0,0H35a0,0,0,0,1,0,0V0a8,8,0,0,1-8,8H8A8,8,0,0,1,0,0V0A0,0,0,0,1,0,0Z" stroke="none"></path><path d="M.889.5H34.111A.389.389,0,0,1,34.5.889v0A6.611,6.611,0,0,1,27.889,7.5H7.111A6.611,6.611,0,0,1,.5.889v0A.389.389,0,0,1,.889.5Z" fill="none"></path></g><g id="Path_235" data-name="Path 235" transform="translate(7.348 0.604)" fill="#fff"><path d="M 22.39001655578613 34.29282379150391 L 0.4999973177909851 34.29282379150391 L 0.4999973177909851 0.5000019073486328 L 22.39001655578613 0.5000019073486328 L 22.39001655578613 34.29282379150391 Z" stroke="none"></path><path d="M 0.9999980926513672 1 L 0.9999980926513672 33.79282379150391 L 21.89001655578613 33.79282379150391 L 21.89001655578613 1 L 0.9999980926513672 1 M -1.9073486328125e-06 0 L 22.89001655578613 0 L 22.89001655578613 34.79282379150391 L -1.9073486328125e-06 34.79282379150391 L -1.9073486328125e-06 0 Z" stroke="none" fill="#707070"></path></g><g id="Rectangle_521" data-name="Rectangle 521" transform="translate(0)" fill="#fff" stroke="#707070" stroke-width="1"><rect width="36" height="3" rx="1.5" stroke="none"></rect><rect x="0.5" y="0.5" width="35" height="2" rx="1" fill="none"></rect></g><g id="Rectangle_522" data-name="Rectangle 522" transform="translate(0 33)" fill="#fff" stroke="#707070" stroke-width="1"><rect width="36" height="3" rx="1.5" stroke="none"></rect><rect x="0.5" y="0.5" width="35" height="2" rx="1" fill="none"></rect></g><line id="Line_14" data-name="Line 14" y2="30.215" transform="translate(18.335 2.893)" fill="none" stroke="#707070" stroke-width="1"></line><g id="Ellipse_41" data-name="Ellipse 41" transform="translate(15 26)" fill="#fff" stroke="#707070" stroke-width="1"><circle cx="3" cy="3" r="3" stroke="none"></circle><circle cx="3" cy="3" r="2.5" fill="none"></circle></g></g></svg><span class="icon-span">1100 KG</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-detail-about">
            <div class="product-detail-about-content">Açıklama</div>
            <div class="product-detail-about-content">Uyarı</div>
        </div>
    </div>
</section>

<!--====== Product Detail Area End ======-->

<?php include "footer.php"; ?>