<?php
session_start();
include "header.php";
include "z_db.php";

// --- 1) Blog kayıtlarını çek ve hata kontrolü yap ---
$query  = "SELECT * FROM blog ORDER BY updated_at DESC";
$result = mysqli_query($con, $query);

if (!$result) {
    // Sorgu hatası var: logla, boş dizi setle
    error_log("Blog sorgu hatası: " . mysqli_error($con));
    $rows = [];
} else {
    // Tüm satırları assoc dizisi olarak al
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>

<!-- ***** Breadcrumb Area Start ***** -->
<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content text-center">
                    <h2 class="text-white text-uppercase mb-3">Blog</h2>
                    <ol class="breadcrumb d-flex justify-content-center">
                        <li class="breadcrumb-item">
                            <a class="text-uppercase text-white" href="home">Ana Sayfa</a>
                        </li>
                        <li class="breadcrumb-item text-white active">Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ***** Breadcrumb Area End ***** -->

<section id="body">
    <section id="blog-list" class="blog-page container pt-5">
        <div class="row blog-cards">
            <?php foreach ($rows as $row): ?>
                <?php
                    // --- 2) Logo dosya adını temizle ve kaçışla (basename + htmlspecialchars) ---
                    $rawLogo     = $row['logo'] ?? '';
                    $logoFilename = basename($rawLogo); 
                    $safeLogo     = htmlspecialchars($logoFilename, ENT_QUOTES, 'UTF-8');

                    // --- 3) Başlık ve metinleri kaçışla ---
                    $safeTitle  = htmlspecialchars($row['blog_title'] ?? '', ENT_QUOTES, 'UTF-8');
                    // 150 karakterlik kısa açıklama
                    $shortDesc  = htmlspecialchars(
                        mb_substr($row['blog_desc'] ?? '', 0, 150),
                        ENT_QUOTES, 'UTF-8'
                    );
                    // 300 karakterlik uzun detay snippet
                    $fullDetail = htmlspecialchars(
                        mb_substr($row['blog_detail'] ?? '', 0, 300),
                        ENT_QUOTES, 'UTF-8'
                    );
                ?>
                <div class="col-md-4 mb-4 scroll-fade">
                    <div class="flip-card" onclick="flipCard(this)">
                        <div class="flip-card-inner">

                            <!-- ÖN YÜZ: Kısa özet -->
                            <div class="flip-card-front">
                                <div class="blog-img-wrapper">
                                    <img 
                                        src="assets/img/blog/<?php echo $safeLogo; ?>" 
                                        alt="<?php echo $safeTitle; ?>"
                                    >
                                </div>
                                <div class="flip-front-content">
                                    <h3><?php echo $safeTitle; ?></h3>
                                    <p>
                                        <?php 
                                            echo $shortDesc;
                                            if (mb_strlen($row['blog_desc'] ?? '') > 150) {
                                                echo '…';
                                            }
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <!-- ARKA YÜZ: Detay snippet -->
                            <div class="flip-card-back">
                                <button 
                                  class="close-flip" 
                                  onclick="event.stopPropagation(); flipCard(this)"
                                >
                                    ×
                                </button>
                                <h4><?php echo $safeTitle; ?></h4>
                                <p>
                                    <?php
                                        echo nl2br($fullDetail);
                                        if (mb_strlen($row['blog_detail'] ?? '') > 300) {
                                            echo '…';
                                        }
                                    ?>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</section>

<section class="section cta-area bg-overlay-1 ptb_100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="section-heading text-center m-0">
                    <div class="about-content-contact">
                        <?php
                        // --- 4) $ufile değişkenini sanitize et ve göster ---
                        //     Burada $ufile, daha önce veritabanından çekilmiş logo dosya adıdır.
                        //     Eğer henüz çekmediyseniz, aynı “basename+htmlspecialchars” mantığını uygulayın.
                        $safeUfile = htmlspecialchars(basename($ufile ?? ''), ENT_QUOTES, 'UTF-8');
                        ?>
                        <a href="index.php">
                            <img 
                              src="assets/img/logo/<?php echo $safeUfile; ?>" 
                              alt="brand-logo"
                            >
                        </a>
                    </div>
                    <p class="text-white d-none d-sm-block mt-4">
                        İletişim ve daha detaylı bilgi için
                    </p>
                    <a href="contact.php" class="btn btn-bordered-white mt-4">
                        Bize Ulaşın
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Kayan kartlar için IntersectionObserver
    const scrollFadeEls = document.querySelectorAll(".scroll-fade");
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("active");
            }
        });
    }, { threshold: 0.1 });
    scrollFadeEls.forEach(el => observer.observe(el));

    // Flip Card fonksiyonu
    function flipCard(el) {
        const parent = el.classList.contains("flip-card") 
                       ? el 
                       : el.closest(".flip-card");
        parent.classList.toggle("flipped");
        event.stopPropagation();
    }
</script>

<?php include "footer.php"; ?>
