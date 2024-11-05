<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Update Product</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                <li class="breadcrumb-item active">Update</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Update Product Details</h5>
                        </div>

                        <?php
                        $errormsg = "";
                        $product = null;

                        if (isset($_POST['find'])) {
                            $product_id = mysqli_real_escape_string($con, $_POST['product_id']);
                            if (is_numeric($product_id) && $product_id > 0) {
                                $result = mysqli_query($con, "SELECT * FROM urunler WHERE id = '$product_id'");
                                $product = mysqli_fetch_assoc($result);
                                if (!$product) {
                                    $errormsg = "<div class='alert alert-danger alert-dismissible alert-outline fade show'>Product not found.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                                }
                            } else {
                                $errormsg = "<div class='alert alert-danger alert-dismissible alert-outline fade show'>Please enter a valid Product ID.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                            }
                        }

                        if (isset($_POST['update']) && $product) {
                            $isim = mysqli_real_escape_string($con, $_POST['isim']);
                            $fiyat = mysqli_real_escape_string($con, $_POST['fiyat']);
                            $aciklama = mysqli_real_escape_string($con, $_POST['aciklama']);
                            $stok = mysqli_real_escape_string($con, $_POST['stok']);

                            $image_path = $product['image_path']; // Varsayılan olarak eski resim

                            // Yeni resim yüklenmişse işlemi yap
                            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
                                $target_dir = "uploads/";
                                $image_path = $target_dir . basename($_FILES["product_image"]["name"]);
                                move_uploaded_file($_FILES["product_image"]["tmp_name"], $image_path);
                            }

                            // Update sorgusu
                            $qf = mysqli_query($con, "UPDATE urunler SET isim='$isim', fiyat='$fiyat', aciklama='$aciklama', stok='$stok', image_path='$image_path' WHERE id='$product[id]'");

                            if ($qf) {
                                $errormsg = "<div class='alert alert-success alert-dismissible alert-outline fade show'>Product has been updated successfully.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                                $product = null;
                            } else {
                                $errormsg = "<div class='alert alert-danger alert-dismissible alert-outline fade show'>Some technical error occurred. Please try again later.<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                            }
                        }
                        ?>

                        <div class="card-body p-4">
                            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { print $errormsg; } ?>
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="productId" class="form-label">Product ID</label>
                                    <input type="number" class="form-control" name="product_id" placeholder="Enter Product ID to Update" required>
                                    <button type="submit" name="find" class="btn btn-primary mt-2">Find Product</button>
                                </div>
                            </form>

                            <?php if ($product): ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <div class="mb-3">
                                    <label for="productName" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="isim" value="<?php echo $product['isim']; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Product Price</label>
                                    <input type="number" step="0.01" class="form-control" name="fiyat" value="<?php echo $product['fiyat']; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="productDescription" class="form-label">Product Description</label>
                                    <textarea class="form-control" name="aciklama" rows="3"><?php echo $product['aciklama']; ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="productStock" class="form-label">Product Stock</label>
                                    <input type="number" class="form-control" name="stok" value="<?php echo $product['stok']; ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="productImage" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" name="product_image">
                                    <?php if (!empty($product['image_path'])): ?>
                                        <img src="<?php echo $product['image_path']; ?>" alt="Product Image" style="width: 100px; height: auto; margin-top: 10px;">
                                    <?php endif; ?>
                                </div>

                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" name="update" class="btn btn-success">Update Product</button>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>
