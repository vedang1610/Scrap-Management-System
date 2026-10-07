<?php include_once("userCheckLogin.php"); 
include_once("functions.php");

if(isset($_GET['id']))
{
	$id = $_GET['id'];
    $data = selectData("SELECT * FROM `bookings` where id='$id' order by id desc");
    if(is_array($data) && count($data) >= 1)
    {
	   
    }
    else
    {
		runJavascript("alert('Data not found !!!.'); window.location = 'bookings.php'");
    }
}

if(isset($_POST['txtId']))
{
    $bookingId = $_POST['txtId'];
    $staffId = $_POST['txtStaff'];
    $data = selectData("SELECT * FROM `staff` where id='$staffId'");
    if(is_array($data) && count($data) >= 1)
    {
        $data = $data[0];
        $staffName = $data['name'];
        $sql = "UPDATE `bookings` set delivery_status='1',staff_id='$staffId',staff_name='$staffName' where id='$bookingId' ";
        if(insert_update_delete_data($sql) == true)
        {
            runJavascript("alert('Order Assigned to Staff Successfully.'); window.location = 'bookings.php'");
        }
        else
        {
            runJavascript("alert('Error !!!.'); window.location = 'bookings.php'");
        }
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
						<h1>Assign Staff</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
							<!-- <li class="breadcrumb-item active">Admin Profile</li> -->
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
								<div class="col-md-12" >
											<div class="form-group">
													<label for="txtStaff">Select Staff <small style="color: red;"><b>*</b></small></label>
                                                    <input type="text" id="txtId" name="txtId" value="<?php echo @$id; ?>" data-validation="required" hidden>
													<select name="txtStaff" id="txtStaff" class="form-control" data-validation="required">
                                                        <option value="">Select Type</option>
                                                        <?php
                                                        $data = selectData("SELECT * FROM `staff`");
                                                        if(is_array($data) && count($data) >= 1)
                                                        {
                                                            foreach ($data as $value) 
                                                            {
                                                                echo "<option value='".$value['id']."'>".$value['name']."</option>";
                                                            }
                                                        }

                                                        ?>
													</select>
											</div>
								</div>
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
  $("#txtType").change(function(){
      var id = $(this).val();
      if(id != "")
      {

      }
      else
      {
            $("#txtName").val("");
            $("#txtPrice").val("");
            $("#txtDetails").val("");
      }
        $.ajax
        (
                {
                    type: 'GET',
                    url: 'getData.php',
                    data: 
                    {
                    getTypesById:0,
                    id:id
                },
                dataType: 'json',
                success: function (response) 
                {
                    // console.log(response);
                    
                    $("#txtName").val(response['name']);
                    $("#txtPrice").val(response['price']);
                    $("#txtDetails").val(response['details']);
                }
            }
        );
  });
});
</script>


<?php



if(isset($_POST['txtType']))
{
	$typeId = $_REQUEST['txtType'];
	$paymentMode = $_REQUEST['txtPaymentMode'];
	$price = $_REQUEST['txtPrice'];
	$type = $_REQUEST['txtType'];
	$details = $_REQUEST['txtDetails'];
    $userId = $_SESSION['userId'];

    $data = selectData("SELECT * FROM `stock` where id='$typeId'");
    if(is_array($data) && count($data) >= 1)
    {
        $data = $data[0];
        $stockName = $data['name'];
        $stockType = $data['type'];
        $stockDetails = $data['details'];
        $stockPrice = $data['price'];
        $stockStatus = $data['status'];
        $stockDt = $data['dt'];

        $deliveryStatus = "0";
        $paymentStatus = "0";

        
    }
    

	$message = "";
    $sql = "";
    
    $id = "";
	if(empty(trim($id)))
	{
		$sql = "INSERT INTO `bookings`(`user_id`, `stock_id`, `stock_name`, `stock_type`, `stock_details`, `stock_price`, `staff_id`, `staff_name`, `delivery_status`, `payment_type`, `payment_status`, `transaction_id`) 
        VALUES ('$userId','$typeId','$stockName','$stockType','$stockDetails','$stockPrice','0','-','0','$paymentMode','0','0')";
		
	}
	if(insert_update_delete_data($sql) == true)
	{
        if($paymentMode == 0) // Offline Payment
        {   
            $msg = getSwalMessgage("Your Booking is Successfully done, we will contact you for further details.","",'window.location = "userBooking.php"','window.location = "viewstock.php"',"success");
		    runJavascript($msg);
        }
        else if($paymentMode == 1) // Online Payment
        {
            $data = selectData("select * from bookings where user_id='$userId' order by id desc limit 1");
            if(is_array($data) && count($data) >= 1)
            {
                $data = $data[0];
                $link = "window.location = 'payment/payment.php?txtId=".$data['id']."'";
                runJavascript($link);
            }
        }
	}
	else
	{
		runJavascript('swal("Error !!!.", "", "error")');
	}
}

?>
