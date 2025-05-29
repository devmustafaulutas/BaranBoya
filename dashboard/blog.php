<?php
require __DIR__ . '/init.php';

$delete_error = "";
if (isset($_GET['delete_id'])) {
  $todelete = mysqli_real_escape_string($con, $_GET['delete_id']);
  if (mysqli_query($con, "DELETE FROM blog WHERE id='$todelete'")) {
    header("Location: blog.php");
    exit;
  } else {
    $delete_error = "Silme hatası: " . mysqli_error($con);
  }
}
include __DIR__ . '/header.php';
include __DIR__ . '/sidebar.php';
?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card-header  text-white">
            <h4 class="page-title">Blog Listesi</h4>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <table class="table table-striped table-bordered mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Blog Başlığı</th>
                    <th>İşlem</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sorgu = "SELECT * FROM blog ORDER BY id DESC";
                  $sonuc = mysqli_query($con, $sorgu);
                  while ($row = mysqli_fetch_assoc($sonuc)) {
                    $id = $row['id'];
                    $baslik = htmlspecialchars($row['blog_title'], ENT_QUOTES, 'UTF-8');
                    echo "<tr>
                              <td>{$baslik}</td>
                              <td>
                                <a href='add-blog.php' 
                                class='btn btn-sm btn-success'>
                                    <i class='ri-add-line align-middle'></i>Ekle
                                </a>
                                <a href='blog.php?delete_id={$id}' 
                                  class='btn btn-sm btn-danger' 
                                  title='Sil' 
                                  onclick=\"return confirm('Bu blogu silmek istediğinize emin misiniz?');\">
                                  <i class='ri-delete-bin-2-fill'></i> Sil
                                </a>
                                <a href='editblog.php?id={$id}' 
                                   class='btn btn-sm btn-warning me-1' 
                                   title='Düzenle'>
                                  <i class='ri-pencil-fill'></i> Düzenle
                                </a>
                              </td>
                            </tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"; ?>