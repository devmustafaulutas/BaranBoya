<?php include("header.php"); ?>
<?php
// Veritabanı bağlantısı
include "z_db.php";
// URL'den 'sector' parametresini alalım
$sektor_adi = isset($_GET['sector']) ? $_GET['sector'] : '';

// Veritabanından sektör bilgilerini alalım
$sektor_query = "SELECT sektor_adi, sektor_aciklama, resim FROM sektorler WHERE sektor_adi = '$sektor_adi'";
$sektor_data = mysqli_query($con, $sektor_query);

// Eğer sektör bulunursa, detayları gösterelim
if ($sektor = mysqli_fetch_array($sektor_data)) {
    $sektor_adi = $sektor['sektor_adi'];
    $sektor_aciklama = $sektor['sektor_aciklama'];
    $sektor_resim = $sektor['resim'];
} else {
    echo "Sektör bulunamadı.";
    exit;
}
?>
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                    <h2 class="text-white text-uppercase mb-3">Sektörler</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home"><?php echo $sektor_adi ?></a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="sektor-detay">
    <div class="container">
        <div class="sektor-detay-baslik-container">
            <h4 class="sektor-detay-baslik">Sektörler</h4>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="sektor-detay-img">
                    <img src="<?php echo $sektor_resim; ?>" alt="<?php echo $sektor_adi; ?>" class="img-fluid">
                </div>
            </div>
            <div class="col-md-7">
                <h4><?php echo $sektor_adi; ?></h4>
                <p><?php echo $sektor_aciklama; ?></p>
            </div>
        </div>
    </div>
</section>
<?php include("footer.php"); ?>