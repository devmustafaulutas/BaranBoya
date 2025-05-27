<?php
// sitecontact.php
include "header.php";
include "sidebar.php";
include "../z_db.php";

$contact = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM sitecontact WHERE id=1"));

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $p1 = mysqli_real_escape_string($con,$_POST['phone1']);
    $p2 = mysqli_real_escape_string($con,$_POST['phone2']);
    $e  = mysqli_real_escape_string($con,$_POST['email']);
    $stmt = $con->prepare("
      UPDATE sitecontact 
      SET phone1=?, phone2=?, email=?, updated_at=NOW()
      WHERE id=1
    ");
    $stmt->bind_param("sss",$p1,$p2,$e);
    if ($stmt->execute()) {
      $msg = "<div class='alert alert-success'>Kaydedildi.</div>";
      $contact = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM sitecontact WHERE id=1"));
    } else {
      $msg = "<div class='alert alert-danger'>{$stmt->error}</div>";
    }
}
?>
<div class="main-content">
  <div class="page-content container-fluid">
    <h4>İletişim Bilgileri</h4>
    <?= $msg ?? '' ?>
    <form method="post">
      <div class="mb-3">
        <label>Telefon 1</label>
        <input name="phone1" class="form-control" value="<?=htmlspecialchars($contact['phone1'])?>">
      </div>
      <div class="mb-3">
        <label>Telefon 2</label>
        <input name="phone2" class="form-control" value="<?=htmlspecialchars($contact['phone2'])?>">
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" class="form-control" value="<?=htmlspecialchars($contact['email'])?>">
      </div>
      <button class="btn btn-primary">Kaydet</button>
    </form>
  </div>
</div>
<?php include "footer.php"; ?>
