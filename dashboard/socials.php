<?php
include "../z_db.php";

// CRUD İşlemleri
$action = isset($_GET['action']) ? $_GET['action'] : '';

if($action == 'create'){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $fa = mysqli_real_escape_string($con, $_POST['fa']);
        $social_link = mysqli_real_escape_string($con, $_POST['social_link']);

        $query = "INSERT INTO social (name, fa, social_link) VALUES (?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sss", $name, $fa, $social_link);
        if($stmt->execute()){
            header("Location: socials.php?msg=success");
            exit();
        } else {
            $error = "Ekleme sırasında hata oluştu.";
        }
    }
}
elseif($action == 'edit'){
    $id = intval($_GET['id']);
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $fa = mysqli_real_escape_string($con, $_POST['fa']);
        $social_link = mysqli_real_escape_string($con, $_POST['social_link']);

        $query = "UPDATE social SET name=?, fa=?, social_link=? WHERE id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssi", $name, $fa, $social_link, $id);
        if($stmt->execute()){
            header("Location: socials.php?msg=updated");
            exit();
        } else {
            $error = "Güncelleme sırasında hata oluştu.";
        }
    }
    $query = "SELECT * FROM social WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $social = $result->fetch_assoc();
}
elseif($action == 'delete'){
    $id = intval($_GET['id']);
    $query = "DELETE FROM social WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        header("Location: socials.php?msg=deleted");
        exit();
    } else {
        $error = "Silme sırasında hata oluştu.";
    }
}

// Listeleme
$query = "SELECT * FROM social";
$result = mysqli_query($con, $query);
?>

<?php include "header.php"; ?>
<?php include "sidebar.php"; ?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Sayfa Başlığı -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Sosyal Medya Yönetimi</h4>
                    </div>
                </div>
            </div>
            <!-- Sayfa Başlığı Son -->

            <div class="row">
                <div class="col-12">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if($action == 'create'): ?>
                        <!-- Yeni Sosyal Medya Ekleme Formu -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Yeni Sosyal Medya Ekle</h5>
                            </div>
                            <div class="card-body">
                                <form action="socials.php?action=create" method="post">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">İsim</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fa" class="form-label">Font Awesome Sınıfı</label>
                                        <input type="text" class="form-control" id="fa" name="fa" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="social_link" class="form-label">Sosyal Link</label>
                                        <input type="url" class="form-control" id="social_link" name="social_link" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Ekle</button>
                                    <a href="socials.php" class="btn btn-secondary">İptal</a>
                                </form>
                            </div>
                        </div>
                    <?php elseif($action == 'edit'): ?>
                        <!-- Sosyal Medya Düzenleme Formu -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Sosyal Medya Düzenle</h5>
                            </div>
                            <div class="card-body">
                                <form action="socials.php?action=edit&id=<?php echo $id; ?>" method="post">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">İsim</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($social['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fa" class="form-label">Font Awesome Sınıfı</label>
                                        <input type="text" class="form-control" id="fa" name="fa" value="<?php echo htmlspecialchars($social['fa']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="social_link" class="form-label">Sosyal Link</label>
                                        <input type="url" class="form-control" id="social_link" name="social_link" value="<?php echo htmlspecialchars($social['social_link']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Güncelle</button>
                                    <a href="socials.php" class="btn btn-secondary">İptal</a>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Sosyal Medya Listesi -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Sosyal Medya Listesi</h5>
                                <a href="socials.php?action=create" class="btn btn-success float-end">Yeni Ekle</a>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>İsim</th>
                                            <th>Font Awesome</th>
                                            <th>Sosyal Link</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td><i class="<?php echo htmlspecialchars($row['fa']); ?>"></i> <?php echo htmlspecialchars($row['fa']); ?></td>
                                                <td><a href="<?php echo htmlspecialchars($row['social_link']); ?>" target="_blank">Link</a></td>
                                                <td>
                                                    <a href="socials.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                                    <a href="socials.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                        <?php if(mysqli_num_rows($result) == 0): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Kayıt bulunamadı.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>