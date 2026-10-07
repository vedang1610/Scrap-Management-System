<?php include_once("checkLogin.php"); 
include_once("functions.php");

if(isset($_GET['deleteId']))
{
    $id = $_GET['deleteId'];
    $sql = "DELETE from `products` where id='$id' ";
    if(insert_update_delete_data($sql) == true)
    {
		    runJavascript("alert('Package Deleted Successfully.'); window.location = 'products.php'");
    }
    else
    {
		    runJavascript("alert('Error !!!'); window.location = 'products.php'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products</title>

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
            <h1>Manage Packages</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Manage Packages</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="card">
          <div class="card-header">
            <!-- <h3 class="card-title">Add And Manage Admin Users</h3>
            <br> -->
            <a href="addProducts.php"><button type="button" id="btnAdd" class="btn btn-block btn-success">Add Package</button></a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <!-- <th>Category</th> -->
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Product Images</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody id="tableBody">
                  <?php 
                // $userId = $_SESSION['userId'];
				$data = selectData("SELECT * FROM `products` order by id desc");
				if(is_array($data) && count($data) >= 1)
				{
          $no = 1;
        
          foreach($data as $value)
          {
            // $categoryId = $value['category_id'];
            // $category = selectData("select * from category where id='$categoryId'");

            $productId = $value['id'];
            $productImages = selectData("select count(*) as imagesCount from product_images where product_id='$productId'");
            $imagesCount = 0;
            if(is_array($productImages) && count($productImages) >= 1)
            {
              $imagesCount = $productImages[0]['imagesCount'];
            }
            
            $date=date_create($value['dt']);

                      $action = "";
                      
                      $action .= '<a href="addProducts.php?getData='.$value['id'].'">
                      <button type="button" onclick="" class="btn btn-success">Edit</button>
                      </a>';
                      $action .= '<form action="products.php?deleteId='.$value['id'].'" method="POST">
                      <input type="submit" class="btn btn-danger" value="Delete" onclick="'."return confirm('Are you sure to delete it ?');".'">
                      </form>';

                      $productImagesAction = '<a href="productImages.php?getData='.$value['id'].'">
                      View Images
                      </a>';
                      
              // $category = $category[0];
              // $CategoryName = $category['name'];
              echo "<tr>";
                    echo "<td>{$no}</td>"
                          ."<td>".$value['name']."</td>"
                          ."<td>".$value['description']."</td>"
                          ."<td>".$value['price']."</td>"
                          ."<td><a href='upload_images/".$value['image']."' target='_blank'>View Image</a></td>"
                          ."<td>".($imagesCount >= 1 ? $productImagesAction : "No Images Available")."</td>"
                          ."<td>".($value['status'] == 0 ? "Not Active" : "Active")."</td>"
                          ."<td>".date_format($date,"d/m/Y")."</td>"
                          ."<td>".$action."</td>"
                          ;
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Product Images</th>
                    <th>Status</th>
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
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
    $.validate();
</script> -->

<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>
</html>
