<?php
include 'header.php'; // Header dosyasını dahil ediyoruz
include 'sidebar.php'; // Sidebar dosyasını dahil ediyoruz
include '../z_db.php'; // Veritabanı bağlantısı

$table = isset($_GET['table']) ? $_GET['table'] : '';

?>

<div id="container-categorys-form" class="container mt-5">
    <?php
    if($table == 'kategoriler') {
        ?>
        <h2 class="mt-5">Yeni Kategori Ekle</h2>
        <form method="post" action="categorys.php?action=add&table=kategoriler">
            <div class="form-group">
                <label>İsim:</label>
                <input type="text" name="isim" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Resim:</label>
                <input type="text" name="resim" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategori ID (varsa):</label>
                <input type="number" name="kategori_id" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Kaydet</button>
        </form>
        <?php
    } elseif($table == 'alt_kategoriler') {
        ?>
        <h2 class="mt-5">Yeni Alt Kategori Ekle</h2>
        <form method="post" action="categorys.php?action=add&table=alt_kategoriler">
            <div class="form-group">
                <label>İsim:</label>
                <input type="text" name="isim" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Resim:</label>
                <input type="text" name="resim" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategori ID:</label>
                <input type="number" name="kategori_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Kaydet</button>
        </form>
        <?php
    } elseif($table == 'alt_kategoriler_alt') {
        ?>
        <h2 class="mt-5">Yeni Alt Kategorinin Altı Ekle</h2>
        <form method="post" action="categorys.php?action=add&table=alt_kategoriler_alt">
            <div class="form-group">
                <label>İsim:</label>
                <input type="text" name="isim" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Resim:</label>
                <input type="text" name="resim" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Alt Kategori ID:</label>
                <input type="number" name="alt_kategori_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Kaydet</button>
        </form>
        <?php
    }
    ?>
</div>

<?php include 'footer.php'; // Footer dosyasını dahil ediyoruz ?>