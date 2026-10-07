<?php include_once("userCheckLogin.php"); 
include_once("functions.php");

if(isset($_GET['getData']))
{
	$id = $_GET['getData'];
    $data = selectData("SELECT * FROM `bookings` where id='$id' order by id desc");
	
    if(is_array($data) && count($data) >= 1)
    {
	    $data = $data[0];

	    $male = ($data['gender'] == "0" ? "selected" : "");
	    $female = ($data['gender'] == "1" ? "selected" : "");
		$other = ($data['gender'] == "2" ? "selected" : "");
    }
    else
    {
		runJavascript("alert('Data not found !!!.'); window.location = 'viewstock.php'");
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
$he = 2; 
include_once("userHeader.php");


?>
<!-- Include Header End -->

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Pass Booking</h1>
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
			<form method="POST" enctype="multipart/form-data">
				<div class="card-body">
						<div class="row">
								<div class="col-md-6" >
									<div class="form-group">
										<label for="name">Name <small style="color: red;"><b>*</b></small></label>
										<input type="hidden" class="form-control" id="id" name="id" value="<?php echo @$data['id']; ?>">
										<input type="hidden" class="form-control" id="old_photo" name="old_photo" value="<?php echo @$data['photo']; ?>">
										<input type="text" class="form-control" id="name" name="name" value="<?php echo @$data['name']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="fname">Father Name <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="fname" name="fname" value="<?php echo @$data['fname']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="dob">DOB <small style="color: red;"><b>*</b></small></label>
										<input type="date" class="form-control" id="dob" name="dob" value="<?php echo @$data['dob']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender <small style="color: red;"><b>*</b></small></label>
                                        <select class="form-control" name="gender" id="gender" data-validation="required">
                                            <option value="">Select Gender</option>
                                            <option value="0" <?php echo @$male; ?>>Male</option>
                                            <option value="1" <?php echo @$female; ?>>Female</option>
											<option value="2" <?php echo @$other; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="phone">Phone <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="phone" name="phone" value="<?php echo @$data['phone']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="email">Email <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="email" name="email" value="<?php echo @$data['email']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="aadhar_no">Aadhar Card No <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="aadhar_no" name="aadhar_no" value="<?php echo @$data['aadhar_card_no']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6">
                                   <div class="form-group">
                                        <label for="photo">Photo  <?php if(!isset($data)) { ?><small style="color: red;"><b>*</b></small> <?php } ?></label>
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" 
										<?php if(!isset($data)) { echo 'data-validation="required"'; } ?>>
                                   </div>
                              	</div>
								<div class="col-md-6">
										<div class="form-group">
											<label for="address">Address <small style="color: red;"><b>*</b></small></label>
											<textarea class="form-control" id="address" name="address" data-validation="required"><?php echo @$data['address']; ?></textarea>
										</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="district">District <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="district" name="district" value="<?php echo @$data['district']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="city">City <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="city" name="city" value="<?php echo @$data['city']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="school">School Name <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="school" name="school" value="<?php echo @$data['school_name']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="admission_no">Admission Number <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="admission_no" name="admission_no" value="<?php echo @$data['admission_no']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6">
										<div class="form-group">
											<label for="school_address">School Address <small style="color: red;"><b>*</b></small></label>
											<textarea class="form-control" id="school_address" name="school_address" data-validation="required"><?php echo @$data['school_address']; ?></textarea>
										</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="pass_from">Pass From <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="pass_from" name="pass_from" value="<?php echo @$data['source_from']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="pass_to">Pass To <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="pass_to" name="pass_to" value="<?php echo @$data['source_to']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="from_date">From Date <small style="color: red;"><b>*</b></small></label>
										<input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo @$data['from_date']; ?>" data-validation="required">
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="to_date">To Date <small style="color: red;"><b>*</b></small></label>
										<input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo @$data['to_date']; ?>" data-validation="required">
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
	<?php include_once("userFooter.php"); ?>
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

<script>
$(document).ready(function(){
//   $("#txtType").change(function(){
//       var id = $(this).val();
//       if(id != "")
//       {

//       }
//       else
//       {
//             $("#txtName").val("");
//             $("#txtPrice").val("");
//             $("#txtDetails").val("");
//       }
//         $.ajax
//         (
//                 {
//                     type: 'GET',
//                     url: 'getData.php',
//                     data: 
//                     {
//                     getTypesById:0,
//                     id:id
//                 },
//                 dataType: 'json',
//                 success: function (response) 
//                 {
//                     // console.log(response);
                    
//                     $("#txtName").val(response['name']);
//                     $("#txtPrice").val(response['price']);
//                     $("#txtDetails").val(response['details']);
//                 }
//             }
//         );
//   });
});
</script>


<?php



if(isset($_POST['name']))
{
	$id = $_REQUEST['id'];
	$name = $_REQUEST['name'];
	$fname = $_REQUEST['fname'];
	$dob = $_REQUEST['dob'];
	$gender = $_REQUEST['gender'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$aadhar_no = $_REQUEST['aadhar_no'];
	$address = $_REQUEST['address'];
	$district = $_REQUEST['district'];
	$city = $_REQUEST['city'];
	$school = $_REQUEST['school'];
	$admission_no = $_REQUEST['admission_no'];
	$school_address = $_REQUEST['school_address'];
	$pass_from = $_REQUEST['pass_from'];
	$pass_to = $_REQUEST['pass_to'];
	$from_date = $_REQUEST['from_date'];
	$to_date = $_REQUEST['to_date'];
	$pass_no = rand(10000000,1000000000);
	$dt = date("Y-m-d");
	$photo = $_REQUEST['old_photo'];

	$userId = $_SESSION['userId'];

	if(isset($_FILES['photo']['name']))
    {
	   $uploadResult = uploadFile($_FILES,"photo","upload_images/", array("png","jpg","jpeg") );
	 
	   if($uploadResult['status'] == 'success')
	   {
		   $photo = $uploadResult['image'];
	   }
    }

	$message = "";
	$sql = "";

	if($id == "") // insert
	{
		$sql = "INSERT INTO `bookings`(`user_id`, `pass_number`, `name`, `fname`, `dob`, `gender`, `aadhar_card_no`, `phone`, `email`, `photo`, `district`, `city`, 				`address`, `school_name`, `school_address`, `admission_no`, `source_from`, `source_to`, `from_date`, `to_date`, `amount`, `payment_type`, 					`payment_status`, `dt`, `remark`) 
							VALUES 
							(
								'$userId',
								'$pass_no',
								'$name',
								'$fname',
								'$dob',
								'$gender',
								'$aadhar_no',
								'$phone',
								'$email',
								'$photo',
								'$district',
								'$city',
								'$address',
								'$school',
								'$school_address',
								'$admission_no',
								'$pass_from',
								'$pass_to',
								'$from_date',
								'$to_date',
								'0',
								'0',
								'0',
								'$dt',
								'-'
							)";
			$message = "Your Pass Information is Saved Successfully, we'll review and let you know the update.";
	}	
	else
	{
		$sql = "UPDATE `bookings` SET 
					`name` = '$name', 
					`fname` = '$fname', 
					`dob` = '$dob', 
					`gender` = '$gender', 
					`aadhar_card_no` = '$aadhar_no', 
					`phone` = '$phone', 
					`email` = '$email', 
					`photo` = '$photo', 
					`district` = '$district', 
					`city` = '$city',
					`address` = '$address', 
					`school_name` = '$school', 
					`school_address` = '$school_address', 
					`admission_no` = '$admission_no', 
					`source_from` = '$pass_from', 
					`source_to` = '$pass_to', 
					`from_date` = '$from_date', 
					`to_date` = '$to_date', 
					`dt` = '$dt', 
					`remark` = '-' 
					where id='$id' ";
					$message = "Your Pass Information is Updated Successfully, we'll review and let you know the update.";
	}
			
	if(insert_update_delete_data($sql) == true)
	{
		$msg = getSwalMessgage("$message","",
			'window.location = "userBooking.php"','window.location = "userBooking.php"',"success");
			runJavascript($msg);
	}
	else
	{
		runJavascript('swal("SM !!!.", "", "error")');
	}
}

?>
