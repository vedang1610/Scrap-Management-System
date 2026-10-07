<?php 
include_once("../admin/userCheckLogin.php"); 
include_once("../admin/functions.php");

/* 
 * PayPal and database configuration 
 */ 

if(isset($_REQUEST['txtId']))
{
    // exit;
// //getting Details
$id           =      (int)$_REQUEST['txtId'];
// Take the amount from the logged-in user's own unpaid order, not from the URL (it could be edited)
$orderRow = selectData("SELECT total FROM sales WHERE id='$id' AND user_id='" . (int)$_SESSION['userId'] . "' AND payment_status='0' AND status NOT IN ('2','3')");
if (!is_array($orderRow) || !count($orderRow)) { header('Location: ../admin/userOrders.php'); exit; }
// $name         =      $_GET['txtName'];
// $phone        =      $_GET['txtPhone'];
// $email        =      $_GET['txtEmail'];
$amount       =      $orderRow[0]['total'];
// $donerId       =      $_GET['txtDonerId'];
// $YName = "-";
// $Purpose = "-";
// if(isset($_GET['txtYName']))
// {
//     if(!empty($_GET['txtYName']))
//     {
//         $YName = $_GET['txtYName'];
//     }
// }
// if(isset($_GET['txtPurpose']))
// {
//     if(!empty($_GET['txtPurpose']))
//     {
//         $Purpose = $_GET['txtPurpose'];
//     }
// }

// print_r($_GET);
// exit;

// //genrating order number
$orderno= mt_rand(100000000, 999999999);


// PayPal configuration 
define('PAYPAL_ID', 'sb-45ekz1506764@business.example.com'); //Tarang Sir
// define('PAYPAL_ID', 'sb-ptzye6086884@business.example.com'); //Sumit
define('PAYPAL_SANDBOX', TRUE); //TRUE or FALSE 

// Build return links from the address this site is actually running on (was hard-coded to localhost:8080/nsp/scrap)
$baseURL = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$returnURL=$baseURL . "/success.php?";
$returnURL.="orderId={$orderno}&";
$returnURL.="txtId={$id}&";
$returnURL.="amount={$amount}&";

define('PAYPAL_RETURN_URL', $returnURL);
define('PAYPAL_CANCEL_URL', $baseURL . '/cancel.php');
define('PAYPAL_NOTIFY_URL', $baseURL . '/cancel.php');
define('PAYPAL_CURRENCY', 'USD'); 
 
// Database configuration 
 
 
// Change not required 
define('PAYPAL_URL', (PAYPAL_SANDBOX == true)?"https://www.sandbox.paypal.com/cgi-bin/webscr":"https://www.sandbox.paypal.com/cgi-bin/webscr");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body>


<h3 style="text-align: center"> Please do not refresh the page</h3>
   <!-- PayPal payment form for displaying the buy button -->
                <form action="<?php echo PAYPAL_URL; ?>" method="post" id="payment">
                    <!-- Identify your business so that you can collect the payments. -->
                    <input type="hidden" name="business" value="<?php echo PAYPAL_ID; ?>">
                    
                    <!-- Specify a Buy Now button. -->
                    <input type="hidden" name="cmd" value="_xclick">
                    
                    <!-- Specify details about the item that buyers will purchase. -->
                    <input type="hidden" name="item_name" value="<?php echo 'test';?>">
                    <input type="hidden" name="item_number" value="<?php echo 1;?>">
                    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                    <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
                    
                    <!-- Specify URLs -->
                    <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL ?>">
                    <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
                    <input type='hidden' name='notify_url' value="<?php echo PAYPAL_RETURN_URL; ?>">
                    
                    <!-- Display the payment button. -->
                    <input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" 
                    style="display: none;">
                </form>

 <script src="js/jquery.min.js"></script>
<script type="text/javascript">
    document.getElementById("payment").submit();
</script>
</body>
</html>
<?php
}
?>