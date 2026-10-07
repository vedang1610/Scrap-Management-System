<?php include_once("checkLogin.php"); 
include_once("functions.php");

if(isset($_GET['getData']))
{
	$id = $_GET['getData'];
    $data = selectData("SELECT * FROM `staff` where id='$id'");
    if(is_array($data) && count($data) >= 1)
    {
	    $data = $data[0];

	    $name = $data['name'];
	    $email = $data['email'];
	    $phone = $data['phone'];
	    $address = $data['address'];
	    $status = $data['status'];
    }
    else
    {
		runJavascript("alert('Data not found !!!.'); window.location = 'viewstaff.php'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Profile</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.min.css">

	<!-- Functions JS -->
	<script src="functions.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

<!-- Include Header -->
<?php 
$he = 5; 
include_once("header.php");


?>
<!-- Include Header End -->

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1><?php echo (isset($_GET['getData']) ? "Update" : "Add") ?> Staff</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="userDashboard.php">Dashboard</a></li>
							<li class="breadcrumb-item active">Admin Profile</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>

		<!-- Main content -->
		<section class="content">

		<div class="col-md-12">
		<div class="card card-success">
			<!-- <div class="card-header text-center">
										<span class="h3">Update Your Profile Details</span>
			</div> -->
			<!-- <div class="card-header">
				<h3 class="card-title">Enter Your Details for New Connection</h3>
			</div> -->
			<!-- /.card-header -->
			<!-- form start -->
			<form method="POST">
				<div class="card-body">
						<div class="row">
								<div class="col-md-6">
									<div class="form-group">
											<input type="text" class="form-control" id="txtId" name="txtId" value="<?php echo @$id; ?>" autocomplete="off" hidden>
											<label for="txtName">Name <small style="color: red;"><b>*</b></small></label>
											<input type="text" class="form-control" id="txtName" name="txtName" value="<?php echo @$name; ?>" placeholder="Enter Name" data-validation="required" >
									</div>
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                            <label for="txtPhone">Phone <small style="color: red;"><b>*</b></small></label>
                                            <input type="text" class="form-control" id="txtPhone" name="txtPhone" value="<?php echo @$phone; ?>" placeholder="Enter Phone" data-validation="required" >
                                    </div>
								</div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                            <label for="txtEmail">Email <small style="color: red;"><b>*</b></small></label>
                                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="<?php echo @$email; ?>" placeholder="Enter Email" data-validation="required" >
                                    </div>
								</div>
								<div class="col-md-6">
											<div class="form-group">
													<label for="txtAddress">Address <small style="color: red;"><b>*</b></small></label>
													<textarea class="form-control" id="txtAddress" name="txtAddress" placeholder="Enter Address" data-validation="required"><?php echo @$address; ?></textarea>
											</div>
								</div>
								<br><br>
								<div class="col-md-12">
											<div class="form-group">
													<button type="submit" class="btn btn-success btn-block">Submit</button>
											</div>
								</div>
						</div>
				</div>
			</form>
		</div>
	</div>

		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->

	<!-- Include Footer -->
	<?php include_once("footer.php"); ?>
	<!-- Include Footer End -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>

<!-- Validation -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
		 <script>
		 $.validate();
		 addDecimalValidationEvent("txtPrice");
		 </script>

		 <!-- SweetAlert -->
		 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</body>
</html>
<?php



if(isset($_POST['txtName']))
{
	$id = $_REQUEST['txtId'];
	$name = $_REQUEST['txtName'];
	$phone = $_REQUEST['txtPhone'];
	$email = $_REQUEST['txtEmail'];
	$address = $_REQUEST['txtAddress'];

	$message = "";
	if(empty(trim($id)))
	{
		$sql = "INSERT INTO `staff`(`name`, `email`, `phone`, `address`, `status`) 
		VALUES ('$name','$email','$phone','$address','1')";
		$message = "Data Inserted Successfully.";
	}
	else
	{
		$sql = "UPDATE `staff` SET 
					`name` = '$name', 
					`email` = '$email', 
					`phone` = '$phone', 
					`address` = '$address' 
					WHERE id='$id' ";
		$message = "Data Updated Successfully.";
	}
	if(insert_update_delete_data($sql) == true)
	{
		$msg = getSwalMessgage($message,"",'window.location = "viewstaff.php"','window.location = "viewstock.php"',"success");
		runJavascript($msg);
	}
	else
	{
		runJavascript('swal("Error !!!.", "", "error")');
	}
}

?>
