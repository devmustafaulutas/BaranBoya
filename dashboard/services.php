<?php
require __DIR__ . '/init.php';

if (isset($_POST['delete_id'])) {
  $id = intval($_POST['delete_id']);
  mysqli_query($con, "DELETE FROM service WHERE id=$id");
  header("Location: services");
  exit;
}
include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';
$res = mysqli_query($con, "SELECT * FROM service ORDER BY id DESC");
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
      <div class="row">
        <div class="col-12">
          <div class="card-header text-white d-flex justify-content-between align-items-center">
            <h4 class="page-title">Servisler</h4>
            <div class="text-end">
              <a href="add-service.php" class="btn btn-success">
                <i class="ri-add-line"></i> Yeni Ekle
              </a>
            </div>
          </div>
        </div>
      </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-striped table-bordered mb-0">
                <thead >
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
                          <form method="post" action="services" style="display:inline-block;">
                            <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger"
                              onclick="return confirm('Bu hizmeti silmek istediğinize emin misiniz?')">Sil</button>
                          </form>
                          <a href="editservice.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center">Hiç hizmet bulunamadı.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . "footer.php"; ?>