<?php include_once("checkLogin.php"); 
include_once("functions.php");

if(isset($_GET['cancelId']))
{
    $id = $_GET['cancelId'];
    $sql = "DELETE from `bookings` where id='$id' ";
    if(insert_update_delete_data($sql) == true)
    {
		    runJavascript("alert('Pass Booking Canceled Successfully.'); window.location = 'userBooking.php'");
    }
    else
    {
		    runJavascript("alert('Error !!!'); window.location = 'userBooking.php'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <!-- Functions JS -->
  <script src="functions.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

<!-- Include Header -->
<?php $he = 4; include_once("header.php") ?>
<!-- Include Header End -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Pass Booking</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Manage Pass Booking</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card">
          <!-- <div class="card-header"> -->
            <!-- <h3 class="card-title">Add And Manage Admin Users</h3> -->
            <!-- <br> -->
            <!-- <a href="addUserBooking.php"><button type="button" id="btnAdd" class="btn btn-block btn-success">New Pass Booking</button></a> -->
          <!-- </div> -->
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Pass Number</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <!-- <th>Email</th> -->
                  <th>Source From</th>
                  <th>Source To</th>
                  <th>From Date</th>
                  <th>To Date</th>
                  <th>Status</th>
                  <th>Amount To Paid</th>
                  <th>Payment Status</th>
			            <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="tableBody">
                  <?php 
        // $userId = $_SESSION['userId'];
				$data = selectData("SELECT * FROM `bookings` order by id desc");
				if(is_array($data) && count($data) >= 1)
				{
				$no = 1;
			
				foreach($data as $value)
				{
					$date=date_create($value['dt']);

          $action = "";

          

          if($value['status'] == 0)
          {
              $action .= '<a href="reviewUserBooking.php?getData='.$value['id'].'">
                <button type="button" onclick="" class="btn btn-success">Review</button>
              </a>';
              $action .= '<form action="userBooking.php?cancelId='.$value['id'].'" method="POST">
              <input type="submit" class="btn btn-danger" value="Cancel" onclick="'."return confirm('Are you sure to cancel it ?');".'">
            </form>';
          }
          
          if($value['payment_status'] == 1)
          {
              $action .= '<a href="viewPass.php?user_id='.$value['user_id'].'&booking_id='.$value['id'].'" target="_blank">
              <button type="button" onclick="" class="btn btn-info" title="View Pass">View</button>
            </a>';
          }
          

					echo "<tr>";
					echo "<td>{$no}</td>
					<td>".$value['pass_number']."</td>
          <td>".$value['name']."</td>
          <td>".$value['phone']."</td>
         
          <td>".$value['source_from']."</td>
          <td>".$value['source_to']."</td>
          <td>".$value['from_date']."</td>
          <td>".$value['to_date']."</td>
          <td>".($value['status'] == "0" ? "Pending" : "Approved")."</td>
          <td>".($value['amount'] <= 0 ? "Pending" : $value['amount'])."</td>
          <td>".($value['payment_status'] == "0" ? "Pending" :"Paid")."</td>
					<td>".date_format($date,"d/m/Y")."</td>
					<td>".'
					
					'.$action.'

          </td>';
					echo "</tr>";
					$no ++;
				}
			
				}
				else{
				echo "<tr>No Data Found...</tr>";
				}

                  ?>
                </tbody>
              <tfoot>
                <tr>
                <th>No</th>
                  <th>Pass Number</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Source From</th>
                  <th>Source To</th>
                  <th>From Date</th>
                  <th>To Date</th>
                  <th>Status</th>
                  <th>Amount To Paid</th>
                  <th>Payment Status</th>
			            <th>Date</th>
                  <th>Action</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

    <!-- Modal -->
    

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
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
     //  "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false,
    //   "responsive": true,
    // });
  });
</script>


<!-- Validation -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
    $.validate();
</script>

<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>
</html>
