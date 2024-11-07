<?php include "header.php"; ?>
        <!-- ***** Welcome Area Start ***** -->
        <section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Welcome Intro Start -->
                    <div class="col-12 col-md-7">
                    <?php
    $rr=mysqli_query($con,"SELECT * FROM static");
$r = mysqli_fetch_row($rr);
$stitle = $r[1];
$stext=$r[2];
?>

                        <div class="welcome-intro">
                            <h1 class="text-white"><?php print $stitle?></h1>
                            <p class="text-white my-4"><?php print $stext?></p>
                            <!-- Buttons -->
                            <div class="button-group">
                                <a href="#" class="btn btn-bordered-white">Start a Project</a>
                                <a href="#" class="btn btn-bordered-white d-none d-sm-inline-block">Contact Us</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="welcome-thumb-wrapper mt-5 mt-md-0">
                            <div class="slider-container">
                                <div class="slides">
                                    <span class="slide active">
                                        <img class="welcome-animation" src="assets/img/welcome/baran-boya-icerik.png">
                                    </span>
                                    <span class="slide">
                                        <img class="welcome-animation" src="assets/img/welcome/baran-boya-icerik1.png">
                                    </span>
                                    <span class="slide">
                                        <img class="welcome-animation" src="assets/img/welcome/baran-boya-icerik2.png">
                                    </span>
                                </div>
                                <div class="slider-navigation">
                                    <button class="prev">&#10094;</button>
                                    <button class="next">&#10095;</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Shape Bottom -->
            <div class="shape shape-bottom">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
                    <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
        c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
        c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </div>
        </section>
        <!-- ***** Welcome Area End ***** -->

        <!-- ***** Promo Area Start ***** -->
        <section class="section promo-area ptb_100">
            <div class="container">
                <div class="row">


                <?php
				   $q="SELECT * FROM  why_us ORDER BY id DESC LIMIT 3";


 $r123 = mysqli_query($con,$q);

while($ro = mysqli_fetch_array($r123))
{

	$title="$ro[title]";
	$detail="$ro[detail]";

print "
<div class='col-12 col-md-6 col-lg-4 res-margin'>
<!-- Single Promo -->
<div class='single-promo color-1 bg-hover hover-bottom text-center p-5'>
    <h3 class='mb-3'>$title</h3>
    <p>$detail</p>
</div>
</div>
";
}
?>




                </div>
            </div>
        </section>
        <!-- ***** Promo Area End ***** -->

        <!-- ***** Content Area Start ***** -->

        <!-- ***** Content Area End ***** -->

        <!-- ***** Content Area Start ***** -->

        <!-- ***** Content Area End ***** -->

        <!-- ***** Service Area End ***** -->
        <section id="service" class="section service-area bg-grey ptb_150">
            <!-- Shape Top -->
            <div class="shape shape-top">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
                    <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
                c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
                c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-7">
                        <!-- Section Heading -->
                        <div class="section-heading text-center">
                            <h2><?php print $service_title?></h2>
                            <p class="d-none d-sm-block mt-4"><?php print $service_text?></p>
                        </div>
                    </div>
                </div>
                <div class="row">

                <?php
				   $qs="SELECT * FROM  service ORDER BY id DESC LIMIT 6";


 $r1 = mysqli_query($con,$qs);

