<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>User Registration</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
     <br><br>
	<div class="col-md-6">
		<div class="card card-primary">
			<div class="card-header text-center">
                    <span class="h3">Enter Your Details for New Connection.</span>
			</div>
			<!-- <div class="card-header">
				<h3 class="card-title">Enter Your Details for New Connection</h3>
			</div> -->
			<!-- /.card-header -->
			<!-- form start -->
			<form id="userForm" method="POST" enctype="multipart/form-data">
				<div class="card-body">
                         <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <input type="text" class="form-control" id="txtId" name="txtId" value="" autocomplete="off" hidden>
                                        <label for="txtName">Name <small style="color: red;"><b>*</b></small></label>
                                        <input type="text" class="form-control" id="txtName" name="txtName" placeholder="Enter Name" data-validation="required">
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="txtPhone">Phone <small style="color: red;"><b>*</b></small></label>
                                        <input type="number" class="form-control" id="txtPhone" name="txtPhone" placeholder="Enter Phone" data-validation="required" minlength="10" maxlength="10">
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="txtEmail">Email <small style="color: red;"><b>*</b></small></label>
                                        <input type="text" class="form-control" id="txtEmail" name="txtEmail" placeholder="Enter Email" data-validation="required">
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label for="txtPassword">Password <small style="color: red;"><b>*</b></small></label>
                                        <input type="password" class="form-control" id="txtPassword" name="txtPassword" 
                                        placeholder="Enter Password" data-validation="required">
                                   </div>
                              </div>
                              
                              <div class="col-md-12">
                                   <div class="form-group">
                                        <label for="txtAddress">Address <small style="color: red;"><b>*</b></small></label>
                                        <textarea class="form-control" id="txtAddress" name="txtAddress" placeholder="Enter Address" data-validation="required"></textarea>
                                   </div>
                              </div>
                              <div class="col-md-12">
                                   <div class="form-group">
                                        <label for="idProofImage">Image <small style="color: red;"><b>*</b></small></label>
                                        <input type="file" class="form-control" id="idProofImage" name="idProofImage" accept="image/*" data-validation="required">
                                   </div>
                              </div>
                              <div class="col-md-12">
                                   <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="chkActive" data-validation="required">
                                        <label class="form-check-label" for="chkActive">Accept the Terms and Conditions ? 
                                             <small style="color: red;"><b>*</b></small>
                                        </label>
                                   </div>
                              </div>
                              <br><br>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <a href="userLogin.php"><button type="button" class="btn btn-danger btn-block">Cancel</button></a>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                   </div>
                              </div>
                         </div>
				</div>
			</form>
		</div>
	</div>
	<!-- /.register-box -->
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>

     <!-- Validation -->
     <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
     <script>
     $.validate();
     </script>

     <!-- SweetAlert -->
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
     
</body>

</html>


<?php

include_once('functions.php');
if(isset($_POST['txtName']))
{
    if(isset($_FILES['idProofImage']['name']))
    {
     //     print_r($_FILES);
	   $uploadResult = uploadFile($_FILES,"idProofImage","upload_images/", array("png","jpg","jpeg") );
	   
	   if($uploadResult['status'] == 'success')
	   {
			$name = $_REQUEST['txtName'];
			$phone = $_REQUEST['txtPhone'];
			$email = $_REQUEST['txtEmail'];
			$password = $_REQUEST['txtPassword'];
			$address = $_REQUEST['txtAddress'];
			$status = 1;//$_REQUEST['isActive'] == "true" ? "active" : "inactive";
			$idproof = $uploadResult['image'];
		
			$sql = "INSERT INTO `users`(`name`,`email`, `pass`, `phone`,`address`, `idproof`, `status`) 
				VALUES ('$name','$email','$password','$phone','$address','$idproof','$status')";
			if(insert_update_delete_data($sql) == true)
			{
                    $msg = getSwalMessgage("You're Registered Successfully, please Login.","",'window.location = "userBooking.php"','window.location = "viewstock.php"',"success");
                    runJavascript($msg);
			}
			else
			{
				runJavascript('swal("Error !!!.", "", "error")');
			}
	   }
	   else
	   {
               runJavascript('swal("Error !!!.", "", "error")');
	   }
    }

  }

?>