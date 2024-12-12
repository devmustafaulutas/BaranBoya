<?php
include 'header.php'; // Header dosyasını dahil ediyoruz
include 'sidebar.php'; // Sidebar dosyasını dahil ediyoruz
include '../z_db.php'; // Veritabanı bağlantısı

// İşlem ve tablo belirleme
$action = isset($_GET['action']) ? $_GET['action'] : '';
$table = isset($_GET['table']) ? $_GET['table'] : '';

if ($action == 'delete') {
    $id = $_GET['id'];
    if ($table == 'kategoriler') {
        $sql = "DELETE FROM kategoriler WHERE id = ?";
    } elseif ($table == 'alt_kategoriler') {
        $sql = "DELETE FROM alt_kategoriler WHERE id = ?";
    } elseif ($table == 'alt_kategoriler_alt') {
        $sql = "DELETE FROM alt_kategoriler_alt WHERE id = ?";
    }
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: categorys.php');
}

// Kategorileri Listeleme
$sql_kategoriler = "SELECT * FROM kategoriler";
$result_kategoriler = $con->query($sql_kategoriler);

// Alt Kategorileri Listeleme
$sql_alt_kategoriler = "SELECT ak.*, k.isim AS kategori_isim FROM alt_kategoriler ak JOIN kategoriler k ON ak.kategori_id = k.id";
$result_alt_kategoriler = $con->query($sql_alt_kategoriler);

// Alt Kategorilerin Altını Listeleme
$sql_alt_kategoriler_alt = "SELECT aka.*, ak.isim AS alt_kategori_isim FROM alt_kategoriler_alt aka JOIN alt_kategoriler ak ON aka.alt_kategori_id = ak.id";
$result_alt_kategoriler_alt = $con->query($sql_alt_kategoriler_alt);

// HTML ve Formlar
?>
<div id="container-categorys-heading" class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="center">
                Kullanım Kılavuzu
            </h3>
            <p>
                Bu sayfada kategorileri, alt kategorileri ve alt kategorilerin altını yönetebilirsiniz.<br>
                Kategori Güncelleme ve Silme işlemleri için tabloda bulunan butonları kullanabilirsiniz.<br>
                Alt Kategori Güncelle ve Ekleme işlemlerinde ise hangi kategorinin altında gözükmesini istiyorsanız o kategorinin id bilgisine bakıp eklemeli veya güncellemelisiniz.<br>
                Alt Kategorilerin Altı Ekleme işleminde ise hangi alt kategorinin altında gözükmesini istiyorsanız o alt kategorinin id bilgisine bakıp eklemelisiniz.
        </div>
    </div>
</div>
<div id="container-categorys" class="container mt-5">
    <h1 class="mb-4">Kategoriler</h1>
    <a href="add_form.php?table=kategoriler" class="btn btn-primary mb-3">Ekle</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>İsim</th>
                <th>Resim</th>
                <th>Kategori ID</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result_kategoriler->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['isim']; ?></td>
                <td><?= $row['resim']; ?></td>
                <td><?= $row['kategori_id']; ?></td>
                <td>
                    <a href="update_form.php?table=kategoriler&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                    <a href="categorys.php?action=delete&table=kategoriler&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h1 class="mt-5 mb-4">Alt Kategoriler</h1>
    <a href="add_form.php?table=alt_kategoriler" class="btn btn-primary mb-3">Ekle</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>İsim</th>
                <th>Resim</th>
                <th>Kategori ID</th>
                <th>Kategori İsmi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result_alt_kategoriler->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['isim']; ?></td>
                <td><?= $row['resim']; ?></td>
                <td><?= $row['kategori_id']; ?></td>
                <td><?= $row['kategori_isim']; ?></td>
                <td>
                    <a href="update_form.php?table=alt_kategoriler&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                    <a href="categorys.php?action=delete&table=alt_kategoriler&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h1 class="mt-5 mb-4">Alt Kategorilerin Altı</h1>
    <a href="add_form.php?table=alt_kategoriler_alt" class="btn btn-primary mb-3">Ekle</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>İsim</th>
                <th>Resim</th>
                <th>Alt Kategori ID</th>
                <th>Alt Kategori İsmi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result_alt_kategoriler_alt->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['isim']; ?></td>
                <td><?= $row['resim']; ?></td>
                <td><?= $row['alt_kategori_id']; ?></td>
                <td><?= $row['alt_kategori_isim']; ?></td>
                <td>
                    <a href="update_form.php?table=alt_kategoriler_alt&id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                    <a href="categorys.php?action=delete&table=alt_kategoriler_alt&id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; // Footer dosyasını dahil ediyoruz ?>