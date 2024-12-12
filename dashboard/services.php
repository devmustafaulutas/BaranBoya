<?php 
include 'header.php';
include 'sidebar.php';
include '../z_db.php'; // Veritabanı bağlantısını dahil edin
?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Hizmetler ve Tedarikçiler</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Hizmetler</a></li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Hizmetler -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Hizmetler</h5>
                            <a href="#addServiceModal" class="btn btn-success" data-bs-toggle="modal">
                                <i class="bi bi-plus-lg"></i> Hizmet Ekle
                            </a>
                        </div>

                        <div class="card-body">
                            <?php
                            // Hizmetler CRUD İşlemleri
                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
                                $service_title = $con->real_escape_string($_POST['service_title']);
                                $service_desc = $con->real_escape_string($_POST['service_desc']);
                                $icon = $con->real_escape_string($_POST['icon']);

                                $sql = "INSERT INTO service (service_title, service_desc, icon) VALUES ('$service_title', '$service_desc', '$icon')";
                                if ($con->query($sql) === TRUE) {
                                    echo "<div class='alert alert-success'>Yeni hizmet başarıyla eklendi.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Hata: {$sql}<br>{$con->error}</div>";
                                }
                            }

                            // Hizmet güncelleme
                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_service'])) {
                                $service_id = $con->real_escape_string($_POST['service_id']);
                                $service_title = $con->real_escape_string($_POST['service_title']);
                                $service_desc = $con->real_escape_string($_POST['service_desc']);
                                $icon = $con->real_escape_string($_POST['icon']);

                                $sql = "UPDATE service SET service_title='$service_title', service_desc='$service_desc', icon='$icon' WHERE id='$service_id'";
                                if ($con->query($sql) === TRUE) {
                                    echo "<div class='alert alert-success'>Hizmet başarıyla güncellendi.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Hata: {$sql}<br>{$con->error}</div>";
                                }
                            }

                            // Hizmet silme
                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_service'])) {
                                $service_id = $con->real_escape_string($_POST['service_id']);

                                $sql = "DELETE FROM service WHERE id='$service_id'";
                                if ($con->query($sql) === TRUE) {
                                    echo "<div class='alert alert-success'>Hizmet başarıyla silindi.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Hata: {$sql}<br>{$con->error}</div>";
                                }
                            }

                            // Hizmetler Görünümü
                            $sql = "SELECT * FROM service";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                echo "<table class='table table-bordered table-hover'>";
                                echo "<thead class='table-light'>
                                        <tr>
                                            <th>ID</th>
                                            <th>Başlık</th>
                                            <th>Açıklama</th>
                                            <th>İkon</th>
                                            <th>İşlemler</th>
                                        </tr>
                                      </thead>
                                      <tbody>";
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $row["id"]. "</td>
                                            <td>" . $row["service_title"]. "</td>
                                            <td>" . $row["service_desc"]. "</td>
                                            <td>" . $row["icon"]. "</td>
                                            <td>
                                                <form method='post' action='' style='display:inline-block;'>
                                                    <input type='hidden' name='service_id' value='" . $row['id'] . "'>
                                                    <button type='submit' name='delete_service' class='btn btn-danger btn-sm'>Sil</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo "<div class='alert alert-info'>Hiç hizmet bulunamadı.</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tedarikçiler -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Tedarikçilerimiz</h5>
                            <a href="#addSupplierModal" class="btn btn-success" data-bs-toggle="modal">
                                <i class="bi bi-plus-lg"></i> Tedarikçi Ekle
                            </a>
                        </div>

                        <div class="card-body">
                            <?php
                            // Tedarikçiler CRUD İşlemleri
                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_supplier'])) {
                                // Dosya yükleme işlemi
                                $target_dir = "../assets/img/tedarikcilerimiz/";
                                $target_file = $target_dir . basename($_FILES["resim"]["name"]);
                                $uploadOk = 1;
                                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                                // Dosya tipi kontrolü
                                if(isset($_POST["submit"])) {
                                    $check = getimagesize($_FILES["resim"]["tmp_name"]);
                                    if($check !== false) {
                                        $uploadOk = 1;
                                    } else {
                                        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                                Dosya bir resim değil.
                                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                              </div>";
                                        $uploadOk = 0;
                                    }
                                }
                                // Dosya boyutu kontrolü
                                if ($_FILES["resim"]["size"] > 500000) {
                                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                            Dosya çok büyük.
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                          </div>";
                                    $uploadOk = 0;
                                }

                                // Dosya formatı kontrolü
                                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                && $imageFileType != "gif" ) {
                                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                            Sadece JPG, JPEG, PNG & GIF dosyalarına izin verilmektedir.
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                          </div>";
                                    $uploadOk = 0;
                                }

                                if ($uploadOk == 0) {
                                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                            Dosya yüklenemedi.
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                          </div>";
                                } else {
                                    if (move_uploaded_file($_FILES["resim"]["tmp_name"], $target_file)) {
                                        $resim = basename($_FILES["resim"]["name"]);
                                        if (!empty($resim)) {
                                            $sql = "INSERT INTO tedarikcilerimiz (resim) VALUES ('$resim')";
                                            if ($con->query($sql) === TRUE) {
                                                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                                        Yeni tedarikçi başarıyla oluşturuldu.
                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                      </div>";
                                            } else {
                                                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                                        Hata: " . $sql . "<br>" . $con->error . "
                                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                      </div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>
                                                    Resim alanı gereklidir.
                                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                                  </div>";
                                        }
                                    }
                                }
                            }

                            // Tedarikçi silme
                            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_supplier'])) {
                                $supplier_id = $con->real_escape_string($_POST['supplier_id']);

                                $sql = "DELETE FROM tedarikcilerimiz WHERE id='$supplier_id'";
                                if ($con->query($sql) === TRUE) {
                                    echo "<div class='alert alert-success'>Tedarikçi başarıyla silindi.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Hata: {$sql}<br>{$con->error}</div>";
                                }
                            }

                            // Tedarikçiler Görünümü
                            $sql = "SELECT * FROM tedarikcilerimiz";
                            $result = $con->query($sql);
                            if ($result->num_rows > 0) {
                                echo "<table class='table table-bordered table-hover'>";
                                echo "<thead class='table-light'>
                                        <tr>
                                            <th>ID</th>
                                            <th>Resim</th>
                                            <th>Güncellenme Tarihi</th>
                                            <th>İşlemler</th>
                                        </tr>
                                      </thead>
                                      <tbody>";
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>" . $row["id"]. "</td>
                                            <td><img src='../assets/img/tedarikcilerimiz/" . $row["resim"]. "' style='width: 50px; height: auto;'></td>
                                            <td>" . $row["guncellenme_tarihi"]. "</td>
                                            <td>
                                                <form method='post' action='' style='display:inline-block;'>
                                                    <input type='hidden' name='supplier_id' value='" . $row['id'] . "'>
                                                    <button type='submit' name='delete_supplier' class='btn btn-danger btn-sm'>Sil</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo "<div class='alert alert-info'>Hiç tedarikçi bulunamadı.</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hizmet Ekle Modal -->
            <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addServiceModalLabel">Yeni Hizmet Ekle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="service_title" class="form-label">Başlık</label>
                                    <input type="text" class="form-control" name="service_title" id="service_title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="service_desc" class="form-label">Açıklama</label>
                                    <input type="text" class="form-control" name="service_desc" id="service_desc" required>
                                </div>
                                <div class="mb-3">
                                    <label for="service_detail" class="form-label">Detay</label>
                                    <textarea class="form-control" name="service_detail" id="service_detail" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="ufile" class="form-label">Dosya</label>
                                    <input type="file" class="form-control" name="ufile" id="ufile" required>
                                </div>
                                <div class="mb-3">
                                    <label for="icon" class="form-label">İkon</label>
                                    <input type="text" class="form-control" name="icon" id="icon" required>
                                </div>
                                <button type="submit" name="add_service" class="btn btn-primary">Hizmet Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tedarikçi Ekle Modal -->
            <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSupplierModalLabel">Yeni Tedarikçi Ekle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="resim" class="form-label">Resim</label>
                                    <input type="file" class="form-control" name="resim" id="resim" required>
                                </div>
                                <button type="submit" name="add_supplier" class="btn btn-primary">Tedarikçi Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
