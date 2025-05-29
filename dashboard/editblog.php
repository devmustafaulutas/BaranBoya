<?php
require __DIR__ . '/init.php';

include "header.php";
$todo = mysqli_real_escape_string($con, $_GET['id']);
include "sidebar.php";
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Edit Blog</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $query = "SELECT * FROM blog WHERE id='$todo'";
            $result = mysqli_query($con, $query);
            if ($row = mysqli_fetch_assoc($result)) {
                $blog_title = $row['blog_title'];
                $blog_desc = $row['blog_desc'];
                $blog_detail = $row['blog_detail'];
            }
            if (isset($_POST['save'])) {
                $blog_title = mysqli_real_escape_string($con, $_POST['blog_title']);
                $blog_desc = mysqli_real_escape_string($con, $_POST['blog_desc']);
                $blog_detail = mysqli_real_escape_string($con, $_POST['blog_detail']);

                $qb = mysqli_query(
                    $con,
                    "UPDATE blog 
             SET blog_title='$blog_title', blog_desc='$blog_desc', blog_detail='$blog_detail' 
             WHERE id='$todo'"
                );
                if ($qb) {
                    echo "<script>location.replace('blog.php');</script>";
                    exit;
                } else {
                    $errormsg = "
              <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                Güncelleme hatası: " . mysqli_error($con) . "
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
                }
            }
            ?>
            <div class="row">
                <div class="col-xxl-9">
                    <div class="card mt-xxl-n5">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                        <i class="fas fa-home"></i> Edit Blog
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <?php if (!empty($errormsg))
                                echo $errormsg; ?>
                            <div class="tab-content">
                                <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Blog Title</label>
                                                    <input type="text" class="form-control" name="blog_title"
                                                        value="<?= htmlspecialchars($blog_title, ENT_QUOTES) ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Short Description</label>
                                                    <textarea class="form-control" name="blog_desc"
                                                        rows="2"><?= htmlspecialchars($blog_desc) ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Blog Detail</label>
                                                    <textarea class="form-control" name="blog_detail"
                                                        rows="3"><?= htmlspecialchars($blog_detail) ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="submit" name="save" class="btn btn-primary">Update
                                                        Blog</button>
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
</div>

<?php include "footer.php"; ?>