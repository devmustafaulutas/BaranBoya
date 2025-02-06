<?php include "header.php"; ?>
<?php include "z_db.php" ?>

<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content text-center">
                    <h2 class="text-white text-uppercase mb-3">Blog</h2>
                    <ol class="breadcrumb d-flex justify-content-center">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item text-white active">Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="body">
    <section id="blog-list" class="blog-page container pt-5">
    <div class="row blog-cards">
        <?php
        $query = "SELECT * FROM blog ORDER BY updated_at DESC";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
        ?>
        <div class="col-md-4 mb-4 scroll-fade">
        <div class="flip-card" onclick="flipCard(this)">
            <div class="flip-card-inner">
            <div class="flip-card-front">
                <div class="blog-img-wrapper">
                <img src="assets/img/blog/<?php echo htmlspecialchars($row['logo']); ?>" alt="<?php echo htmlspecialchars($row['blog_title']); ?>">
                </div>
                <div class="flip-front-content">
                <h3><?php echo htmlspecialchars($row['blog_title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?php echo htmlspecialchars(substr($row['blog_desc'], 0, 80), ENT_QUOTES, 'UTF-8'); ?>...</p>
                </div>
            </div>
            <div class="flip-card-back">
                <button class="close-flip" onclick="event.stopPropagation(); flipCard(this)">×</button>
                <h4><?php echo htmlspecialchars($row['blog_title'], ENT_QUOTES, 'UTF-8'); ?></h4>
                <p><?php echo nl2br(htmlspecialchars($row['blog_desc'], ENT_QUOTES, 'UTF-8')); ?></p>
            </div>
            </div>
        </div>
        </div>
        <?php
        endwhile;
        else:
        echo '<p>Hiç blog yazısı bulunamadı.</p>';
        endif;
        ?>
    </div>
    </section>
</section>

<section class="section cta-area bg-overlay-1 ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <!-- Section Heading -->
                        <div class="section-heading text-center m-0">   
                            <div class="about-content-contact">
                                <a href="index.php">
                                    <img src="assets/img/logo/<?php print $ufile?>" alt="brand-logo">
                                </a>
                            </div>
                            <p class="text-white d-none d-sm-block mt-4">İletişim ve daha detaylı bilgi için</p>
                            <a href="contact" class="btn btn-bordered-white mt-4">Bize Ulaşın</a>
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
  let parent = el.classList.contains("flip-card") ? el : el.closest(".flip-card");
  parent.classList.toggle("flipped");
  event.stopPropagation();
}
</script>

<?php include "footer.php"; ?>