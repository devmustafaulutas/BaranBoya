<?php include "header.php"; ?>
<?php
// Hakkımızda verilerini veritabanından çekin
$stmt = $con->prepare("SELECT * FROM about_us WHERE id = ?");
$id = 1;
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$about_content = $data['about_content'];
$vision_content = $data['vision_content'];
$mission_content = $data['mission_content'];
?>
        <!-- ***** Breadcrumb Area Start ***** -->
        <section class="section breadcrumb-area overlay-dark d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Breamcrumb Content -->
                        <div class="breadcrumb-content d-flex flex-column align-items-center text-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-uppercase text-white" href="index.php">Ana Sayfa</a></li>
                                <li class="breadcrumb-item text-white active">Hakkımızda</li>
                            </ol>
                            <p>
                                
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Breadcrumb Area End ***** -->
            
        <!-- ***** About Area Start ***** -->
        <section class="section about-area ptb_100">
            <div id="about-content" class="container">
                <div class="about-content row justify-content-between align-items-center">
                    <div id="hakkimizda">
                        <h5 id="about-baslik" class="text-white text-uppercase mb-3">HAKKIMIZDA</h5>
                        <p><?php echo $about_content; ?></p>
                    </div>
                    <div id="vizyon">
                        <h5 id="about-baslik" class="text-white text-uppercase mb-3">VİZYONUMUZ</h5>
                        <p><?php echo $vision_content; ?></p>
                    </div>
                    <div id="misyon">
                        <h5 id="about-baslik" class="text-white text-uppercase mb-3">MİSYONUMUZ</h5>
                        <p><?php echo $mission_content; ?></p>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** About Area End ***** -->


        <!-- ***** Our Goal Area End ***** -->

        <!-- ***** Team Area Start ***** -->

        <!-- ***** Team Area End ***** -->

        <!--====== Contact Area Start ======-->
    

        <!--====== Call To Action Area Start ======-->
                    <?php
    $rt=mysqli_query($con,"SELECT ufile FROM logo where id=1");
    $tr = mysqli_fetch_array($rt);
    $ufile = "$tr[ufile]";
?>


        <section class="section cta-area bg-overlay ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <!-- Section Heading -->
                        <div class="section-heading text-center m-0">   
                            <div class="about-content-contact">
                                <a href="index.php">
                                    <img src="dashboard/uploads/logo/<?php print $ufile?>" alt="brand-logo">
                                </a>
                            </div>
                            <p class="text-white d-none d-sm-block mt-4">İletişim ve daha detaylı bilgi için</p>
                            <a href="contact" class="btn btn-bordered-white mt-4">Bize Ulaşın</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Call To Action Area End ======-->
<?php include "footer.php"; ?>
