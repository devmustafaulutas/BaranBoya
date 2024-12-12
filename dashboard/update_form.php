<?php
include 'header.php'; // Header dosyasını dahil ediyoruz
include 'sidebar.php'; // Sidebar dosyasını dahil ediyoruz
include '../z_db.php'; // Veritabanı bağlantısı

$table = isset($_GET['table']) ? $_GET['table'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

?>

<div id="container-categorys-form" class="container mt-5">
    <?php
    if($table == 'kategoriler') {
        $sql = "SELECT * FROM kategoriler WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $kategori = $result->fetch_assoc();
        ?>
        <h2 class="mt-5">Kategori Güncelle</h2>
        <form method="post" action="categorys.php?action=update&table=kategoriler">
            <input type="hidden" name="id" value="<?= $kategori['id']; ?>">
            <div class="form-group">
                <label>İsim:</label>
                <input type="text" name="isim" class="form-control" value="<?= $kategori['isim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Resim:</label>
                <input type="text" name="resim" class="form-control" value="<?= $kategori['resim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori ID (varsa):</label>
                <input type="number" name="kategori_id" class="form-control" value="<?= $kategori['kategori_id']; ?>">
            </div>
            <button type="submit" class="btn btn-success">Güncelle</button>
        </form>
        <?php
    } elseif($table == 'alt_kategoriler') {
        $sql = "SELECT * FROM alt_kategoriler WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $alt_kategori = $result->fetch_assoc();
        ?>
        <h2 class="mt-5">Alt Kategori Güncelle</h2>
        <form method="post" action="categorys.php?action=update&table=alt_kategoriler">
            <input type="hidden" name="id" value="<?= $alt_kategori['id']; ?>">
            <div class="form-group">
                <label>İsim:</label>
                <input type="text" name="isim" class="form-control" value="<?= $alt_kategori['isim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Resim:</label>
                <input type="text" name="resim" class="form-control" value="<?= $alt_kategori['resim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori ID:</label>
                <input type="number" name="kategori_id" class="form-control" value="<?= $alt_kategori['kategori_id']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Güncelle</button>
        </form>
        <?php
    } elseif($table == 'alt_kategoriler_alt') {
        $sql = "SELECT * FROM alt_kategoriler_alt WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $alt_kategori_alt = $result->fetch_assoc();
        ?>
        <h2 class="mt-5">Alt Kategorinin Altını Güncelle</h2>
        <form method="post" action="categorys.php?action=update&table=alt_kategoriler_alt">
            <input type="hidden" name="id" value="<?= $alt_kategori_alt['id']; ?>">
            <div class="form-group">
                <label>İsim:</label>
                <input type="text" name="isim" class="form-control" value="<?= $alt_kategori_alt['isim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Resim:</label>
                <input type="text" name="resim" class="form-control" value="<?= $alt_kategori_alt['resim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Alt Kategori ID:</label>
                <input type="number" name="alt_kategori_id" class="form-control" value="<?= $alt_kategori_alt['alt_kategori_id']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Güncelle</button>
        </form>
        <?php
    }
    ?>
</div>

<?php include 'footer.php'; // Footer dosyasını dahil ediyoruz ?>