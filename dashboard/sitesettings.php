<?php
include "header.php";
include "sidebar.php";
include "../z_db.php";

require '../vendor/autoload.php'; // Kök dizindeki vendor klasörünü dahil edin

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

// CRUD İşlemleri
$action = isset($_GET['action']) ? $_GET['action'] : '';

// ###############################
// ### VERİ ÇEKME ########
// ###############################
$siteconfig = [];
$sitecontact = [];
$logo = [];
$static = [];

// Site Config
$siteconfig_query = "SELECT * FROM siteconfig WHERE id = 1";
$siteconfig_result = mysqli_query($con, $siteconfig_query);
if ($siteconfig_result && mysqli_num_rows($siteconfig_result) > 0) {
    $siteconfig = mysqli_fetch_assoc($siteconfig_result);
} else {
    $siteconfig = [
        'site_title' => '',
        'site_keyword' => '',
        'site_desc' => '',
        'site_about' => '',
        'site_footer' => '',
        'follow_text' => '',
        'site_url' => ''
    ];
}

// SEO Değişkenleri
$site_title = isset($siteconfig['site_title']) ? $siteconfig['site_title'] : 'Varsayılan Başlık';
$site_description = isset($siteconfig['site_desc']) ? $siteconfig['site_desc'] : 'Varsayılan Açıklama';
$site_keywords = isset($siteconfig['site_keyword']) ? $siteconfig['site_keyword'] : 'anahtar, kelimeler';

// Site Contact
$sitecontact_query = "SELECT * FROM sitecontact WHERE id = 1";
$sitecontact_result = mysqli_query($con, $sitecontact_query);
if ($sitecontact_result && mysqli_num_rows($sitecontact_result) > 0) {
    $sitecontact = mysqli_fetch_assoc($sitecontact_result);
} else {
    $sitecontact = [
        'phone1' => '',
        'phone2' => '',
        'email' => ''
    ];
}

// Logo
$logo_query = "SELECT * FROM logo WHERE id = 1";
$logo_result = mysqli_query($con, $logo_query);
if ($logo_result && mysqli_num_rows($logo_result) > 0) {
    $logo = mysqli_fetch_assoc($logo_result);
} else {
    $logo = [
        'id' => '',
        'logo' => '',
        'updated_at' => ''
    ];
}

// Statik İçerikler
$static_query = "SELECT * FROM static";
$static_result = mysqli_query($con, $static_query);

// ###############################
// ### CRUD İŞLEMLERİ ########
// ###############################
// 1. Edit Logo

if ($action == 'edit_logo') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Dosya yükleme işlemleri
        $upload_dir = "../assets/img/logo/";
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!isset($upload_dir)) {
            $upload_dir = "uploads/"; // Varsayılan bir değer atayın
        }
        

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $file_tmp = $_FILES['logo']['tmp_name'];
            $file_name = basename($_FILES['logo']['name']);
            $file_size = $_FILES['logo']['size'];
            $file_type = mime_content_type($file_tmp);

            // Dosya türü ve boyutu kontrolü
            if (!in_array($file_type, $allowed_types)) {
                $errormsg_logo = "<div class='alert alert-danger'>Geçersiz dosya türü. Sadece JPG, PNG ve GIF dosyalarına izin verilmektedir.</div>";
            } elseif ($file_size > $max_size) {
                $errormsg_logo = "<div class='alert alert-danger'>Dosya boyutu 2MB'ı geçemez.</div>";
            } else {
                // Benzersiz dosya adı oluşturma
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $new_filename = uniqid('logo_', true) . "." . $ext;
                $destination = $upload_dir . $new_filename;

                // Yükleme dizini yoksa oluşturma
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Dosyayı hedefe taşıma
                if (move_uploaded_file($file_tmp, $destination)) {
                    // Eski logo dosyasını silme
                    if (!empty($logo['logo']) && file_exists($upload_dir . $logo['logo'])) {
                        unlink($upload_dir . $logo['logo']);
                    }

                    // Veritabanını güncelleme
                    $update_query = "UPDATE logo SET logo = ?, updated_at = NOW() WHERE id = 1";
                    $stmt = $con->prepare($update_query);
                    $stmt->bind_param("s", $new_filename);
                    if ($stmt->execute()) {
                        $errormsg_logo = "<div class='alert alert-success'>Logo başarıyla güncellendi.</div>";
                        // Güncellenmiş logoyu yeniden çekme
                        $logo = mysqli_fetch_assoc(mysqli_query($con, $logo_query));
                    } else {
                        $errormsg_logo = "<div class='alert alert-danger'>Logo güncellenirken bir hata oluştu.</div>";
                    }
                } else {
                    $errormsg_logo = "<div class='alert alert-danger'>Dosya yüklenirken bir hata oluştu.</div>";
                }
            }
        } else {
            $errormsg_logo = "<div class='alert alert-danger'>Lütfen bir logo dosyası seçiniz.</div>";
        }
    }
}

