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
                                <h4 class="mb-sm-0">Datatables</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                        <li class="breadcrumb-item active">Datatables</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Basic Datatables</h5>
                                </div>
                                <div class="card-body">
                                    <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                        <thead>
                                            <tr>

                                                <th data-ordering="false">SR No.</th>
                                                <th data-ordering="false">Username</th>
                                                <th data-ordering="false">Name Of Depositor</th>
                                                <th data-ordering="false">Nationality</th>
                                                <th>Item Deposited</th>

                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                        <?php
				   $q="SELECT * FROM  deposit ORDER BY id DESC";


 $r123 = mysqli_query($con,$q);

while($ro = mysqli_fetch_array($r123))
{

	$id="$ro[id]";
	$sn="$ro[sn]";
	$username="$ro[username]";
  $nod="$ro[nod]";
  $nationality="$ro[nationality]";
  $itd="$ro[itd]";


  print "<tr>

				  <td>
				  $sn
				  </td>
				  </td>
				  <td>
				  $username
				  </td>
          <td>
				  $nod
				  </td>
          <td>
				  $nationality
				  </td>
          <td>
				  $itd
				  </td>
          <td>
                                                    <div class='dropdown d-inline-block'>
                                                        <button class='btn btn-soft-secondary btn-sm dropdown' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                            <i class='ri-more-fill align-middle'></i>
                                                        </button>
                                                        <ul class='dropdown-menu dropdown-menu-end'>

                                                            <li><a href='edituser.php?username=$username' class='dropdown-item edit-item-btn'><i class='ri-pencil-fill align-bottom me-2 text-muted'></i> Edit</a></li>
                                                            <li>
                                                                <a href='delete-user.php?username=$username' class='dropdown-item remove-item-btn'>
                                                                    <i class='ri-delete-bin-fill align-bottom me-2 text-muted'></i> Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>


				  </tr>";

  }
  ?>




                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->




                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include"footer.php";?>
