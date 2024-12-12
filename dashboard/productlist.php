<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>

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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Ürünler</a></li>
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
                        <div class="card-header">
                            <h5 class="card-title mb-0">Tüm Ürünler</h5>
                        </div>

                        <div class="card-body">
                            <?php
                            include "../z_db.php";

                            // Sayfa numarasını al, yoksa 1 olarak ayarla
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

                            // Sayfa başına ürün sayısı
                            $records_per_page = 20;

                            // Toplam ürün sayısını al
                            $total_query = "SELECT COUNT(*) FROM urunler";
                            $total_result = mysqli_query($con, $total_query);
                            $total_rows = mysqli_fetch_array($total_result)[0];
                            $total_pages = ceil($total_rows / $records_per_page);

                            // LIMIT ve OFFSET hesapla
                            $offset = ($page - 1) * $records_per_page;

                            // Ürünleri çek
                            $query = "SELECT * FROM urunler LIMIT $offset, $records_per_page";
                            $result = mysqli_query($con, $query);

                            if (mysqli_num_rows($result) > 0) {
                                echo "<table class='table table-bordered'>";
                                echo "<thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Description</th>
                                            <th>Stock</th>
                                            <th>Actions</th>
                                        </tr>
                                      </thead>
                                      <tbody>";

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $imagePath = !empty($row['resim']) ? $row['resim'] : 'uploads/default.jpg';

                                    echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td><img src='../$imagePath' style='width: 50px; height: auto;' alt='Product Image'></td>
                                            <td>{$row['isim']}</td>
                                            <td>{$row['fiyat']}</td>
                                            <td>{$row['aciklama']}</td>
                                            <td>{$row['stok']}</td>
                                            <td>
                                                <a href='productupdate.php?id={$row['id']}' class='btn btn-warning btn-sm'>Update</a>
                                                <form action='productdel.php' method='post' style='display:inline;'>
                                                    <input type='hidden' name='product_id' value='{$row['id']}'>
                                                    <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</button>
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
                                            <a class='page-link' href='productlist.php?page=" . ($page - 1) . "' aria-label='Previous'>
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
                                        echo "<li class='page-item'><a class='page-link' href='productlist.php?page={$i}'>{$i}</a></li>";
                                    }
                                }

                                // Sonraki sayfa butonu
                                if ($page < $total_pages) {
                                    echo "<li class='page-item'>
                                            <a class='page-link' href='productlist.php?page=" . ($page + 1) . "' aria-label='Next'>
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
                                echo "No products found.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php include "footer.php"; ?>