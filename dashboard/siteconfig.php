<?php
// siteconfig.php
include "header.php";
include "sidebar.php";
include "../z_db.php";

// Mevcut ayarları çek
$config = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM siteconfig WHERE id=1"));

// POST ile güncelle
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title   = mysqli_real_escape_string($con, $_POST['site_title']);
    $keywords= mysqli_real_escape_string($con, $_POST['site_keyword']);
    $desc    = mysqli_real_escape_string($con, $_POST['site_desc']);
    $about   = mysqli_real_escape_string($con, $_POST['site_about']);
    $footer  = mysqli_real_escape_string($con, $_POST['site_footer']);
    $follow  = mysqli_real_escape_string($con, $_POST['follow_text']);
    $url     = mysqli_real_escape_string($con, $_POST['site_url']);

    $stmt = $con->prepare("
        UPDATE siteconfig SET
          site_title=?, site_keyword=?, site_desc=?, site_about=?,
          site_footer=?, follow_text=?, site_url=?, updated_at=NOW()
        WHERE id=1
    ");
    $stmt->bind_param("sssssss",
      $title, $keywords, $desc, $about,
      $footer, $follow, $url
    );
    if ($stmt->execute()) {
      $msg = "<div class='alert alert-success'>Kaydedildi.</div>";
      $config = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM siteconfig WHERE id=1"));
    } else {
      $msg = "<div class='alert alert-danger'>Hata: {$stmt->error}</div>";
    }
}
?>
<div class="main-content">
  <div class="page-content container-fluid">
    <h4>Site Ayarları</h4>
    <?= $msg ?? '' ?>
    <form method="post">
      <div class="mb-3">
        <label>Site Başlığı</label>
        <input name="site_title" class="form-control"
               value="<?= htmlspecialchars($config['site_title']) ?>">
      </div>
      <div class="mb-3">
        <label>Site Keywords</label>
        <input name="site_keyword" class="form-control"
               value="<?= htmlspecialchars($config['site_keyword']) ?>">
      </div>
      <div class="mb-3">
        <label>Site Description</label>
        <textarea name="site_desc" class="form-control" rows="3"><?= htmlspecialchars($config['site_desc']) ?></textarea>
      </div>
      <div class="mb-3">
        <label>Footer About</label>
        <textarea name="site_about" class="form-control" rows="3"><?= htmlspecialchars($config['site_about']) ?></textarea>
      </div>
      <div class="mb-3">
        <label>Footer Text</label>
        <input name="site_footer" class="form-control"
               value="<?= htmlspecialchars($config['site_footer']) ?>">
      </div>
      <div class="mb-3">
        <label>Social Follow Text</label>
        <input name="follow_text" class="form-control"
               value="<?= htmlspecialchars($config['follow_text']) ?>">
      </div>
      <div class="mb-3">
        <label>Site URL</label>
        <input name="site_url" type="url" class="form-control"
               value="<?= htmlspecialchars($config['site_url']) ?>">
      </div>
      <button class="btn btn-primary">Kaydet</button>
    </form>
  </div>
</div>
<?php include "footer.php"; ?>
