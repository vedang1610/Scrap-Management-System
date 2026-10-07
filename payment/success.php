<?php 
include_once("../admin/userCheckLogin.php"); 
include_once("../admin/functions.php");
// If transaction data is available in the URL
// print_r($_GET);
// exit;
// print_r($_GET);
// echo "<br/>";
// print_r($_GET);
// exit;

if(isset($_GET['txtId'])){


$txtId        =   $_GET['txtId'];
$transactionId    =   $_GET['orderId'];
// $name           =   $_GET['name'];
// $phone          =   $_GET['phone'];
// $email          =   $_GET['email'];
// $amount         =   $_GET['amount'];
// $donerId        =   $_GET['donerId'];
// $YName        =   $_GET['YName'];
// $Purpose        =   $_GET['Purpose'];

$dt = date("Y-m-d");
// $query="INSERT INTO `orders`(`donerId`, `benifiterId`, `orderId`, `name`, `phone`, `email`, `amount`, `orderStatus`, `dt`, `donerName`, `purpose`) 
//         VALUES ('$donerId','$benifiterId','$donerId','$name','$phone','$email','$amount','true','$dt','$YName','$Purpose')";
$query = "update sales set payment_status='1', transaction_id='$transactionId', payment_mode='1', status='1' where id='$txtId'";
mysqli_query($con,$query);
// print_r($_REQUEST);
// exit;
// echo $query;
}
/*
if(!empty($_GET['item_number']) && !empty($_GET['tx']) && !empty($_GET['amt']) && !empty($_GET['cc']) && !empty($_GET['st'])){ 
    // Get transaction information from URL 
    $item_number = $_GET['item_number'];  
    $txn_id = $_GET['tx']; 
    $payment_gross = $_GET['amt']; 
    $currency_code = $_GET['cc']; 
    $payment_status = $_GET['st']; 
     
    // Get product info from the database 
    $productResult = $db->query("SELECT * FROM products WHERE id = ".$item_number); 
    $productRow = $productResult->fetch_assoc(); 
     
    // Check if transaction data exists with the same TXN ID. 
    $prevPaymentResult = $db->query("SELECT * FROM payments WHERE txn_id = '".$txn_id."'"); 
 
    if($prevPaymentResult->num_rows > 0){ 
        $paymentRow = $prevPaymentResult->fetch_assoc(); 
        $payment_id = $paymentRow['payment_id']; 
        $payment_gross = $paymentRow['payment_gross']; 
        $payment_status = $paymentRow['payment_status']; 
    }else{ 
        // Insert tansaction data into the database 
        $insert = $db->query("INSERT INTO payments(item_number,txn_id,payment_gross,currency_code,payment_status) VALUES('".$item_number."','".$txn_id."','".$payment_gross."','".$currency_code."','".$payment_status."')"); 
        $payment_id = $db->insert_id; 
    } 
} 
*/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Order Confirmed</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet"> </head>

<body>
    <div class="site-wrapper animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <!--header starts-->
        <header id="header" class="header-scroll top-header headrom">
            <!-- .navbar -->
          <?php //include_once('header.php');?>
            <!-- /.navbar -->
        </header>
        <div class="page-wrapper">
            <!-- top Links -->
            <div class="top-links">
                
            </div>
            <!-- end:Top links -->
            <!-- start: Inner page hero -->
            <!-- <section class="inner-page-hero bg-image" data-image-src="images/decouvrez-l-experience-food-d-airbnb.jpg">
                <div class="profile">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12  col-md-4 col-lg-4 profile-img">
                                <div class="image-wrap">
                                    <figure><img src="images/decouvrez-l-experience-food-d-airbnb.jpg" alt="Profile Image"></figure>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 profile-desc">
                                <div class="pull-left right-text white-txt">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> -->
            <!-- end:Inner page hero -->
            <!-- <div class="breadcrumb">
                <div class="container">
                    <ul>
                        <li><a href="index.php" class="active">Home</a></li>
                        <li><a href="cart.php">Cart</a></li>
                        <li>Detail Cart</li>
                    </ul>
                </div>
            </div> -->
            <div class="container">
            <div class="response-text text-center" >
                        <h3>Your Payment is Successfully Done.</h3>
                        <a href="../admin/userOrders.php">
                        <h3>Back to Home.</h3>
                        </a>
            </div>
            </div>

                <!-- end:row -->
            </div>
            <!-- end:Container -->
          
            <!-- start: FOOTER -->
        <?php //include('includes/footer.php');?>
            <!-- end:Footer -->`
        </div>
        <!-- end:page wrapper -->
    </div>
    <!--/end:Site wrapper -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>

</html>

<script>
         setTimeout(function(){
            window.location.href = '../admin/userOrders.php';
         }, 3000);
      </script>