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
                        <h4 class="mb-sm-0">Product List</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                <li class="breadcrumb-item active">List</li>
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
                            <h5 class="card-title mb-0">All Products</h5>
                        </div>

                        <div class="card-body">
                            <?php
                            include "db_connection.php";

                            $query = "SELECT * FROM urunler";
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
                                            <td><img src='$imagePath' alt='Product Image' style='width: 50px; height: 50px;'></td>
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
                            } else {
                                echo "<div class='alert alert-warning'>No products found.</div>";
                            }

                            mysqli_close($con);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include "footer.php"; ?>
</div>
