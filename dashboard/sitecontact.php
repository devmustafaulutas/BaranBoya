<?php
// dashboard/contact.php
require __DIR__ . '/init.php';

// Fetch existing contact data
$contact = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT * FROM sitecontact WHERE id=1")
);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p1 = mysqli_real_escape_string($con, $_POST['phone1']);
    $p2 = mysqli_real_escape_string($con, $_POST['phone2']);
    $e  = mysqli_real_escape_string($con, $_POST['email']);

    $stmt = $con->prepare(
        "UPDATE sitecontact
           SET phone1 = ?, phone2 = ?, email = ?, updated_at = NOW()
         WHERE id=1"
    );
    $stmt->bind_param("sss", $p1, $p2, $e);

    if ($stmt->execute()) {
        $msg = '<div class="alert alert-success">Bilgiler başarıyla kaydedildi.</div>';
        // reload data
        $contact = mysqli_fetch_assoc(
            mysqli_query($con, "SELECT * FROM sitecontact WHERE id=1")
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
          <div class="card-header  text-white">
            <h5 class="mb-0">İletişim Bilgileri</h5>
          </div>
          <div class="card-body">
            <?= $msg ?? '' ?>
            <form method="post">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="phone1" class="form-label">Telefon No (Merkez)</label>
                  <input
                    type="text"
                    id="phone1"
                    name="phone1"
                    class="form-control"
                    value="<?= htmlspecialchars($contact['phone1'], ENT_QUOTES) ?>"
                  >
                </div>
                <div class="col-md-6">
                  <label for="phone2" class="form-label">Telefon No (Şubeler)</label>
                  <input
                    type="text"
                    id="phone2"
                    name="phone2"
                    class="form-control"
                    value="<?= htmlspecialchars($contact['phone2'], ENT_QUOTES) ?>"
                  >
                </div>
                <div class="col-12">
                  <label for="email" class="form-label">E-posta</label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="<?= htmlspecialchars($contact['email'], ENT_QUOTES) ?>"
                  >
                </div>
              </div>
              <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success">
                  Kaydet
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <?php include "footer.php"; ?>