// 2. Edit Site Config
if ($action == 'edit_siteconfig') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Form verilerini sanitize etme
        $site_title = mysqli_real_escape_string($con, $_POST['site_title']);
        $site_keyword = mysqli_real_escape_string($con, $_POST['site_keyword']);
        $site_desc = mysqli_real_escape_string($con, $_POST['site_desc']);
        $site_about = mysqli_real_escape_string($con, $_POST['site_about']);
        $site_footer = mysqli_real_escape_string($con, $_POST['site_footer']);
        $follow_text = mysqli_real_escape_string($con, $_POST['follow_text']);
        $site_url = mysqli_real_escape_string($con, $_POST['site_url']);

        // Veritabanını güncelleme
        $update_query = "UPDATE siteconfig SET 
                            site_title = ?, 
                            site_keyword = ?, 
                            site_desc = ?, 
                            site_about = ?, 
                            site_footer = ?, 
                            follow_text = ?, 
                            site_url = ?, 
                            updated_at = NOW() 
                         WHERE id = 1";

        $stmt = $con->prepare($update_query);
        $stmt->bind_param("sssssss", $site_title, $site_keyword, $site_desc, $site_about, $site_footer, $follow_text, $site_url);

        if ($stmt->execute()) {
            $errormsg_siteconfig = "<div class='alert alert-success'>Site Config başarıyla güncellendi.</div>";
            // Güncellenmiş veriyi yeniden çekme
            $siteconfig = mysqli_fetch_assoc(mysqli_query($con, $siteconfig_query));
        } else {
            $errormsg_siteconfig = "<div class='alert alert-danger'>Site Config güncellenirken bir hata oluştu.</div>";
        }
    }
}

// 3. Edit Site Contact
if ($action == 'edit_sitecontact') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Form verilerini sanitize etme
        $phone1 = mysqli_real_escape_string($con, $_POST['phone1']);
        $phone2 = mysqli_real_escape_string($con, $_POST['phone2']);
        $email = mysqli_real_escape_string($con, $_POST['email']);

        // Basit doğrulamalar
        $status = "OK";
        $msg = "";

        if (strlen($phone1) < 1) {
            $msg .= "Telefon alanı boş olamaz.<br>";
            $status = "NOTOK";
        }
        if (strlen($email) < 1) {
            $msg .= "Email alanı boş olamaz.<br>";
            $status = "NOTOK";
        }

        if ($status == "OK") {
            $qb = mysqli_query($con, "UPDATE sitecontact SET phone1='$phone1', phone2='$phone2', email='$email', updated_at=NOW() WHERE id=1");
            if ($qb) {
                $errormsg_contact = "<div class='alert alert-success alert-dismissible fade show'>
                                      İletişim Bilgileri başarıyla güncellendi.
                                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                      </div>";
                // Güncellenmiş veriyi yeniden çekme
                $sitecontact = mysqli_fetch_assoc(mysqli_query($con, $sitecontact_query));
            } else {
                $errormsg_contact = "<div class='alert alert-danger alert-dismissible fade show'>
                                     Güncelleme sırasında bir hata oluştu.
                                     <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                     </div>";
            }
        } else {
            $errormsg_contact = "<div class='alert alert-danger alert-dismissible fade show'>
                                 $msg
                                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                 </div>";
        }
    }
}

