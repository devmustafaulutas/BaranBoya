<?php
// hizmetler.php
include "../z_db.php";

// POST ile silme işlemi (header yönlendirmesinin çalışması için include'dan önce)
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    mysqli_query($con, "DELETE FROM service WHERE id=$id");
    header("Location: services");
    exit;
}

include "header.php";
include "sidebar.php";

// Verileri çek
$res = mysqli_query($con, "SELECT * FROM service ORDER BY id DESC");
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- Başlık -->
      <div class="row mb-3">
        <div class="col-6">
          <h4 class="page-title">Hizmetler</h4>
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
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Açıklama</th>
                    <th>İkon</th>
                    <th>İşlemler</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (mysqli_num_rows($res) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($res)): ?>
                      <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['service_title'], ENT_QUOTES) ?></td>
                        <td><?= htmlspecialchars(mb_substr($row['service_desc'], 0, 50), ENT_QUOTES) ?>…</td>
                        <td><?= $row['icon'] ?></td>
                        <td>
                          <a href="add-service.php" class="btn btn-sm btn-success">Ekle</a>
                          <!-- Silme formu -->
                          <form method="post" action="services" style="display:inline-block;">
                            <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?')">Sil</button>
                          </form>
                          <a href="editservice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr><td colspan="5" class="text-center">Hiç hizmet bulunamadı.</td></tr>
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
