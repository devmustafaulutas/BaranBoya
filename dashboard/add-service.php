<?php
require __DIR__ . '/init.php';

$status = "OK";
$msg    = "";

if (isset($_POST['save'])) {
    $service_title  = mysqli_real_escape_string($con, $_POST['service_title']);
    $service_desc   = mysqli_real_escape_string($con, $_POST['service_desc']);
    $service_detail = mysqli_real_escape_string($con, $_POST['service_detail']);
    $icon_html      = mysqli_real_escape_string($con, $_POST['icon_html']);

    // Validasyon
    if (strlen($service_title) < 5) {
        $msg    .= "Başlık en az 5 karakter olmalı.<br>";
        $status = "NOTOK";
    }
    if (strlen($service_desc) > 150) {
        $msg    .= "Kısa açıklama 150 karakterden uzun olamaz.<br>";
        $status = "NOTOK";
    }
    if (strlen($service_detail) < 15) {
        $msg    .= "Detay en az 15 karakter olmalı.<br>";
        $status = "NOTOK";
    }
    if (empty($icon_html)) {
        $msg    .= "Lütfen bir ikon seçin.<br>";
        $status = "NOTOK";
    }

    // Ekleme
    if ($status === "OK") {
        $sql = "INSERT INTO service (service_title, service_desc, service_detail, icon) VALUES ('{$service_title}','{$service_desc}','{$service_detail}','{$icon_html}')";
        if (mysqli_query($con, $sql)) {
            header("Location:services");
            exit;
        } else {
            $msg = "Veritabanı hatası: " . mysqli_error($con);
        }
    }
}
?>

<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>

<!-- RemixIcon CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <!-- Page Title -->
      <div class="row mb-4">
        <div class="col-6">
          <h4 class="page-title">Add Service</h4>
        </div>
        <div class="col-6 text-end">
          <a href="services" class="btn btn-secondary">Back to List</a>
        </div>
      </div>

      <?php if (!empty($msg)): ?>
        <div class="alert alert-danger"><?= $msg ?></div>
      <?php endif; ?>

      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <form method="post">

                <div class="mb-3">
                  <label class="form-label">Service Title</label>
                  <input type="text" name="service_title" class="form-control" placeholder="Enter Service Title" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Short Description</label>
                  <textarea name="service_desc" class="form-control" rows="2" placeholder="Enter Short Description" required></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label">Detailed Description</label>
                  <textarea name="service_detail" class="form-control" rows="4" placeholder="Enter Detailed Description" required></textarea>
                </div>

                <!-- Icon Picker -->
                <div class="mb-3">
                  <label class="form-label">Icon</label>
                  <div>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#iconModal">
                      <i id="iconPreview" class="ri-tools-line fs-2"></i>
                      <span class="ms-2">Select Icon</span>
                    </button>
                  </div>
                  <input type="hidden" name="icon_html" id="iconInput" required>
                </div>

                <div class="text-end">
                  <button type="submit" name="save" class="btn btn-primary">Add Service</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="iconModalLabel">Choose Icon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <?php
          $icons = [
            'ri-tools-line'=>'Tools',
            'ri-customer-service-line'=>'Customer Service',
            'ri-settings-2-line'=>'Settings',
            'ri-certificate-line'=>'Certificate',
            'ri-star-line'=>'Star',
            'ri-rocket-line'=>'Rocket',
            'ri-bar-chart-line'=>'Bar Chart',
            'ri-pencil-line'=>'Edit',
            'ri-delete-bin-line'=>'Delete',
            'ri-check-line'=>'Check',
            'ri-mail-line'=>'Mail',
            'ri-phone-line'=>'Phone',
            'ri-calendar-line'=>'Calendar',
            'ri-earth-line'=>'Earth',
            'ri-lock-line'=>'Lock',
            'ri-unlock-line'=>'Unlock',
            'ri-heart-line'=>'Heart',
            'ri-user-line'=>'User',
            'ri-share-forward-line'=>'Share',
            'ri-download-line'=>'Download'
          ];
          foreach ($icons as $class => $label): ?>
            <div class="col-2 text-center">
              <button type="button" class="btn btn-light icon-btn p-2" data-icon="<?= $class ?>">
                <i class="<?= $class ?> fs-3"></i>
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.icon-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const cls = btn.getAttribute('data-icon');
      document.getElementById('iconPreview').className = cls + ' fs-2';
      document.getElementById('iconInput').value = '<i class="' + cls + '"></i>';
      var modal = bootstrap.Modal.getInstance(document.getElementById('iconModal'));
      modal.hide();
    });
  });
</script>

<?php include "footer.php"; ?>