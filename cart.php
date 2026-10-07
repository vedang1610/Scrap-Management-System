<?php
session_start();
include('admin/db.php');
include('admin/functions.php');
if(!isset($_SESSION['userId']))
{
    header('location:admin/userLogin.php');
    exit;
}

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$userId = (int)$_SESSION['userId'];
if(isset($_GET['addProduct']) && isset($_GET['qty']))
{
	$productId = (int)$_GET['addProduct'];
    $qty = max(1, (int)$_GET['qty']);

    $sqlSelect = selectData("SELECT * FROM cart WHERE user_id = '$userId' and product_id = '$productId' ");
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
    header('location:cart.php');
    exit;
}

$productsData = selectData("
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
if(!is_array($productsData)) $productsData = array();

$finalTotal = 0;
$itemCount = 0;
foreach ($productsData as $row) {
    $finalTotal += $row['price'] * $row['cartQty'];
    $itemCount += $row['cartQty'];
}
$shippingCharges = count($productsData) ? 50 : 0;

// Prefill the delivery address with the address from registration
$defaultAddress = '';
$userRow = selectData("SELECT address FROM users WHERE id = '$userId'");
if(is_array($userRow) && count($userRow)) $defaultAddress = $userRow[0]['address'];

$pageTitle = 'My Cart';
$activePage = 'cart';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('ui/head.php'); ?>
</head>
<body class="sms has-tabbar<?php echo count($productsData) ? ' has-actionbar' : ''; ?>">

    <?php include('ui/header.php'); ?>

    <section class="hero">
        <div class="wrap">
            <div class="crumbs"><a href="index.php">Home</a><i class="fa-solid fa-chevron-right"></i><span>Cart</span></div>
            <h1>My cart</h1>
            <p><?php echo $itemCount ? $itemCount . ' item' . ($itemCount == 1 ? '' : 's') . ' ready for checkout' : 'Your cart is waiting to be filled'; ?></p>
        </div>
    </section>

    <main class="wrap cart">
    <?php if (count($productsData)) { ?>
        <div class="cart-grid">
            <div>
                <form method="POST" id="cartForm">
                    <input type="hidden" name="buttonUpateCart" value="1">
                    <div class="cart-list">
                        <?php foreach ($productsData as $row) { ?>
                            <div class="cart-item">
                                <a href="productDetails.php?getData=<?php echo (int)$row['id']; ?>">
                                    <img src="admin/upload_images/<?php echo e($row['image']); ?>" alt="<?php echo e($row['name']); ?>">
                                </a>
                                <div>
                                    <div class="ci-top">
                                        <div>
                                            <div class="ci-name"><?php echo e($row['name']); ?></div>
                                            <div class="ci-unit">&#8377;<?php echo e($row['price']); ?> each</div>
                                        </div>
                                        <button type="button" class="ci-remove" data-remove aria-label="Remove <?php echo e($row['name']); ?>"><i class="fa-regular fa-trash-can"></i></button>
                                    </div>
                                    <div class="ci-bottom">
                                        <input type="hidden" name="productPrice[]" value="<?php echo e($row['price']); ?>">
                                        <input type="hidden" name="productId[]" value="<?php echo (int)$row['id']; ?>">
                                        <input type="hidden" name="cartId[]" value="<?php echo (int)$row['cartId']; ?>">
                                        <div class="stepper sm">
                                            <button type="button" data-step="-1" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
                                            <input type="number" name="productQty[]" min="1" max="50" value="<?php echo (int)$row['cartQty']; ?>" data-price="<?php echo e($row['price']); ?>" inputmode="numeric" aria-label="Quantity">
                                            <button type="button" data-step="1" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
                                        </div>
                                        <div class="ci-total" data-line-total>&#8377;<?php echo number_format($row['price'] * $row['cartQty'], 2, '.', ''); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </form>
                <div class="cart-tools" style="margin-top:12px">
                    <a href="scrap.php" class="btn btn-ghost btn-sm"><i class="fa-solid fa-plus"></i> Add more</a>
                    <span class="cart-pending" id="cartPending"><i class="fa-solid fa-spinner fa-spin"></i> Saving…</span>
                    <form method="POST" onsubmit="return confirm('Remove all items from your cart?');">
                        <button class="btn btn-danger-ghost btn-sm" name="buttonClearCart" value="1"><i class="fa-regular fa-trash-can"></i> Clear cart</button>
                    </form>
                </div>
            </div>

            <form id="checkoutForm" method="post" class="summary" data-validate novalidate>
                <h2>Order summary</h2>
                <div class="sum-row"><span>Subtotal</span><span id="sumSubtotal">&#8377;<?php echo number_format($finalTotal, 2, '.', ''); ?></span></div>
                <div class="sum-row"><span>Shipping</span><span>&#8377;<?php echo number_format($shippingCharges, 2, '.', ''); ?></span></div>
                <div class="sum-row total"><span>Total</span><span id="sumTotal">&#8377;<?php echo number_format($finalTotal + $shippingCharges, 2, '.', ''); ?></span></div>

                <div class="field" style="margin-top:16px">
                    <label for="address">Delivery address <span class="req">*</span></label>
                    <div class="control top"><i class="fa-solid fa-location-dot"></i>
                        <textarea class="input" id="address" name="address" rows="3" placeholder="House no, street, city, state" autocomplete="street-address" required><?php echo e($defaultAddress); ?></textarea>
                    </div>
                </div>

                <div class="field" style="margin-top:16px">
                    <label>Payment</label>
                    <div class="pay-options">
                        <label class="pay-option">
                            <input type="radio" name="paymentMode" value="1" checked>
                            <span><i class="fa-regular fa-credit-card"></i>Online<small>Pay now</small></span>
                        </label>
                        <label class="pay-option">
                            <input type="radio" name="paymentMode" value="0">
                            <span><i class="fa-solid fa-money-bill-wave"></i>Offline<small>Pay on delivery</small></span>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="total" value="<?php echo $finalTotal; ?>">
                <input type="hidden" name="shippingCharges" value="<?php echo $shippingCharges; ?>">
                <input type="hidden" name="grandTotal" value="<?php echo ($finalTotal + $shippingCharges); ?>">

                <button type="submit" class="btn btn-primary btn-block" id="buttonCheckout" style="margin-top:18px"><i class="fa-solid fa-lock"></i> Proceed to checkout</button>
            </form>
        </div>
    <?php } else { ?>
        <div class="grid">
            <div class="empty">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>Your cart is empty</h3>
                <p>Browse scrap products and add something you like.</p>
                <a href="scrap.php" class="btn btn-primary"><i class="fa-solid fa-recycle"></i> Browse scrap</a>
            </div>
        </div>
    <?php } ?>
    </main>

    <?php if (count($productsData)) { ?>
        <div class="actionbar">
            <div class="ab-price"><span>Total</span><strong id="barTotal">&#8377;<?php echo number_format($finalTotal + $shippingCharges, 2, '.', ''); ?></strong></div>
            <button type="button" class="btn btn-primary" onclick="goCheckout()">Checkout <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    <?php } ?>

    <?php include('ui/footer.php'); ?>

    <script src="assets/sms.js?v=<?php echo @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/sms.js'); ?>"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    (function () {
        var form = document.getElementById('cartForm');
        if (!form) return;
        var shipping = <?php echo (float)$shippingCharges; ?>;
        var saveTimer;

        function money(n) { return '₹' + n.toFixed(2); }

        // Recalculate line totals and the summary right away
        function recalc() {
            var sub = 0;
            form.querySelectorAll('.cart-item').forEach(function (item) {
                var input = item.querySelector('input[name="productQty[]"]');
                var line = Number(input.dataset.price) * Number(input.value);
                item.querySelector('[data-line-total]').textContent = money(line);
                sub += line;
            });
            document.getElementById('sumSubtotal').textContent = money(sub);
            document.getElementById('sumTotal').textContent = money(sub + shipping);
            document.getElementById('barTotal').textContent = money(sub + shipping);
        }

        // Save the cart a moment after the last change (the page reloads with fresh totals)
        function saveSoon(delay) {
            clearTimeout(saveTimer);
            document.querySelector('.cart-tools').classList.add('is-saving');
            document.getElementById('buttonCheckout').disabled = true;
            saveTimer = setTimeout(function () { form.submit(); }, delay);
        }

        form.addEventListener('change', function (e) {
            if (e.target.name === 'productQty[]') { recalc(); saveSoon(700); }
        });
        // Hide the bottom bar while the real checkout button is on screen
        var bar = document.querySelector('.actionbar');
        if (bar && 'IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) {
                bar.style.visibility = entries[0].isIntersecting ? 'hidden' : '';
            }).observe(document.getElementById('buttonCheckout'));
        }

        form.querySelectorAll('[data-remove]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.closest('.cart-item').remove();
                recalc();
                saveSoon(0);
            });
        });
    })();

    // Bottom bar button only brings the address & payment section into view;
    // the order is placed with the "Proceed to checkout" button inside it.
    function goCheckout() {
        var f = document.getElementById('checkoutForm');
        window.scrollTo({ top: window.scrollY + f.getBoundingClientRect().top - 80, behavior: 'smooth' });
        var address = document.getElementById('address');
        if (!address.value.trim()) setTimeout(function () { address.focus(); }, 400);
    }
    </script>

   </body>
