<?php
// logo.php
include "header.php";
include "sidebar.php";
include "../z_db.php";

$row = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM logo WHERE id=1"));
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['logo'])) {
    $u = "../assets/img/logo/";
    $f = $_FILES['logo'];
    $ext = pathinfo($f['name'],PATHINFO_EXTENSION);
    $new = uniqid('logo_').".$ext";
    if (move_uploaded_file($f['tmp_name'], "$u$new")) {
      if ($row['logo'] && file_exists("$u{$row['logo']}"))
        unlink("$u{$row['logo']}");
      $stmt = $con->prepare("UPDATE logo SET logo=?, updated_at=NOW() WHERE id=1");
      $stmt->bind_param("s",$new);
      $stmt->execute();
      $msg = "<div class='alert alert-success'>Güncellendi.</div>";
      $row['logo']=$new;
    } else {
      $msg = "<div class='alert alert-danger'>Yükleme Hatası</div>";
    }
}
?>
<div class="main-content">
  <div class="page-content container-fluid">
    <h4>Logo</h4>
    <?= $msg ?? '' ?>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Yeni Logo</label>
        <input type="file" name="logo" accept="image/*" class="form-control">
      </div>
      <button class="btn btn-primary">Yükle</button>
    </form>
    <hr>
    <p>Mevcut Logo:</p>
    <?php if($row['logo']): ?>
      <img src="../assets/img/logo/<?=htmlspecialchars($row['logo'])?>" style="max-height:80px">
    <?php endif; ?>
  </div>
</div>
<?php include "footer.php"; ?>