// 4. Add Static Content
if ($action == 'add_static') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Form verilerini sanitize etme
        $stitle = mysqli_real_escape_string($con, $_POST['stitle']);
        $stext = mysqli_real_escape_string($con, $_POST['stext']);

        // Veritabanına ekleme
        $insert_query = "INSERT INTO static (stitle, stext, updated_at) VALUES (?, ?, NOW())";

        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("ss", $stitle, $stext);

        if ($stmt->execute()) {
            $errormsg_add_static = "<div class='alert alert-success'>Yeni statik içerik başarıyla eklendi.</div>";
        } else {
            $errormsg_add_static = "<div class='alert alert-danger'>Statik içerik eklenirken bir hata oluştu.</div>";
        }
    }
}

// 5. Edit Static Content
if ($action == 'edit_static' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Form verilerini sanitize etme
        $stitle = mysqli_real_escape_string($con, $_POST['stitle']);
        $stext = mysqli_real_escape_string($con, $_POST['stext']);

        // Veritabanını güncelleme
        $update_query = "UPDATE static SET 
                            stitle = ?, 
                            stext = ?, 
                            updated_at = NOW() 
                         WHERE id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ssi", $stitle, $stext, $id);

        if ($stmt->execute()) {
            $errormsg_edit_static = "<div class='alert alert-success'>Statik içerik başarıyla güncellendi.</div>";
        } else {
            $errormsg_edit_static = "<div class='alert alert-danger'>Statik içerik güncellenirken bir hata oluştu.</div>";
        }
    }

    // Mevcut veriyi çekme
    $static_edit_query = "SELECT * FROM static WHERE id = $id";
    $static_edit_result = mysqli_query($con, $static_edit_query);
    if ($static_edit_result && mysqli_num_rows($static_edit_result) > 0) {
        $static_edit = mysqli_fetch_assoc($static_edit_result);
    } else {
        $errormsg_edit_static = "<div class='alert alert-danger'>Statik içerik bulunamadı.</div>";
    }
}

// 6. Delete Static Content
if ($action == 'delete_static' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Veritabanından silme
    $delete_query = "DELETE FROM static WHERE id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $errormsg_delete_static = "<div class='alert alert-success'>Statik içerik başarıyla silindi.</div>";
    } else {
        $errormsg_delete_static = "<div class='alert alert-danger'>Statik içerik silinirken bir hata oluştu.</div>";
    }
}

// 7. Delete Logo
if ($action == 'delete_logo' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Eski logo dosyasını silme
    if (!empty($logo['logo']) && file_exists($upload_dir . $logo['logo'])) {
        unlink($upload_dir . $logo['logo']);
    }

    // Veritabanından silme
    $delete_query = "DELETE FROM logo WHERE id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $errormsg_delete_logo = "<div class='alert alert-success'>Logo başarıyla silindi.</div>";
    } else {
        $errormsg_delete_logo = "<div class='alert alert-danger'>Logo silinirken bir hata oluştu.</div>";
    }
}

