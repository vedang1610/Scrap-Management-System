<?php

include_once('functions.php');
if(isset($_GET['user_id']) && isset($_GET['booking_id']))
{
     if(!empty($_GET['user_id']) && !empty($_GET['booking_id']))
     {
          $user_id = $_GET['user_id'];
          $booking_id = $_GET['booking_id'];
          
          $userData = selectData("SELECT * FROM `users` where id='$user_id'");
          $bookingData = selectData("SELECT * FROM `bookings` where id='$booking_id'");
        
          if(is_array($userData) && count($userData) >= 1 && is_array($userData) && count($userData) >= 1)
          {
              $userData = $userData[0];
              $bookingData = $bookingData[0];
              if($bookingData['payment_status'] == 0)
              {
                runJavascript("window.location = 'userBooking.php'");
              }

              $dob = date_format( date_create($bookingData['dob']) ,"d/m/Y");
              $from_date = date_format( date_create($bookingData['from_date']) ,"d/m/Y");
              $to_date = date_format( date_create($bookingData['to_date']) ,"d/m/Y");
              $createdDate = date_format( date_create($bookingData['dt']) ,"d/m/Y");

              $gender = "";
              if($bookingData['gender'] == "0")
              {
                $gender = "Male";
              }
              else if($bookingData['gender'] == "1")
              {
                $gender = "Female";
              }
              else
              {
                $gender = "Other";
              }
          }
          else
          {
            runJavascript("window.location = 'userBooking.php'");
          }
     }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Invoice</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <script type="text/javascript">     
    function PrintDiv() {    
       var divToPrint = document.getElementById('divToPrint');
       var popupWin = window.open('', '_blank', 'width=500,height=500');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- Table row -->
    <div class="row" id="divToPrint">
      <div class="col-12 table-responsive">
        <!-- <table class="table table-striped"> -->
        <table border="1" class="table table-bordered">
          <tbody>
              <tr align="center">
                <td colspan="6" style="font-size:20px;color:blue">
                    Pass ID: 286529906
                </td>
              </tr>
              <tr>
                <th scope="">Name</th>
                <td colspan="3"><?php echo $bookingData['name']; ?></td>
              </tr>
              <tr>
                <th scope="">Father Name</th>
                <td colspan="3"><?php echo $bookingData['fname']; ?></td>
              </tr>
              <tr>
                <th scope="">Photo</th>
                <td colspan="3"><img src="upload_images/<?php echo $bookingData['photo']; ?>" width="50" height="50"></td>
              </tr>
              <tr>
                <th scope="">Mobile Number</th>
                <td><?php echo $bookingData['phone']; ?></td>
                <th scope="">Email</th>
                <td><?php echo $bookingData['email']; ?></td>
              </tr>
              <tr>
                <th scope="">Gender</th>
                <td><?php echo $gender; ?></td>
                <th scope="">Aadhar Card Number</th>
                <td><?php echo $bookingData['aadhar_card_no']; ?></td>
              </tr>
              <tr>
                <th scope="">Source</th>
                <td><?php echo $bookingData['source_from']; ?></td>
                <th scope="">Destination</th>
                <td><?php echo $bookingData['source_to']; ?></td>
              </tr>
              <tr>
                <th scope="">From Date</th>
                <td><?php echo $from_date; ?></td>
                <th scope="">To Date</th>
                <td><?php echo $to_date; ?></td>
              </tr>
              <tr>
                <th scope="">Cost</th>
                <td><?php echo $bookingData['amount']; ?></td>
                <th scope="">Pass Creation Date</th>
                <td><?php echo $createdDate; ?></td>
              </tr>
          </tbody>
        </table>
        
      </div>
      <!-- /.col -->
    </div>
    <p style="text-align: center;font-size: 20px;color: red">
  <input type="button" value="print" onclick="PrintDiv();" /></p>
    <!-- /.row -->
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
//   window.addEventListener("load", window.print());
</script>
</body>
</html>
