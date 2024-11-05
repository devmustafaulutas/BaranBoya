<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Create Product</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                <li class="breadcrumb-item active">Add</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#productDetails" role="tab" aria-selected="false">
                                        <i class="fas fa-box"></i> New Product
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <?php
                        $status = "OK";
                        $msg = "";
                        if (isset($_POST['save'])) {
                            $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
                            $product_price = mysqli_real_escape_string($con, $_POST['product_price']);
                            $product_desc = mysqli_real_escape_string($con, $_POST['product_desc']);
                            $product_stock = mysqli_real_escape_string($con, $_POST['product_stock']);

                            $target_dir = "uploads/"; // Resimlerin yükleneceği dizin
                            $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                            // Resim dosyasının geçerli olup olmadığını kontrol et
                            $check = getimagesize($_FILES["product_image"]["tmp_name"]);
                            if ($check === false) {
                                $msg .= "File is not an image.<br>";
                                $status = "NOTOK";
                            }

                            // Resim boyutunu kontrol et
                            if ($_FILES["product_image"]["size"] > 500000) {
                                $msg .= "Sorry, your file is too large.<br>";
                                $status = "NOTOK";
                            }

                            if ($status == "OK") {
                                if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                                    $image_path = $target_file;
                                } else {
                                    $msg .= "Sorry, there was an error uploading your file.<br>";
                                    $status = "NOTOK";
                                }
                            }

                            // Veritabanına kaydet
                            if ($status == "OK") {
                                $qf = mysqli_query($con, "INSERT INTO urunler (isim, fiyat, aciklama, stok, resim) VALUES ('$product_name', '$product_price', '$product_desc', '$product_stock', '$image_path')");

                                if ($qf) {
                                    $errormsg = "
                                    <div class='alert alert-success alert-dismissible alert-outline fade show'>
                                        Product has been added successfully.
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                                } else {
                                    $errormsg = "
                                    <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                                        Some technical error occurred. Please try again later.
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                    </div>";
                                }
                            } else {
                                $errormsg = "
                                <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                                    $msg
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                            }
                        }
                        ?>

                        <div class="card-body p-4">
                            <div class="tab-content">
                                <div class="tab-pane active" id="productDetails" role="tabpanel">
                                    <?php
                                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                        print $errormsg;
                                    }
                                    ?>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="productName" class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" name="product_name" placeholder="Enter Product Name" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="productPrice" class="form-label">Price</label>
                                                    <input type="number" step="0.01" class="form-control" name="product_price" placeholder="Enter Product Price" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="productDesc" class="form-label">Description</label>
                                                    <textarea class="form-control" name="product_desc" rows="3" placeholder="Enter Product Description"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="productStock" class="form-label">Stock</label>
                                                    <input type="number" class="form-control" name="product_stock" placeholder="Enter Stock Quantity" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="productImage" class="form-label">Product Image</label>
                                                    <input type="file" class="form-control" name="product_image" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" name="save" class="btn btn-primary">Create Product</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include "footer.php"; ?>
