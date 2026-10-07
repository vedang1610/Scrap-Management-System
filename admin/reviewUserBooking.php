<?php include_once("checkLogin.php"); 
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
	<title>Admin Dashboard</title>

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
$he = 4; 
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
										<input type="text" class="form-control" id="name" name="name" value="<?php echo @$data['name']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="fname">Father Name <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="fname" name="fname" value="<?php echo @$data['fname']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="dob">DOB <small style="color: red;"><b>*</b></small></label>
										<input type="date" class="form-control" id="dob" name="dob" value="<?php echo @$data['dob']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender">Gender <small style="color: red;"><b>*</b></small></label>
                                        <select class="form-control" name="gender" id="gender" data-validation="required" readonly>
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
										<input type="text" class="form-control" id="phone" name="phone" value="<?php echo @$data['phone']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="email">Email <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="email" name="email" value="<?php echo @$data['email']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="aadhar_no">Aadhar Card No <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="aadhar_no" name="aadhar_no" value="<?php echo @$data['aadhar_card_no']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6">
                                   <div class="form-group">
                                        <label for="photo">Photo  <?php if(!isset($data)) { ?><small style="color: red;"><b>*</b></small> <?php } ?></label>
                                        <a href="upload_images/<?php echo $data['photo']; ?>" target="_blank">
                                            <img src="upload_images/<?php echo $data['photo']; ?>" width="100" height="100">
                                        </a>
                                   </div>
                              </div>
								<div class="col-md-12">
										<div class="form-group">
											<label for="address">Address <small style="color: red;"><b>*</b></small></label>
											<textarea class="form-control" id="address" name="address" data-validation="required" readonly><?php echo @$data['address']; ?></textarea>
										</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="district">District <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="district" name="district" value="<?php echo @$data['district']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="city">City <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="city" name="city" value="<?php echo @$data['city']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="school">School Name <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="school" name="school" value="<?php echo @$data['school_name']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="admission_no">Admission Number <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="admission_no" name="admission_no" value="<?php echo @$data['admission_no']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6">
										<div class="form-group">
											<label for="school_address">School Address <small style="color: red;"><b>*</b></small></label>
											<textarea class="form-control" id="school_address" name="school_address" data-validation="required" readonly><?php echo @$data['school_address']; ?></textarea>
										</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="pass_from">Pass From <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="pass_from" name="pass_from" value="<?php echo @$data['source_from']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="pass_to">Pass To <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="pass_to" name="pass_to" value="<?php echo @$data['source_to']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="from_date">From Date <small style="color: red;"><b>*</b></small></label>
										<input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo @$data['from_date']; ?>" data-validation="required" readonly>
									</div>
								</div>
								<div class="col-md-6" >
									<div class="form-group">
										<label for="to_date">To Date <small style="color: red;"><b>*</b></small></label>
										<input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo @$data['to_date']; ?>" data-validation="required" readonly>
									</div>
								</div>

                                <div class="col-md-6">
									<div class="form-group">
										<label for="admission_no">Admission Number <small style="color: red;"><b>*</b></small></label>
										<input type="text" class="form-control" id="admission_no" name="admission_no" value="<?php echo @$data['admission_no']; ?>" data-validation="required" readonly>
									</div>
								</div>
						</div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                            </div>
                            
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label for="amount">Amount <small style="color: red;"><b>*</b></small></label>
                                    <input type="text" class="form-control" id="amount" name="amount" value="<?php echo @$data['amount']; ?>" data-validation="required">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PaymentMode">Payment Mode <small style="color: red;"><b>*</b></small></label>
                                    <select class="form-control" name="PaymentMode" id="PaymentMode" data-validation="required">
                                        <option value="">Select Option</option>
                                        <option value="0" <?php echo @$data['payment_type'] == "0" ? "selected" : ""; ?>>Offline</option>
                                        <option value="1" <?php echo @$data['payment_type'] == "1" ? "selected" : ""; ?>>Online</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paymentStatus">Payment Status <small style="color: red;"><b>*</b></small></label>
                                    <select class="form-control" name="paymentStatus" id="paymentStatus" data-validation="required">
                                        <option value="">Select Option</option>
                                        <option value="0" <?php echo @$data['payment_status'] == "0" ? "selected" : ""; ?>>Pending</option>
                                        <option value="1" <?php echo @$data['payment_status'] == "1" ? "selected" : ""; ?>>Paid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <small style="color: red;"><b>*</b></small></label>
                                    <select class="form-control" name="status" id="status" data-validation="required">
                                        <option value="">Select Option</option>
                                        <option value="0" <?php echo @$data['status'] == "0" ? "selected" : ""; ?>>Pending</option>
                                        <option value="1" <?php echo @$data['status'] == "1" ? "selected" : ""; ?>>Approved</option>
                                        <option value="2" <?php echo @$data['status'] == "2" ? "selected" : ""; ?>>Renewed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="remark">Remark <small style="color: red;"><b>*</b></small></label>
                                        <textarea class="form-control" id="remark" name="remark" data-validation="required"><?php echo @$data['remark']; ?></textarea>
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
		 addDecimalValidationEvent("amount");
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



if(isset($_POST['id']))
{
	$id = $_REQUEST['id'];
	$amount = $_REQUEST['amount'];
	$PaymentMode = $_REQUEST['PaymentMode'];
	$paymentStatus = $_REQUEST['paymentStatus'];
	$status = $_REQUEST['status'];
	$remark = $_REQUEST['remark'];
    $transactionId= mt_rand(100000000, 999999999);

	$sql = "UPDATE `bookings` SET 
					`amount` = '$amount',
                    `payment_type` = '$PaymentMode',
                    `payment_status` = '$paymentStatus',
                    `status` = '$status',
                    `remark` = '$remark',
                    transaction_id='$transactionId' 
					where id='$id' ";
					$message = "Pass Information is Updated Successfully.";
	echo $sql;	
	if(insert_update_delete_data($sql) == true)
	{
		$msg = getSwalMessgage("$message","",
			'window.location = "bookings.php"','window.location = "bookings.php"',"success");
			runJavascript($msg);
	}
	else
	{
		runJavascript('swal("SM !!!.", "", "error")');
	}
}

?>
