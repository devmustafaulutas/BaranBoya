<?php include "header.php"; ?>

<?php

// Kategorileri ve alt kategorileri çekme
$kategori_sql = "SELECT * FROM kategoriler";
$kategoriler = $con->query($kategori_sql);

$alt_kategori_sql = "SELECT * FROM alt_kategoriler";
$alt_kategoriler = $con->query($alt_kategori_sql);

// Filtreleme
$kategori_id = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : 0;
$alt_kategori_id = isset($_GET['alt_kategori_id']) ? (int)$_GET['alt_kategori_id'] : 0;

$sql = "SELECT u.id, u.isim, u.aciklama, u.fiyat, u.resim, a.isim AS alt_kategori_isim
        FROM urunler u
        JOIN alt_kategoriler a ON u.alt_kategori_id = a.id
        WHERE (a.kategori_id = ? OR ? = 0) AND (u.alt_kategori_id = ? OR ? = 0)
        ORDER BY u.id DESC";

$stmt = $con->prepare($sql);
$stmt->bind_param("iiii", $kategori_id, $kategori_id, $alt_kategori_id, $alt_kategori_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- ***** Breadcrumb Area Start ***** -->
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                    <h2 class="text-white text-uppercase mb-3">Ürünler</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item text-white active">Ürünler</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Breadcrumb Area End ***** -->

<!-- Kategori ve Alt Kategori Seçim Formu -->
<section class="filter-area">
    <div class="container">
        <form method="GET" action="urunler.php">
            <div class="row mb-4">
                <div class="col-md-6">
                    <select name="kategori_id" class="form-control" onchange="this.form.submit()">
                        <option value="0">Tüm Kategoriler</option>
                        <?php if ($kategoriler->num_rows > 0): ?>
                            <?php while ($kategori = $kategoriler->fetch_assoc()): ?>
                                <option value="<?php echo $kategori['id']; ?>" <?php echo ($kategori_id == $kategori['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($kategori['isim']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <select name="alt_kategori_id" class="form-control" onchange="this.form.submit()">
                        <option value="0">Tüm Alt Kategoriler</option>
                        <?php if ($alt_kategori->num_rows > 0): ?>
                            <?php while ($alt_kategori = $alt_kategoriler->fetch_assoc()): ?>
                                <option value="<?php echo $alt_kategori['id']; ?>" <?php echo ($alt_kategori_id == $alt_kategori['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($alt_kategori['isim']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Ürünler Listesi -->
<section class="products-area">
    <div class="container">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($urun = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="product-card">
                            <div class="product-img">
                                <img src="<?php echo htmlspecialchars($urun['resim']); ?>" alt="<?php echo htmlspecialchars($urun['isim']); ?>" class="img-fluid">
                            </div>
                            <div class="product-info text-center">
                                <h3 class="product-title"><?php echo htmlspecialchars($urun['isim']); ?></h3>
                                <p class="product-description"><?php echo htmlspecialchars($urun['aciklama']); ?></p>
                                <p class="product-price"><?php echo number_format($urun['fiyat'], 2, ',', '.') . ' TL'; ?></p>
                                <a href="urun_detay.php?id=<?php echo $urun['id']; ?>" class="btn btn-primary">Detaylar</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Hiç ürün bulunamadı.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>
