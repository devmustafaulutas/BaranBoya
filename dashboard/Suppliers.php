<?php
// tedarikciler.php
include "../z_db.php";

// Silme işlemi POST veya GET ile
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    mysqli_query($con, "DELETE FROM tedarikcilerimiz WHERE id=$id");
    header("Location: tedarikciler.php");
    exit;
} elseif (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    mysqli_query($con, "DELETE FROM tedarikcilerimiz WHERE id=$id");
    header("Location: tedarikciler.php");
    exit;
}

include "header.php";
include "sidebar.php";

// Verileri çek
$res = mysqli_query($con, "SELECT * FROM tedarikcilerimiz ORDER BY id DESC");
$counter = 1;
?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Başlık ve Ekle Butonu -->
            <div class="row mb-3">
                <div class="col-6">
                    <h4 class="page-title">Tedarikçilerimiz</h4>
                </div>

            </div>

            <!-- Tablo -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Resim</th>
                                        <th>Güncellenme Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($res) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($res)): ?>
                                            <tr>
                                                <td><?= $counter++ ?></td>
                                                <td>
                                                    <img src="../assets/img/tedarikcilerimiz/<?= htmlspecialchars($row['resim'], ENT_QUOTES) ?>"
                                                        style="width:60px; height:auto;" alt="Tedarikçi">
                                                </td>
                                                <td><?= $row['guncellenme_tarihi'] ?></td>
                                                <td>
                                                    <!-- Düzenle -->
                                                    <a href="edit-supplier.php?id=<?= $row['id'] ?>"
                                                        class="btn btn-sm btn-warning">Düzenle</a>
                                                    <!-- Silme formu -->
                                                    <form method="post" action="tedarikciler.php" style="display:inline-block;">
                                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Bu tedarikçiyi silmek istediğinize emin misiniz?')">Sil</button>
                                                    </form>
                                                    <a href="add-supplier.php" class="btn btn-sm btn-success">
                                                        <i class="ri-add-line"></i>Ekle
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">Hiç tedarikçi bulunamadı.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- end row -->

        </div><!-- end container -->
    </div><!-- end page-content -->
</div><!-- end main-content -->

<?php include "footer.php"; ?>