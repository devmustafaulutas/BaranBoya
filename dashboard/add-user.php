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
                                <h4 class="mb-sm-0">Add User</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                                        <li class="breadcrumb-item active">Add</li>
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
                                                <i class="fas fa-home"></i> Add User
                                            </a>
                                        </li>


                                    </ul>
                                </div>



                                <?php
           $status = "OK"; //initial status
$msg="";
           if(ISSET($_POST['save'])){
$username = mysqli_real_escape_string($con,$_POST['username']);
$password = mysqli_real_escape_string($con,$_POST['password']);
$role = mysqli_real_escape_string($con,$_POST['role']);
$nod = mysqli_real_escape_string($con,$_POST['nod']);
$nationality = mysqli_real_escape_string($con,$_POST['nationality']);
$pod = mysqli_real_escape_string($con,$_POST['pod']);
$dod = mysqli_real_escape_string($con,$_POST['dod']);
$itd = mysqli_real_escape_string($con,$_POST['itd']);
$sn = mysqli_real_escape_string($con,$_POST['sn']);
$sc = mysqli_real_escape_string($con,$_POST['sc']);
$nok = mysqli_real_escape_string($con,$_POST['nok']);
$cv = mysqli_real_escape_string($con,$_POST['cv']);

 if ( strlen($username) < 5 ){
$msg=$msg."Username Must Be More Than 5 Char Length.<BR>";
$status= "NOTOK";}
 if ( strlen($password) < 5 ){
$msg=$msg."Password Must Be More Than 5 Char Length.<BR>";
$status= "NOTOK";}

if ( strlen($nod) < 3 ){
  $msg=$msg."Depositor's Name Must Be More Than 3 Char Length.<BR>";
  $status= "NOTOK";}

  if ( strlen($nationality) < 3 ){
    $msg=$msg."Nationality Must Be More Than 3 Char Length.<BR>";
    $status= "NOTOK";}

 if ( strlen($pod) < 3 ){
  $msg=$msg."Purpose of Deposit Must Be More Than 3 Char Length.<BR>";
  $status= "NOTOK";}

 if ( strlen($dod) < 1 ){
  $msg=$msg."Date of Deposit Must contain a Char.<BR>";
  $status= "NOTOK";}


  $rr=mysqli_query($con,"SELECT COUNT(*) FROM deposit WHERE username = '$username'");
  $r = mysqli_fetch_row($rr);
  $nr = $r[0];
  if($nr==1){
  $msg=$msg."Username Already Exists. Please Try Another One.<BR>";
  $status= "NOTOK";
  }


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
$qb=mysqli_query($con,"INSERT INTO deposit (username, password, role,nod,nationality,pod,dod,itd,sn,sc,nok,cv) VALUES ('$username', '$password', '$role', '$nod', '$nationality', '$pod', '$dod', '$itd', '$sn', '$sc', '$nok', '$cv')");


		if($qb){
		    	$errormsg= "
<div class='alert alert-success alert-dismissible alert-outline fade show'>
                  User has been added successfully.
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
if($_SERVER['REQUEST_METHOD'] == 'POST')
						{
						print $errormsg;
						}
   ?>
              <form action="" method="post" enctype="multipart/form-data">
                                                <div class="row">

                                                <div class="col-lg-6">
                                                <div class="mb-3">
                                                <label for="firstnameInput" class="form-label"> Select Role</label>
                                                <select class="form-select" name="role" aria-label="Default select example">
                                                    <option selected="">Select Role </option>
                                                    <option value="admin">Administrator</option>
                                                    <option value="user">User</option>
                                                   </select>
                                           </div>
                                            </div>

   <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Username</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="username" placeholder="Enter Username">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Password</label>
                                                            <input type="password" class="form-control" id="firstnameInput" name="password" placeholder="Enter Password">
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label"> Name Of Depositor</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="nod" placeholder="Enter Name Of Depositor">
                                                        </div>
                                                    </div>
                                                    <!--end col-->

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nationality</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="nationality" placeholder="Enter Depositor Nationality"">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Purpose Of Deposit</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="pod" placeholder="Enter Purpose Of Deposit">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Date Of Deposit</label>
                                                            <input type="date" class="form-control" id="firstnameInput" name="dod" placeholder="Enter Date Of Deposit">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Item Deposited</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="itd" placeholder="Enter Item Deposited">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Serial Numbers</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="sn" placeholder="Serial Numbers">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Security Codes</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="sc" placeholder="Enter Security Codes" >
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Next Of Kin</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="nok" placeholder="Enter Next Of Kin">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Container Value</label>
                                                            <input type="text" class="form-control" id="firstnameInput" name="cv" placeholder="Enter Container Value">
                                                        </div>
                                                    </div>


                                                    <!--end col-->

                                                    <!--end col-->
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit" name="save" class="btn btn-primary">Add User</button>

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