while($rod = mysqli_fetch_array($r1))
{
	$id="$rod[id]";
	$serviceg="$rod[service_title]";
	$service_desc="$rod[service_desc]";

print "
<div class='col-12 col-md-6 col-lg-4'>
<!-- Single Service -->
<div class='single-service p-4'  style='border: solid 1px #788282;'>
    <h3 class='my-3'>$serviceg</h3>
    <p>$service_desc</p>
    <a class='service-btn mt-3' href='servicedetail.php?id=$id'>Learn More</a>
</div>
</div>

";
}
?>
                   </div>
            </div>
            <!-- Shape Bottom -->
            <div class="shape shape-bottom">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none" fill="#FFFFFF">
                    <path class="shape-fill" d="M421.9,6.5c22.6-2.5,51.5,0.4,75.5,5.3c23.6,4.9,70.9,23.5,100.5,35.7c75.8,32.2,133.7,44.5,192.6,49.7
        c23.6,2.1,48.7,3.5,103.4-2.5c54.7-6,106.2-25.6,106.2-25.6V0H0v30.3c0,0,72,32.6,158.4,30.5c39.2-0.7,92.8-6.7,134-22.4
        c21.2-8.1,52.2-18.2,79.7-24.2C399.3,7.9,411.6,7.5,421.9,6.5z"></path>
                </svg>
            </div>
        </section>
        <!-- ***** Service Area End ***** -->

        <!-- ***** Portfolio Area Start ***** -->
        
        <!-- ***** Portfolio Area End ***** -->

        <!-- ***** Price Plan Area Start ***** -->

        <!-- ***** Price Plan Area End ***** -->

        <!-- ***** Review Area Start ***** -->
        <section id="review" class="section review-area bg-overlay ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10 col-lg-7">
                        <!-- Section Heading -->


                        <div class="section-heading text-center">
                            <h2 class="text-white">TEDARİKÇİLERİMİZ</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Client Reviews -->
                    <div class="client-reviews owl-carousel">
                        <!-- Single Review -->

                        <?php
                        // Veritabanı sorgusu: Son 8 resmi al
                        $q = "SELECT * FROM tedarikcilerimiz ORDER BY id DESC LIMIT 8";
                        $r123 = mysqli_query($con, $q);

                        // Her bir kaydı işleyerek slider için resimleri ekle
                        while ($ro = mysqli_fetch_array($r123)) {
                            $resim = "$ro[resim]";  // Resim dosyasının yolu veya adı

                            // Resmi slider içinde göstermek için HTML çıktısı
                            print "
                            <div class='single-review p-5'>
                                <!-- Review Content -->
                                <div class='review-content'>
                                    <!-- Resim Slider -->
                                    <div class='slider-image'>
                                        <img class='img-fluid' src='$resim'>
                                    </div>
                                </div>
                            </div>
                            ";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Review Area End ***** -->

        <!--====== Contact Area Start ======-->
        <section id="contact" class="contact-area ptb_100">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-lg-5">
                        <!-- Section Heading -->
                        <div class="section-heading text-center mb-3">
                            <h2><?php print $contact_title ?></h2>
                            <p class="d-none d-sm-block mt-4"><?php print $contact_text ?></p>
                        </div>
                        <!-- Contact Us -->
                        <div class="contact-us">
                            <ul>
                                <!-- Contact Info -->
                                <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-3">
                                    <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                                    <a class="d-block my-2" href="tel:<?php print $phone1 ?>">
                                        <h3><?php print $phone1 ?></h3>
                                    </a>

                                </li>
                                <!-- Contact Info -->
                                <li class="contact-info color-3 bg-hover active hover-bottom text-center p-5 m-3">
                                    <span><i class="fas fa-envelope-open-text fa-3x"></i></span>
                                    <a class="d-none d-sm-block my-2" href="mailto:<?php print $email1 ?>">
                                        <h3><?php print $email1 ?></h3>
                                    </a>
                                    <a class="d-block d-sm-none my-2" href="mailto:<?php print $email1 ?>">
                                        <h3><?php print $email1 ?></h3>
                                    </a>

                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 pt-4 pt-lg-0">
                        <!-- Contact Box -->
                        <div class="contact-box text-center">
                            <!-- Contact Form -->
                            <?php
           $status = "OK"; //initial status
$msg="";
           if(ISSET($_POST['save'])){
$name = mysqli_real_escape_string($con,$_POST['name']);
$email = mysqli_real_escape_string($con,$_POST['email']);
$phone = mysqli_real_escape_string($con,$_POST['phone']);
$message = mysqli_real_escape_string($con,$_POST['message']);

 if ( strlen($name) < 5 ){
$msg=$msg."Name Must Be More Than 5 Char Length.<BR>";
$status= "NOTOK";}
 if ( strlen($email) < 9 ){
$msg=$msg."Email Must Be More Than 10 Char Length.<BR>";
$status= "NOTOK";}
if ( strlen($message) < 10 ){
    $msg=$msg."Message Must Be More Than 10 Char Length.<BR>";
    $status= "NOTOK";}

if ( strlen($phone) < 8 ){
  $msg=$msg."Phone Must Be More Than 8 Char Length.<BR>";
  $status= "NOTOK";}

  if($status=="OK")
  {

$recipient="awolu_faith@live.com";

$formcontent="NAME:$name \n EMAIL: $email  \n PHONE: $phone  \n MESSAGE: $message";

$subject = "New Enquiry from Vogue Website";
$mailheader = "From: noreply@vogue.com \r\n";
$result= mail($recipient, $subject, $formcontent);

          if($result){
                  $errormsg= "
  <div class='alert alert-success alert-dismissible alert-outline fade show'>
                   Enquiry Sent Successfully. We shall get back to you ASAP.
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
   "; //printing error if found in validation

          }
      }

          elseif ($status!=="OK") {
              $errormsg= "
  <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                       ".$msg." <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button> </div>"; //printing error if found in validation


      }
      else{
              $errormsg= "
        <div class='alert alert-danger alert-dismissible alert-outline fade show'>
                   Some Technical Glitch Is There. Please Try Again Later Or Ask Admin For Help.
                   <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                   </div>"; //printing error if found in validation


          }
             }
             ?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
						{
						print $errormsg;
						}
   ?>

<form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name" placeholder="Name" required="required">
                                        </div>

                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" placeholder="Email" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="phone" placeholder="Phone" required="required">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message" placeholder="Message" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-bordered active btn-block mt-3" name="save"><span class="text-white pr-3"><i class="fas fa-paper-plane"></i></span>Gönder</button>
                                    </div>
                                </div>
                            </form>
                            <p class="form-message"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Contact Area End ======-->

        <!--====== Call To Action Area Start ======-->
        <section class="section cta-area bg-overlay ptb_100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <!-- Section Heading -->
                        <div class="section-heading text-center m-0">
                            <h2 class="text-white"><?php print $enquiry_title; ?></h2>
                            <p class="text-white d-none d-sm-block mt-4"><?php print $enquiry_text; ?></p>
                            <a href="contact" class="btn btn-bordered-white mt-4">Bize ulaşın</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--====== Call To Action Area End ======-->

      <?php include "footer.php"; ?>
