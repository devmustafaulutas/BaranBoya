<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>
<?php include "../z_db.php"; ?>
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
                        <h4 class="mb-sm-0">Ürün Listesi</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Ürünler</a></li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Tüm Ürünler</h5>
                            <a href="productadd.php" class="btn btn-success">
                                <i class="bi bi-plus-lg"></i> Ürün Ekle
                            </a>
                        </div>

                        <div class="card-body">
                            <?php
                            // Kategorileri çek
                            $kategori_query = "SELECT * FROM kategoriler ORDER BY isim ASC";
                            $kategori_result = mysqli_query($con, $kategori_query);

                            // Seçili kategori
                            $selected_kategori = isset($_GET['kategori']) && is_numeric($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

                            // Sayfa numarasını al, yoksa 1 olarak ayarla
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

                            // Sayfa başına ürün sayısı
                            $records_per_page = 20;

                            // Toplam ürün sayısını al
                            $total_query = "SELECT COUNT(*) FROM urunler WHERE 1";

                            $params = [];
                            $types = "";
                            if ($selected_kategori > 0) {
                                $total_query .= " AND kategori_id = ?";
                                $params[] = $selected_kategori;
                                $types .= "i";
                            }

                            $stmt_total = $con->prepare($total_query);
                            if (!empty($params)) {
                                $stmt_total->bind_param($types, ...$params);
                            }
                            $stmt_total->execute();
                            $stmt_total->bind_result($total_rows);
                            $stmt_total->fetch();
                            $stmt_total->close();

                            $total_pages = ceil($total_rows / $records_per_page);

                            // LIMIT ve OFFSET hesapla
                            $offset = ($page - 1) * $records_per_page;

                            // Ürünleri çek
                            $query = "SELECT urunler.id, urunler.isim, urunler.aciklama, urunler.fiyat, urunler.stok, urunler.resim, urunler.kategori_id, urunler.alt_kategori_id, urunler.alt_kategori_alt_id, kategoriler.isim AS kategori_adi, alt_kategoriler.isim AS alt_kategori_adi, alt_kategoriler_alt.isim AS alt_alt_kategori_adi 
                                      FROM urunler 
                                      LEFT JOIN kategoriler ON urunler.kategori_id = kategoriler.id 
                                      LEFT JOIN alt_kategoriler ON urunler.alt_kategori_id = alt_kategoriler.id 
                                      LEFT JOIN alt_kategoriler_alt ON urunler.alt_kategori_alt_id = alt_kategoriler_alt.id 
                                      WHERE 1";

                            $params = [];
                            $types = "";
                            if ($selected_kategori > 0) {
                                $query .= " AND urunler.kategori_id = ?";
                                $params[] = $selected_kategori;
                                $types .= "i";
                            }

                            $query .= " LIMIT ?, ?";
                            $params[] = $offset;
                            $params[] = $records_per_page;
                            $types .= "ii";

                            $stmt = $con->prepare($query);
                            $stmt->bind_param($types, ...$params);
                            $stmt->execute();
                            $result = [];
                            $stmt->store_result();
                            $stmt->bind_result($id, $isim, $aciklama, $fiyat, $stok, $resim, $kategori_id, $alt_kategori_id, $alt_kategori_alt_id, $kategori_adi, $alt_kategori_adi, $alt_alt_kategori_adi);
                            while ($stmt->fetch()) {
                                $result[] = [
                                    'id' => $id,
                                    'isim' => $isim,
                                    'aciklama' => $aciklama,
                                    'fiyat' => $fiyat,
                                    'stok' => $stok,
                                    'resim' => $resim,
                                    'kategori_id' => $kategori_id,
                                    'alt_kategori_id' => $alt_kategori_id,
                                    'alt_kategori_alt_id' => $alt_kategori_alt_id,
                                    'kategori_adi' => $kategori_adi,
                                    'alt_kategori_adi' => $alt_kategori_adi,
                                    'alt_alt_kategori_adi' => $alt_alt_kategori_adi
                                ];
                            }

                            echo "<form method='get' action='products.php' class='mb-3'>
                                    <div class='row g-3'>
                                        <div class='col-md-3'>
                                            <select name='kategori' class='form-select' onchange='this.form.submit()'>
                                                <option value='0'>Tüm Kategoriler</option>";
                            while ($kategori = mysqli_fetch_assoc($kategori_result)) {
                                $selected = ($kategori['id'] == $selected_kategori) ? "selected" : "";
                                echo "<option value='{$kategori['id']}' {$selected}>{$kategori['isim']}</option>";
                            }
                            echo "      </select>
                                        </div>
                                        <div class='col-md-3'>
                                            <button type='submit' class='btn btn-secondary w-100'>Filtrele</button>
                                        </div>
                                    </div>
                                  </form>";

                            if (!empty($result)) {
                                echo "<table class='table table-bordered table-hover'>";
                                echo "<thead class='table-light'>
                                        <tr>
                                            <th>ID</th>
                                            <th>Resim</th>
                                            <th>İsim</th>
                                            <th>Kategori</th>
                                            <th>Alt Kategori</th>
                                            <th>Alt-Alt Kategori</th>
                                            <th>Fiyat</th>
                                            <th>Açıklama</th>
                                            <th>Stok</th>
                                            <th>Aksiyon</th>
                                        </tr>
                                      </thead>
                                      <tbody>";

                                foreach ($result as $row) {
                                    $imagePath = !empty($row['resim']) ? $row['resim'] : 'uploads/default.jpg';

                                    echo "<tr>
                                            <td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td><img src='../" . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . "' style='width: 50px; height: auto;' alt='Product Image'></td>
                                            <td>" . htmlspecialchars($row['isim'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars($row['kategori_adi'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars($row['alt_kategori_adi'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars($row['alt_alt_kategori_adi'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars($row['fiyat'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars($row['aciklama'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>" . htmlspecialchars($row['stok'], ENT_QUOTES, 'UTF-8') . "</td>
                                            <td>
                                                <a href='productupdate.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' class='btn btn-warning btn-sm'>Güncelle</a>
                                                <form action='productdel.php' method='post' style='display:inline;' onsubmit='return confirm(\"Ürünü silmek istediğinizden emin misiniz?\");'>
                                                    <input type='hidden' name='product_id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>
                                                    <button type='submit' name='delete' class='btn btn-danger btn-sm'>Sil</button>
                                                </form>
                                            </td>
                                          </tr>";
                                }

                                echo "</tbody></table>";

                                // Sayfalama Kontrolleri
                                echo "<nav aria-label='Page navigation example'>";
                                echo "<ul class='pagination justify-content-center'>";

                                // Önceki sayfa butonu
                                if ($page > 1) {
                                    echo "<li class='page-item'>
                                            <a class='page-link' href='products.php?page=" . ($page - 1) . "&kategori={$selected_kategori}' aria-label='Previous'>
                                                <span aria-hidden='true'>&laquo;</span>
                                            </a>
                                          </li>";
                                } else {
                                    echo "<li class='page-item disabled'>
                                            <span class='page-link' aria-label='Previous'>
                                                <span aria-hidden='true'>&laquo;</span>
                                            </span>
                                          </li>";
                                }

                                // Sayfa numaralarını göster
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    if ($i == $page) {
                                        echo "<li class='page-item active'><span class='page-link'>{$i}</span></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='products.php?page={$i}&kategori={$selected_kategori}'>{$i}</a></li>";
                                    }
                                }

                                // Sonraki sayfa butonu
                                if ($page < $total_pages) {
                                    echo "<li class='page-item'>
                                            <a class='page-link' href='products.php?page=" . ($page + 1) . "&kategori={$selected_kategori}' aria-label='Next'>
                                                <span aria-hidden='true'>&raquo;</span>
                                            </a>
                                          </li>";
                                } else {
                                    echo "<li class='page-item disabled'>
                                            <span class='page-link' aria-label='Next'>
                                                <span aria-hidden='true'>&raquo;</span>
                                            </span>
                                          </li>";
                                }

                                echo "</ul>";
                                echo "</nav>";

                            } else {
                                echo "<div class='alert alert-info'>Hiç ürün bulunamadı.</div>";
                            }

                            $stmt->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php include "footer.php"; ?>