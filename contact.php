<?php include "header.php";?>
        <!-- ***** Breadcrumb Area Start ***** -->
        <section class="section breadcrumb-area overlay-dark d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- Breamcrumb Content -->
                        <div class="breadcrumb-content text-center">
                            <ol class="breadcrumb d-flex justify-content-center">
                                <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Home</a></li>
                                <li class="breadcrumb-item text-white active">İletişim</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ***** Breadcrumb Area End ***** -->


        <!--====== Contact Area Start ======-->
        <section id="contact" class="contact-area ptb_100">
            <div class="container">
                <div class="contact-baslik-container">
                    <h5 id="contact-baslik" class="text-white text-uppercase mb-3 text-center">İLETİŞİM</h5>
                </div>
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

$recipient="mustafaum538@gmail.com";

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
                                            <input type="text" class="form-control" name="name" placeholder="İsim" required="required">
                                        </div>

                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" placeholder="Email" required="required">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="phone" placeholder="Telefon" required="required">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <textarea class="form-control" name="message" placeholder="Mesaj" required="required"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-bordered active btn-block mt-3" name="save"><span class="text-white pr-3"><i class="fas fa-paper-plane"></i></span>Send Message</button>
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


        <!--====== Map Area Start ======-->
        <section class="section map-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 d-flex flex-column"> <!-- Flexbox için d-flex ve flex-column kullanıldı -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3056.2845398240997!2d32.76084867580579!3d40.00209137150924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d349881bd99935%3A0x4f2adf4a1b5956d5!2sBaran%20Boya%20Polyester!5e0!3m2!1str!2str!4v1730577931652!5m2!1str!2str" style="border:0; width: 100%; height: 500px;" allowfullscreen="" loading="lazy"></iframe>
                        <div class="contact-map-info">
                            <h5>BARAN BOYA İVEDİK (Merkez)</h5>
                        </div>
                        <div id="phone-contact" class="contact-us">
                            <ul>
                                <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-2">
                                    <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                                    <a class="d-block my-2" href="tel:<?php print $phone1 ?>">
                                        <h3><?php print $phone1 ?></h3>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6 d-flex flex-column"> <!-- Flexbox için d-flex ve flex-column kullanıldı -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d48915.37859149094!2d32.792040339506265!3d39.981386176280935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d3520a32d52bc3%3A0x766a247a8bfe0bee!2sBaran%20Boya%20Polyester%20Re%C3%A7ine%20Elyaf%20Jelkot%20Siteler!5e0!3m2!1str!2str!4v1730553471165!5m2!1str!2str" style="border:0; width: 100%; height: 500px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="border:0; width: 100%; height: 500px;" allowfullscreen="" loading="lazy"></iframe>
                        <div class="contact-map-info">
                            <h5>BARAN BOYA SİTELER (Şube)</h5>
                        </div>
                        <div id="phone-contact" class="contact-us">
                            <ul>
                                <li class="contact-info color-1 bg-hover active hover-bottom text-center p-5 m-2">
                                    <span><i class="fas fa-mobile-alt fa-3x"></i></span>
                                    <a class="d-block my-2" href="tel:<?php print $phone2 ?>">
                                        <h3><?php print $phone2 ?></h3>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--====== Map Area End ======-->
        <?php include "footer.php";?>
        