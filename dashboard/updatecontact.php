<?php include"header.php";?>
<?php include"sidebar.php";?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
 <div class="page-content">
       <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Site Contact</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Update</a></li>
                                        <li class="breadcrumb-item active">Contact</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->


                    <div class="row">

                        <!--end col-->
                        <div class="col-xxl-9">
                            <div class="card mt-xxl-n5">
                                <div class="card-header">
                                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" aria-selected="false">
                                                <i class="fas fa-home"></i>Update Contact
                                            </a>
                                        </li>


                                    </ul>
                                </div>



                                <?php
           $status = "OK"; //initial status
$msg="";
           if(ISSET($_POST['save'])){
$phone1= mysqli_real_escape_string($con,$_POST['phone1']);
$phone2 = mysqli_real_escape_string($con,$_POST['phone2']);
$email1 = mysqli_real_escape_string($con,$_POST['email1']);
$email2 = mysqli_real_escape_string($con,$_POST['email2']);
$longitude = mysqli_real_escape_string($con,$_POST['longitude']);
$latitude = mysqli_real_escape_string($con,$_POST['latitude']);

 if ( strlen($phone1) < 10 ){
$msg=$msg."Phone field can not be empty.<BR>";
$status= "NOTOK";}
 if ( strlen($email1) < 5 ){
$msg=$msg."Email Field Must contain an email.<BR>";
$status= "NOTOK";}


 /*
$uploads_dir = 'uploads';

        $tmp_name = $_FILES["ufile"]["tmp_name"];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
        $name = basename($_FILES["ufile"]["name"]);
        $random_digit=rand(0000,9999);
        $new_file_name=$random_digit.$name;

        move_uploaded_file($tmp_name, "$uploads_dir/$new_file_name");*/

if($status=="OK")
{
$qb=mysqli_query($con,"update sitecontact set phone1='$phone1', phone2='$phone2', email1='$email1',email2='$email2',longitude='$longitude',latitude='$latitude' where id=1");

		if($qb){
		    	$errormsg= "
<div class='alert alert-success alert-dismissible alert-outline fade show'>
                  Contact Data Updated successfully.
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



                                <div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">

                                        <?php
					 $query="SELECT * FROM sitecontact where id=1 ";


 $result = mysqli_query($con,$query);
$i=0;
while($row = mysqli_fetch_array($result))
{
	$phone1="$row[phone1]";
	$phone2="$row[phone2]";
  $email1="$row[email1]";
  $email2="$row[email2]";
  $longitude="$row[longitude]";
  $latitude="$row[latitude]";
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


                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Phone</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="phone1"  value="<?php print $phone1 ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Alternative Phone</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="phone2"  value="<?php print $phone2 ?>">
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-6">
                                                <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Email</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="email1"  value="<?php print $email1 ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Alternative Email</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="email2"  value="<?php print $email2 ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Map Longitude</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="longitude"  value="<?php print $longitude ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Map Latitude</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="latitude"  value="<?php print $latitude ?>">
                                                        </div>
                                                    </div>


                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit" name="save" class="btn btn-primary">Update Setting</button>

                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>
                                        </div>
                                        <!--end tab-pane-->

                                        <!--end tab-pane-->

                                        <!--end tab-pane-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>


                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include"footer.php";?>
