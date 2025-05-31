<?php
require __DIR__ . '/init.php';

$config = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT * FROM siteconfig WHERE id=1")
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = mysqli_real_escape_string($con, $_POST['site_title']);
    $keywords = mysqli_real_escape_string($con, $_POST['site_keyword']);
    $desc     = mysqli_real_escape_string($con, $_POST['site_desc']);
    $about    = mysqli_real_escape_string($con, $_POST['site_about']);
    $footer   = mysqli_real_escape_string($con, $_POST['site_footer']);
    $follow   = mysqli_real_escape_string($con, $_POST['follow_text']);
    $url      = mysqli_real_escape_string($con, $_POST['site_url']);

    $stmt = $con->prepare(
        "UPDATE siteconfig SET
            site_title     = ?,
            site_keyword   = ?,
            site_desc      = ?,
            site_about     = ?,
            site_footer    = ?,
            follow_text    = ?,
            site_url       = ?,
            updated_at     = NOW()
         WHERE id = 1"
    );
    $stmt->bind_param(
        'sssssss',
        $title,
        $keywords,
        $desc,
        $about,
        $footer,
        $follow,
        $url
    );
    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Ayarlar başarıyla kaydedildi.</div>';
        $config = mysqli_fetch_assoc(
            mysqli_query($con, "SELECT * FROM siteconfig WHERE id=1")
        );
    } else {
        $msg = '<div class="alert alert-danger">Hata: ' . htmlspecialchars($stmt->error) . '</div>';
    }
    $stmt->close();
}

include  __DIR__ .  '/header.php';
include  __DIR__ . '/sidebar.php';
?>

<div class="main-content">
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header text-white">
            <h5 class="mb-0">Site Ayarları</h5>
          </div>
          <div class="card-body">
            <?= $msg ?? '' ?>
            <form method="post">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label" for="site_title">Site Başlığı</label>
                  <input
                    type="text"
                    id="site_title"
                    name="site_title"
                    class="form-control"
                    value="<?= htmlspecialchars($config['site_title'], ENT_QUOTES) ?>"
                  >
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="site_keyword">SEO Kelimeleri</label>
                  <input
                    type="text"
                    id="site_keyword"
                    name="site_keyword"
                    class="form-control"
                    value="<?= htmlspecialchars($config['site_keyword'], ENT_QUOTES) ?>"
                  >
                </div>
                <div class="col-12">
                  <label class="form-label" for="site_desc">Site Açıklaması</label>
                  <textarea
                    id="site_desc"
                    name="site_desc"
                    class="form-control"
                    rows="3"
                  ><?= htmlspecialchars($config['site_desc'], ENT_QUOTES) ?></textarea>
                </div>
                <div class="col-12">
                  <label class="form-label" for="site_about">Footer Hakkında</label>
                  <textarea
                    id="site_about"
                    name="site_about"
                    class="form-control"
                    rows="3"
                  ><?= htmlspecialchars($config['site_about'], ENT_QUOTES) ?></textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="site_footer">Footer Yazısı</label>
                  <input
                    type="text"
                    id="site_footer"
                    name="site_footer"
                    class="form-control"
                    value="<?= htmlspecialchars($config['site_footer'], ENT_QUOTES) ?>"
                  >
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="site_url">Site URL</label>
                  <input
                    type="url"
                    id="site_url"
                    name="site_url"
                    class="form-control"
                    value="<?= htmlspecialchars($config['site_url'], ENT_QUOTES) ?>"
                  >
                </div>
              </div>
              <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success">Kaydet</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <?php include "footer.php"; ?>