</html>

<?php

if(isset($_POST['buttonUpateCart']))
{
    if(isset($_POST['productPrice']) && isset($_POST['productId']) && isset($_POST['cartId']) && isset($_POST['productQty']))
    {
        $productId = $_POST['productId'];
        $productQty = $_POST['productQty'];

        $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
        insert_update_delete_data($sqlDelete);

        $count = 0;
        foreach ($productId as $row)
        {
            $productIdRow = (int)$row;
            $qtyRow = max(1, min(50, (int)$productQty[$count]));

            $sql = "INSERT INTO `cart`(`user_id`, `product_id`, `qty`)
                            VALUES ('$userId','$productIdRow','$qtyRow')";
            insert_update_delete_data($sql);
            $count++;
        }
        runJavascript("window.location.replace('cart.php');");
    }
    else
    {
        $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
        insert_update_delete_data($sqlDelete);
        runJavascript("window.location.replace('cart.php');");
    }
}
else if(isset($_POST['buttonClearCart']))
{
    $sqlDelete = "DELETE FROM `cart` where user_id='$userId'";
    insert_update_delete_data($sqlDelete);
    runJavascript("window.location.replace('cart.php');");
}
else if(isset($_POST['address']))
{
    $address = mysqli_real_escape_string($con, trim($_POST['address']));
    $shippingCharges = (float)$_POST['shippingCharges'];
    $grandTotal = (float)$_POST['grandTotal'];
    $paymentMode = $_POST['paymentMode'] == '0' ? 0 : 1; // 0 = Offline, 1 = Online
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
            foreach ($productsData as $row)
            {
                $productId = $row['id'];
                $productName = mysqli_real_escape_string($con, $row['name']);
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
