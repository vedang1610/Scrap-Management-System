<?php
session_start();
include('admin/db.php');
include('admin/functions.php');
if(!isset($_SESSION['userId']))
{
    header('location:admin/userLogin.php');
}
    
$userId = isset($_SESSION['userId']);
if(isset($_GET['addProduct']) && isset($_GET['qty']))
{
	$productId = $_GET['addProduct'];
    $qty = $_GET['qty'];

    $sqlSelect = selectData("SELECT * FROM cart WHERE user_id = '$userId' and product_id = '$productId' ");
        // print_r($sqlSelect);
        // exit;
    if(is_array($sqlSelect) && count($sqlSelect) >= 1)
    {
        $sqlSelect = $sqlSelect[0];

        $selectId = $sqlSelect['id'];
        $selectProductId = $sqlSelect['product_id']; 
        $qty = ($sqlSelect['qty'] + $qty);
        $sql = "UPDATE `cart` SET `qty` = '$qty' where id='$selectId' and product_id='$selectProductId' ";
        insert_update_delete_data($sql);
    }
    else
    {   
        $sql = "INSERT INTO `cart`(`user_id`, `product_id`, `qty`) 
                        VALUES ('$userId','$productId','$qty')";
        insert_update_delete_data($sql);
    }
    runJavascript('window.location.replace("cart.php");');
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Daily Shop | Home</title>
      <!-- Font awesome -->
	  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/font-awesome.css" rel="stylesheet">
  <link href="css/flaticon.css" rel="stylesheet">
  <link href="css/slick-slider.css" rel="stylesheet">
  <link href="css/fancybox.css" rel="stylesheet">
  <link href="build/mediaelementplayer.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <link href="css/color.css" rel="stylesheet">
  <link href="css/responsive.css" rel="stylesheet">
	  
	  
	  
      <link href="css/c/font-awesome.css" rel="stylesheet">
      <!-- Bootstrap -->
      <link href="css/c/bootstrap.css" rel="stylesheet">
      <!-- SmartMenus jQuery Bootstrap Addon CSS -->
      <link href="css/c/jquery.smartmenus.bootstrap.css" rel="stylesheet">
      <!-- Product view slider -->
      <link rel="stylesheet" type="text/css" href="css/c/jquery.simpleLens.css">
      <!-- slick slider -->
      <link rel="stylesheet" type="text/css" href="css/c/slick.css">
      <!-- price picker slider -->
      <link rel="stylesheet" type="text/css" href="css/c/nouislider.css">
      <!-- Theme color -->
      <link id="switcher" href="css/c/theme-color/default-theme.css" rel="stylesheet">
      <!-- <link id="switcher" href="css/theme-color/bridge-theme.css" rel="stylesheet"> -->
      <!-- Top Slider CSS -->
      <link href="css/c/sequence-theme.modern-slide-in.css" rel="stylesheet" media="all">
      <!-- Main style sheet -->
      <link href="css/c/style.css" rel="stylesheet">
      <!-- Google Font -->
      <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
      <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
   <body>
      <!-- wpf loader Two 
      <div id="wpf-loader-two">
         <div class="wpf-loader-two-inner">
            <span>Loading</span>
         </div>
      </div> -->
      <!-- / wpf loader Two -->       
      <!-- SCROLL TOP BUTTON -->
      <a class="scrollToTop" href="#"><i class="fa fa-chevron-up"></i></a>
      <!-- END SCROLL TOP BUTTON -->
      <!-- Include Header -->
      <?php include("header.php"); ?>
      <!-- page header banner section -->
      <!-- <section id="aa-catg-head-banner">
         <img src="img/fashion/fashion-header-bg-8.jpg" alt="fashion img">
         <div class="aa-catg-head-banner-area">
            <div class="container">
               <div class="aa-catg-head-banner-content">
                  <h2>Fashion</h2>
                  <ol class="breadcrumb">
                     <li><a href="index.html">Home</a></li>
                     <li class="active">Women</li>
                  </ol>
               </div>
            </div>
         </div>
      </section> -->
      
         <!-- Cart view section -->
        <section id="cart-view">
        <div class="container">
            <div class="row">
            <div class="col-md-12">
                <div class="cart-view-area">
                <div class="cart-view-table">
                    <form method="POST">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php 
                                    $productsData =   selectData("
                                                                    SELECT 
                                                                    product.* ,
                                                                    cart.qty as 'cartQty',
                                                                    cart.id as 'cartId',
                                                                    cart.user_id as 'userId'
                                                                    FROM `cart` cart
                                                                    inner join products product on product.id = cart.product_id 
                                                                    where 
                                                                    cart.user_id = '$userId'
                                                                ");
                                    $finalTotal = 0;
                                    $shippingCharges = 50;
                                    $rowCount = 1;
                                    if(is_array($productsData) && count($productsData) >= 1)
                                    {
                                        foreach ($productsData as $row) 
                                        {
                                            $finalTotal = ($finalTotal + $row['price'] * $row['cartQty']);
                                            ?>

                                            <tr>
                                                <td><?php echo $rowCount; ?></td>
                                                <td class="product-thumbnail">
                                                    <img class="img-responsive" src="admin/upload_images/<?php echo $row['image']; ?>" alt="" />
                                                </td>
                                                <td class="product-name"><?php echo $row['name']; ?></td>
                                                <td class="product-price-cart">
                                                    <input type="hidden" name="productPrice[]" value="<?php echo $row['price']; ?>" />
                                                    <span class="amount">Rs. <?php echo $row['price']; ?></span>
                                                </td>
                                                <td class="">
                                                    <div class="">
                                                        <input type="hidden" name="productId[]" value="<?php echo $row['id']; ?>" />
                                                        <input type="hidden" name="cartId[]" value="<?php echo $row['cartId']; ?>" />
                                                        <input class="form-control" type="number" min="1" max="50" name="productQty[]" onchange="qtyChange(this)" data-productId="<?php echo $row['id']; ?>" data-productPrice="<?php echo $row['price']; ?>" data-cartId="<?php echo $row['cartId']; ?>" value="<?php echo $row['cartQty']; ?>" />
                                                    </div>
                                                </td>
                                                <td class="product-subtotal" id="priceText-<?php echo $row['cartId']; ?>">Rs. <?php echo ( $row['price'] * $row['cartQty'] ); ?></td>
                                                <td class="product-remove">
                                                    <!-- <a href="#"><i class="fa fa-pencil-alt"></i></a> -->
                                                    <button type="button" onclick="javascript: $(this).closest('tr').remove();">
                                                        <i class="fa fa-times"></i></a>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php
                                            $rowCount++;
                                        }
                                    }
                                    ?>

                                    <?php
                                        if(count($productsData) <= 0)
                                        {
                                            $shippingCharges = 0;
                                            ?>
                                                <tr>
                                                    <td colspan="7">
                                                        No Items in Cart !!!
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <tr>
                                                    <td colspan="7" class="aa-cart-view-bottom">
                                                        <div class="aa-cart-coupon">

                                                            <!-- <input class="aa-coupon-code" type="text" placeholder="Coupon">
                                                            <input class="aa-cart-view-btn" type="submit" value="Apply Coupon"> -->
                                                            
                                                            <button class="aa-cart-view-btn" name="buttonClearCart">Clear Shopping Cart</button>
															
                                                        </div>
                                                        <!-- <input class="aa-cart-view-btn" type="submit" value="Update Cart"> -->
														
                                                        <button class="aa-cart-view-btn" name="buttonUpateCart">Update Shopping Cart</button>
                                                    </td>
                                                </tr>
												<a href="scrap.php" class="aa-cart-view-btn">Add More </a>
                                            <?php
                                        }
                                        ?>
                            <!-- <tr>
                                <td><a class="remove" href="#"><fa class="fa fa-close"></fa></a></td>
                                <td><a href="#"><img src="img/man/polo-shirt-2.png" alt="img"></a></td>
                                <td><a class="aa-cart-title" href="#">Polo T-Shirt</a></td>
                                <td>$150</td>
                                <td><input class="aa-cart-quantity" type="number" value="1"></td>
                                <td>$150</td>
                            </tr>
                            <tr>
                                <td><a class="remove" href="#"><fa class="fa fa-close"></fa></a></td>
                                <td><a href="#"><img src="img/man/polo-shirt-3.png" alt="img"></a></td>
                                <td><a class="aa-cart-title" href="#">Polo T-Shirt</a></td>
                                <td>$50</td>
                                <td><input class="aa-cart-quantity" type="number" value="1"></td>
                                <td>$50</td>
                            </tr> -->
                            </tbody>
                        </table>
                        </div>
                    </form>
                    <!-- Cart Total view -->
                    <?php
                    if(count($productsData) >= 1)
                    {
                        ?>
                        <form id="checkoutForm" method="post">
                            <div class="cart-view-total">
                                <h4>Cart Totals</h4>
                                <table class="aa-totals-table">
                                    <tbody>
                                    <tr>
                                        <th>Subtotal</th>
                                        <td>Rs. <?php echo $finalTotal; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Shipping Charges</th>
                                        <td>Rs. <?php echo $shippingCharges; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Grand Total</th>
                                        <td>Rs. <?php echo ($finalTotal + $shippingCharges); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Grand Total</th>
                                        <td>
                                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                                <input type="hidden" name="total" value="<?php echo $finalTotal; ?>">
                                                <input type="hidden" name="shippingCharges" value="<?php echo $shippingCharges; ?>">
                                                <input type="hidden" name="grandTotal" value="<?php echo ($finalTotal + $shippingCharges); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Checkout</th>
                                        <td>
                                            <input class="form-check-input" type="radio" name="paymentMode" id="paymentOffline" value="0">
                                            <label class="form-check-label" for="paymentOffline">
                                                Offline
                                            </label>
                                            <br>

                                            <input class="form-check-input" type="radio" name="paymentMode" id="paymentOnline" value="1" checked>
                                            <label class="form-check-label" for="paymentOnline">
                                                Online
                                            </label>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <a href="javascript:void(0);" class="aa-cart-view-btn" onclick="submitform()" id="buttonCheckout">Proceed to Checkout</a>
                                <!-- <a href="#" class="aa-cart-view-btn">Proced to Checkout</a> -->
                            </div>
                        </form>
                    <?php
                        }
                        ?> 
                </div>
                </div>
            </div>
            </div>
        </div>
        </section>
 <!-- / Cart view section -->

      <!-- Subscribe section -->
      <!-- <section id="aa-subscribe">
         <div class="container">
           <div class="row">
             <div class="col-md-12">
               <div class="aa-subscribe-area">
                 <h3>Subscribe our newsletter </h3>
                 <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex, velit!</p>
                 <form action="" class="aa-subscribe-form">
                   <input type="email" name="" id="" placeholder="Enter your Email">
                   <input type="submit" value="Subscribe">
                 </form>
               </div>
             </div>
           </div>
         </div>
         </section> -->
      <!-- / Subscribe section -->
      <!-- Inlcude Footer -->
      <?php include("footer.php"); ?> 
      <!-- jQuery library -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="js/bootstrap.js"></script>  
      <!-- SmartMenus jQuery plugin -->
      <script type="text/javascript" src="js/jquery.smartmenus.js"></script>
      <!-- SmartMenus jQuery Bootstrap Addon -->
      <script type="text/javascript" src="js/jquery.smartmenus.bootstrap.js"></script>  
      <!-- To Slider JS -->
      <script src="js/sequence.js"></script>
      <script src="js/sequence-theme.modern-slide-in.js"></script>  
      <!-- Product view slider -->
      <script type="text/javascript" src="js/jquery.simpleGallery.js"></script>
      <script type="text/javascript" src="js/jquery.simpleLens.js"></script>
      <!-- slick slider -->
      <script type="text/javascript" src="js/slick.js"></script>
      <!-- Price picker slider -->
      <script type="text/javascript" src="js/nouislider.js"></script>
      <!-- Custom js -->
      <script src="js/custom.js"></script> 

        <!-- SweetAlert -->
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

      <script>
        addDecimalValidationEvent2("qty");

        function qtyChange(textBox)
        {
            var qty = textBox.value;
            var productId = textBox.getAttribute("data-productId");
            var productPrice = textBox.getAttribute("data-productPrice");
            var cartId = textBox.getAttribute("data-cartId");

            var totalText = document.getElementById("priceText-"+cartId);

            var total = (productPrice * qty);
            totalText.innerHTML = "Rs. "+total;

            // console.log(totalText.innerHTML);
            // console.log("qty = "+ qty);
            // console.log("ProductId = "+ productId);
            // console.log("productPrice = "+ productPrice);
            // console.log("cartId = "+ cartId);
        }

        function submitform()
        {
            var address = $("#address").val();
            if($.trim(address) == "")
            {
                alert("First fill Address.");
                $("#address").focus();
            }
            else
            {
                document.getElementById('checkoutForm').submit();
            }
        }

        function addCard(id)
        {
            var qty = $("#qty").val();
            if(isNaN(qty) || qty <= 0)
            {
                swal("Qty Should be Number and grater than 1.","","error");
            }
            else
            {
                var url = "cart.php?addProduct="+id+"&qty="+qty;
                window.location=url;
            }
        }
        </script>

   </body>
</html>

<?php

if(isset($_POST['buttonUpateCart']))
{
    if(isset($_POST['productPrice']) && isset($_POST['productId']) && isset($_POST['cartId']) && isset($_POST['productQty']))
    {
        $userId = isset($_SESSION['userId']);
    
        $productPrice = $_POST['productPrice'];
        $productId = $_POST['productId'];
        $cartId = $_POST['cartId'];
        $productQty = $_POST['productQty'];
        

        $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
        insert_update_delete_data($sqlDelete);

        $count = 0;
        foreach ($productId as $row) 
        {
            $productIdRow = $row;
            $qtyRow = $productQty[$count];
            $cartIdRow = $cartId[$count];

            $sql = "INSERT INTO `cart`(`user_id`, `product_id`, `qty`) 
                            VALUES ('$userId','$productIdRow','$qtyRow')";
            insert_update_delete_data($sql);
            $count++;
        }
        $msg = getSwalMessgage("Cart updated Successfully.","",'window.location = "cart.php"','window.location = "cart.php"',"success");
        runJavascript($msg);
    }
    else
    {
        $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
        insert_update_delete_data($sqlDelete);
        runJavascript("window.location='cart.php';");
    }
}
else if(isset($_POST['buttonClearCart']))
{
    $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
    insert_update_delete_data($sqlDelete);
    runJavascript("window.location='cart.php';");
}
else if(isset($_POST['address']))
{
    $userId = isset($_SESSION['userId']);
    $address = $_POST['address'];
    $total = $_POST['total'];
    $shippingCharges = $_POST['shippingCharges'];
    $grandTotal = $_POST['grandTotal'];
    $paymentMode = $_POST['paymentMode']; // 0 = Offline, 1 = Online
    $dt = date("Y-m-d");

    $salesSql = "INSERT INTO `sales`(`user_id`, `payment_mode`, `payment_status`, `delivery_status`, `delivery_address`, `delivery_changes`, `tax`, `total`, 
                                    `status`, `dt`) 
                                    VALUES ('$userId','$paymentMode','0','0','$address','$shippingCharges','0','$grandTotal','0','$dt')"; 
    insert_update_delete_data($salesSql);

    $salesData = selectData("SELECT * FROM `sales` where user_id='$userId' order by id desc limit 1 ");
    if(is_array($salesData) && count($salesData) >= 1)
    {
        $salesData = $salesData[0];
        $saledId = $salesData['id'];

        $productsData =   selectData("
                                    SELECT 
                                    product.* ,
                                    cart.qty as 'cartQty',
                                    cart.id as 'cartId',
                                    cart.user_id as 'userId'
                                    FROM `cart` cart
                                    inner join products product on product.id = cart.product_id 
                                    where 
                                    cart.user_id = '$userId'
                                ");
        if(is_array($productsData) && count($productsData) >= 1)
        {
            // print_r($productsData);
            // exit;
            foreach ($productsData as $row)   
            {
                $productId = $row['id'];
                $productName = $row['name'];
                $productImage = $row['image'];
                $productPrice = $row['price'];
                $qty = $row['cartQty'];

                $qtyTotal = ($productPrice * $qty);

                $salesSql = "INSERT INTO `sales_items`(`sales_id`, `product_id`, `product_name`, `product_image`, `qty`, `price`, `total`) 
                                               VALUES ('$saledId','$productId','$productName','$productImage','$qty','$productPrice','$qtyTotal')"; 
                insert_update_delete_data($salesSql);  
            }

            insert_update_delete_data("DELETE FROM cart where user_id='$userId'");
            if($paymentMode == 0) // Offline
            {
                $msg = getSwalMessgage("Your Order Saved Successfully.","",'window.location = "index.php"','window.location = "index.php"',"success");
                runJavascript($msg);
            }
            else if($paymentMode == 1) // Online
            {
                $link = "window.location = 'payment/payment.php?txtId=$saledId&txtAmount=$grandTotal'";
                runJavascript($link);
            }
        }
    }
}


?>
