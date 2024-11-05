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
                        <h4 class="mb-sm-0">Delete Product</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Products</a></li>
                                <li class="breadcrumb-item active">Delete</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Select Product to Delete</h5>
                        </div>

                        <?php
                        // Error messages
                        $errormsg = "";
                        if (isset($_POST['delete'])) {
                            $product_id = mysqli_real_escape_string($con, $_POST['product_id']);

                            // Check if the product ID is valid
                            if (is_numeric($product_id) && $product_id > 0) {
                                // Delete query
                                $qf = mysqli_query($con, "DELETE FROM urunler WHERE id = '$product_id'");

                                if ($qf) {
                                    $errormsg = "
                                    <div class='alert alert-success alert-dismissible alert-outline fade show'>
                                        Product has been deleted successfully.
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
                                    Please enter a valid Product ID.
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                            }
                        }
                        ?>

                        <div class="card-body p-4">
                            <?php
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                print $errormsg;
                            }
                            ?>
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="productId" class="form-label">Product ID</label>
                                    <input type="number" class="form-control" name="product_id" placeholder="Enter Product ID to Delete" required>
                                </div>

                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete Product</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?php include "footer.php"; ?>