// Mevcut veriyi tekrar çekme (isteğe bağlı)
?>
<div id="container-sitesettings"class="container mt-5">
    <h2 style="margin-top: 100px;">Site Ayarları Yönetimi</h2>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?php echo ($action == 'edit_siteconfig') ? 'active' : ''; ?>" href="sitesettings.php?action=site_config">Site Ayarları</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($action == 'edit_sitecontact') ? 'active' : ''; ?>" href="sitesettings.php?action=sitecontact">İletişim Bilgileri</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($action == 'edit_logo') ? 'active' : ''; ?>" href="sitesettings.php?action=edit_logo">Logolar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($action == 'setup_2fa.php') ? 'active' : ''; ?>" href="sitesettings.php?action=setup_2fa.php">2 Aşamalı Doğrulama</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($action == 'edit_static' || $action == 'add_static') ? 'active' : ''; ?>" href="sitesettings.php?action=edit_static">Statik İçerikler</a>
        </li>
    </ul>

    <div class="mt-4">
        <?php
        switch ($action) {
            case 'edit_siteconfig':
            case 'add_siteconfig':
            ?>
            <?php
            if ($action == 'edit_siteconfig' && isset($siteconfig)):
                ?>
                <!-- Site Config Düzenleme Formu -->
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Site Ayarlarını Düzenle</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($errormsg_siteconfig)): ?>
                    <?php echo $errormsg_siteconfig; ?>
                    <?php endif; ?>
                    <form action="sitesettings.php?action=edit_siteconfig" method="post">
                    <div class="mb-3">
                        <label for="site_title" class="form-label">Site Başlığı</label>
                        <input type="text" class="form-control" id="site_title" name="site_title" value="<?php echo htmlspecialchars($siteconfig['site_title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="site_keyword" class="form-label">Site Keywords</label>
                        <input type="text" class="form-control" id="site_keyword" name="site_keyword" value="<?php echo htmlspecialchars($siteconfig['site_keyword'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="site_desc" class="form-label">Site Description</label>
                        <textarea class="form-control" id="site_desc" name="site_desc" rows="3" required><?php echo htmlspecialchars($siteconfig['site_desc'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="site_about" class="form-label">Footer About</label>
                        <textarea class="form-control" id="site_about" name="site_about" rows="3" required><?php echo htmlspecialchars($siteconfig['site_about'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="site_footer" class="form-label">Footer Text</label>
                        <input type="text" class="form-control" id="site_footer" name="site_footer" value="<?php echo htmlspecialchars($siteconfig['site_footer'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="follow_text" class="form-label">Social Follow Text</label>
                        <input type="text" class="form-control" id="follow_text" name="follow_text" value="<?php echo htmlspecialchars($siteconfig['follow_text'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="site_url" class="form-label">Website URL</label>
                        <input type="url" class="form-control" id="site_url" name="site_url" value="<?php echo htmlspecialchars($siteconfig['site_url'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Site Ayarlarını Güncelle</button>
                    <a href="sitesettings.php?action=edit_siteconfig" class="btn btn-secondary">İptal</a>
                    </form>
                </div>
                </div>
                <?php
            elseif ($action == 'add_siteconfig'):
                ?>
                <!-- Yeni Site Config Ekleme Formu -->
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Yeni Site Ayarları Ekle</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($errormsg_siteconfig)): ?>
                    <?php echo $errormsg_siteconfig; ?>
                    <?php endif; ?>
                    <form action="sitesettings.php?action=add_siteconfig" method="post">
                    <div class="mb-3">
                        <label for="site_title" class="form-label">Site Başlığı</label>
                        <input type="text" class="form-control" id="site_title" name="site_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="site_keyword" class="form-label">Site Keywords</label>
                        <input type="text" class="form-control" id="site_keyword" name="site_keyword" required>
                    </div>
                    <div class="mb-3">
                        <label for="site_desc" class="form-label">Site Description</label>
                        <textarea class="form-control" id="site_desc" name="site_desc" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="site_about" class="form-label">Footer About</label>
                        <textarea class="form-control" id="site_about" name="site_about" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="site_footer" class="form-label">Footer Text</label>
                        <input type="text" class="form-control" id="site_footer" name="site_footer" required>
                    </div>
                    <div class="mb-3">
                        <label for="follow_text" class="form-label">Social Follow Text</label>
                        <input type="text" class="form-control" id="follow_text" name="follow_text" required>
                    </div>
                    <div class="mb-3">
                        <label for="site_url" class="form-label">Website URL</label>
                        <input type="url" class="form-control" id="site_url" name="site_url" required>
                    </div>
                    <button type="submit" class="btn btn-success">Site Ayarları Ekle</button>
                    <a href="sitesettings.php?action=edit_siteconfig" class="btn btn-secondary">İptal</a>
                    </form>
                </div>
                </div>
            <?php endif; ?>
            <?php
            case 'site_config':
            ?>
            <!-- Site Config Verilerini Görüntüleme -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Mevcut Site Ayarları</h5>
                    <a href="sitesettings.php?action=add_siteconfig" class="btn btn-sm btn-success">Yeni İçerik Ekle</a>
                </div>
                <div class="card-body">
                    <?php
                    if(isset($errormsg_delete_siteconfig)){
                        echo $errormsg_delete_siteconfig;
                    }
                    if(isset($errormsg_add_siteconfig) && $action != 'add_siteconfig'){
                        echo $errormsg_add_siteconfig;
                    }
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Site Başlığı</th>
                                <th>Site Keywords</th>
                                <th>Site Description</th>
                                <th>Footer About</th>
                                <th>Footer Text</th>
                                <th>Social Follow Text</th>
                                <th>Website URL</th>
                                <th>Güncelleme Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($siteconfig['site_title'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($siteconfig['site_keyword'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($siteconfig['site_desc'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($siteconfig['site_about'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($siteconfig['site_footer'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($siteconfig['follow_text'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($siteconfig['site_url'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($siteconfig['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <a href="sitesettings.php?action=edit_siteconfig" class="btn btn-sm btn-warning">Düzenle</a>
                                    <form action="sitesettings.php?action=delete_siteconfig" method="post" style="display:inline;">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Site ayarlarını silmek istediğinize emin misiniz?');">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            break;
                case 'edit_sitecontact':
                case 'add_sitecontact'
                    ?>
                    <?php
                    if ($action == 'edit_sitecontact' && isset($sitecontact)):
                        ?>
                        <!-- İletişim Bilgileri Düzenleme Formu -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">İletişim Bilgilerini Düzenle</h5>
                            </div>
                            <div class="card-body">
                                <?php if(isset($errormsg_contact)): ?>
                                    <?php echo $errormsg_contact; ?>
                                <?php endif; ?>
                                <form action="sitesettings.php?action=edit_sitecontact" method="post">
                                    <div class="mb-3">
                                        <label for="phone1" class="form-label">Telefon 1</label>
                                        <input type="text" class="form-control" id="phone1" name="phone1" value="<?php echo htmlspecialchars($sitecontact['phone1'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone2" class="form-label">Telefon 2</label>
                                        <input type="text" class="form-control" id="phone2" name="phone2" value="<?php echo htmlspecialchars($sitecontact['phone2'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($sitecontact['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">İletişim Bilgilerini Güncelle</button>
                                    <a href="sitesettings.php?action=edit_sitecontact" class="btn btn-secondary">İptal</a>
                                </form>
                            </div>
                        </div>
                        <?php
                    
                    elseif ($action == 'add_sitecontact'):
                        ?>
                        <!-- Yeni İletişim Bilgileri Ekleme Formu -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Yeni İletişim Bilgileri Ekle</h5>
                            </div>
                            <div class="card-body">
                                <?php if(isset($errormsg_contact)): ?>
                                    <?php echo $errormsg_contact; ?>
                                <?php endif; ?>
                                <form action="sitesettings.php?action=add_sitecontact" method="post">
                                    <div class="mb-3">
                                        <label for="phone1" class="form-label">Telefon 1</label>
                                        <input type="text" class="form-control" id="phone1" name="phone1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone2" class="form-label">Telefon 2</label>
                                        <input type="text" class="form-control" id="phone2" name="phone2">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">İletişim Bilgileri Ekle</button>
                                    <a href="sitesettings.php?action=edit_sitecontact" class="btn btn-secondary">İptal</a>
                                </form>
                            </div>
                        </div>
                        <?php
                    endif;
                    ?>
                    <?php
                    case 'sitecontact':
                    ?>
                    <!-- İletişim Bilgilerini Görüntüleme -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Mevcut İletişim Bilgileri</h5>
                            <a href="sitesettings.php?action=add_sitecontact" class="btn btn-sm btn-success">Yeni İçerik Ekle</a>
                        </div>
                        <div class="card-body">
                            <?php
                            if(isset($errormsg_delete_contact)){
                                echo $errormsg_delete_contact;
                            }
                            if(isset($errormsg_add_contact) && $action != 'add_sitecontact'){
                                echo $errormsg_add_contact;
                            }
                            ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Telefon 1</th>
                                        <th>Telefon 2</th>
                                        <th>Email</th>
                                        <th>Güncelleme Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sitecontact['phone1'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($sitecontact['phone2'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($sitecontact['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($sitecontact['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <a href="sitesettings.php?action=edit_sitecontact" class="btn btn-sm btn-warning">Düzenle</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    break;
                    case 'edit_logo':
                        ?>
                        <?php
                        if ($action == 'edit_logo' && isset($logo)):
                            ?>
                            <!-- Logo Düzenleme Formu -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Logoları Düzenle</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($errormsg_logo)): ?>
                                        <?php echo $errormsg_logo; ?>
                                    <?php endif; ?>
                                    <form action="sitesettings.php?action=edit_logo" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Yeni Logo Yükleyin</label>
                                            <input class="form-control" type="file" id="logo" name="logo" accept="image/*" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary" style="width: 125px;">Logo Güncelle</button>
                                        <a href="sitesettings.php?action=edit_logo" class="btn btn-secondary" style="width: 100px;">İptal</a>
                                    </form>
                                </div>
                            </div>
                            <?php
                        endif;
                        ?>

                        <!-- Logo Verilerini Görüntüleme -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title">Mevcut Logo</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                if(isset($errormsg_logo)){
                                    echo $errormsg_logo;
                                }
                                ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Logo</th>
                                            <th>Güncelleme Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo htmlspecialchars($logo['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php 
                                                $default_logo = "../assets/img/logo/logo.png";
                                                $upload_dir ??= "../assets/img/logo/"; 

                                                if (!file_exists($upload_dir . $logo['logo'])) {
                                                    echo '<img src="' . htmlspecialchars($default_logo, ENT_QUOTES, 'UTF-8') . '" alt="Varsayılan Logo" style="max-width: 150px;">';
                                                } elseif (!empty($logo['logo'])) {
                                                    echo '<img src="' . htmlspecialchars($upload_dir . $logo['logo'], ENT_QUOTES, 'UTF-8') . '" alt="Logo" style="max-width: 150px;">';
                                                } else {
                                                    echo 'Yok';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($logo['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <a href="sitesettings.php?action=edit_logo" class="btn btn-sm btn-warning">Düzenle</a>
                                                <form action="sitesettings.php?action=delete_logo" method="post" style="display:inline;">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Logoyu silmek istediğinize emin misiniz?');">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                        <?php
                    break;
                    case 'setup_2fa.php':
                        ?>
                    <?php
                    if ($action == 'setup_2fa.php'):
                        ?>
                        <?php
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }
                            $g = new GoogleAuthenticator();
                            $secret = $g->generateSecret();
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $username = $_SESSION['username'];
                                $query = "UPDATE admin SET 2fa_secret = '$secret' WHERE username = '$username'";
                                if (mysqli_query($con, $query)) {
                                    echo "<div class='alert alert-success'>2FA başarıyla etkinleştirildi.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>2FA etkinleştirilirken bir hata oluştu.</div>";
                                }
                                exit;
                                }
                            $username = $_SESSION['username'];
                            $qrCodeUrl = GoogleQrUrl::generate($username, $secret, 'YourAppName');
                            ?>
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title">2FA Kurulumu</h5>
                                </div>
                                <div class="card-body">
                                    <p>Google Authenticator uygulamasını kullanarak aşağıdaki QR kodunu tarayın:</p>
                                    <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code">
                                    <form method="post">
                                        <input type="hidden" name="secret" value="<?php echo $secret; ?>">
                                        <button type="submit" class="btn btn-primary " style="margin:20px;">2FA'yı Etkinleştir</button>
                                    </form>
                                </div>
                            </div>
                        <?php
                    endif;
                    ?>
                    <?php
                    break;
                    
                    case 'delete_logo':
                        // Logo silme işlemi
                        $logo_query = "SELECT logo FROM logo WHERE id = 1";
                        $logo_result = mysqli_query($con, $logo_query);
                        if ($logo_result && mysqli_num_rows($logo_result) > 0) {
                            $logo_row = mysqli_fetch_assoc($logo_result);
                            $logo_file = $logo_row['logo'];
                            $upload_dir = "../assets/img/logo/";
                            if (file_exists("{$upload_dir}{$logo_file}")) {
                                unlink("{$upload_dir}{$logo_file}");
                            }
                            $update_query = "UPDATE logo SET logo = '' WHERE id = 1";
                            mysqli_query($con, $update_query);
                            $errormsg_logo = "<div class='alert alert-success'>Logo başarıyla silindi.</div>";
                        } else {
                            $errormsg_logo = "<div class='alert alert-danger'>Logo bulunamadı.</div>";
                        }
                        break;

                    case 'edit_static':
                    case 'add_static':
                        ?>
                        <?php
                        if ($action == 'edit_static' && isset($static_edit)):
                            ?>
                            <!-- Statik İçerik Düzenleme Formu -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statik İçeriği Düzenle</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (isset($errormsg_edit_static)) {
                                        echo $errormsg_edit_static;
                                    }
                                    ?>
                                    <form action="sitesettings.php?action=edit_static&id=<?php echo $static_edit['id']; ?>" method="post">
                                        <div class="mb-3">
                                            <label for="stitle" class="form-label">Statik Başlık</label>
                                            <input type="text" class="form-control" id="stitle" name="stitle" value="<?php echo htmlspecialchars($static_edit['stitle'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="stext" class="form-label">Statik Metin</label>
                                            <textarea class="form-control" id="stext" name="stext" rows="5" required><?php echo htmlspecialchars($static_edit['stext'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Statik İçeriği Güncelle</button>
                                        <a href="sitesettings.php?action=edit_static" class="btn btn-secondary">İptal</a>
                                    </form>
                                    <form action="sitesettings.php?action=delete_static&id=<?php echo $static_edit['id']; ?>" method="post" class="mt-3"></form>
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Statik içeriği silmek istediğinize emin misiniz?');">Statik İçeriği Sil</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        elseif ($action == 'add_static'):
                            ?>
                            <!-- Yeni Statik İçerik Ekleme Formu -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Yeni Statik İçerik Ekle</h5>
                                </div>
                                <div class="card-body">
                                    <?php if(isset($errormsg_add_static)): ?>
                                        <?php echo $errormsg_add_static; ?>
                                    <?php endif; ?>
                                    <form action="sitesettings.php?action=add_static" method="post">
                                        <div class="mb-3">
                                            <label for="stitle" class="form-label">Statik Başlık</label>
                                            <input type="text" class="form-control" id="stitle" name="stitle" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="stext" class="form-label">Statik Metin</label>
                                            <textarea class="form-control" id="stext" name="stext" rows="5" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Statik İçerik Ekle</button>
                                        <a href="sitesettings.php?action=edit_static" class="btn btn-secondary">İptal</a>
                                    </form>
                                </div>
                            </div>
                            <?php
                        endif;
                        ?>

                <!-- Statik İçerikleri Görüntüleme -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center"></div>
                        <h5 class="card-title">Statik İçerikler</h5>
                        <a href="sitesettings.php?action=add_static" class="btn btn-sm btn-success" style="width: 100px;">Yeni İçerik Ekle</a>
                    </div>
                    <div class="card-body">
                        <?php
                        if(isset($errormsg_delete_static)){
                            echo $errormsg_delete_static;
                        }
                        if(isset($errormsg_add_static) && $action != 'add_static'){
                            echo $errormsg_add_static;
                        }
                        ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Başlık</th>
                                    <th>Metin</th>
                                    <th>Güncelleme Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($row_static = mysqli_fetch_assoc($static_result)):
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row_static['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($row_static['stitle'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo nl2br(htmlspecialchars($row_static['stext'], ENT_QUOTES, 'UTF-8')); ?></td>
                                        <td><?php echo htmlspecialchars($row_static['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <a href="sitesettings.php?action=edit_static&id=<?php echo $row_static['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                            <a href="sitesettings.php?action=delete_static&id=<?php echo $row_static['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                <?php if(mysqli_num_rows($static_result) == 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Kayıt bulunamadı.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                break;
                
            default:
                echo "<h3>Hoşgeldiniz! Ayarları düzenlemek için yukarıdaki sekmeleri kullanınız.</h3>";
                break;
        }
        ?>
    </div>
</div>

<?php include "footer.php"; ?>
</body>
</html>