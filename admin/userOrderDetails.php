<?php include_once("userCheckLogin.php");
include_once("functions.php");
include_once("ui/user_layout.php");

$userId = (int)$_SESSION['userId'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Only your own orders (any order could be opened by changing the id before)
$rows = selectData("SELECT * FROM `sales` WHERE id='$id' AND user_id='$userId'");
if (!count($rows)) redirect_to('userOrders.php', 'Order not found.', 'error');
$o = $rows[0];
$items = selectData("SELECT * FROM `sales_items` WHERE sales_id='$id' ORDER BY id");

$code = (int)$o['status'];
$st = user_order_status($code);
$paid = $o['payment_status'] == '1';
$canceled = in_array($code, array(2, 3));
$itemsTotal = 0;
foreach ($items as $it) $itemsTotal += $it['total'];

// Progress: placed -> confirmed -> paid -> delivered
$steps = array(
    array('Placed',    'fa-cart-shopping', true),
    array('Confirmed', 'fa-thumbs-up',     in_array($code, array(1, 4)) || $paid),
    array('Paid',      'fa-wallet',        $paid),
    array('Delivered', 'fa-truck',         $o['delivery_status'] == '1' || $code == 4),
);
if ($canceled) $steps = array(array('Placed', 'fa-cart-shopping', true), array($st[0], 'fa-ban', true));

user_start('orders', 'Order #' . $id, date('d M Y', strtotime($o['dt'])) . ' &middot; ' . pill($st[0], $st[1]), '', 'userOrders.php');
?>

<div class="two-col">
  <div class="stack">
    <div class="box">
      <div class="box-body">
        <div class="track <?php echo $canceled ? 'canceled' : ''; ?>" style="<?php echo $canceled ? 'grid-template-columns:1fr 1fr' : ''; ?>">
          <?php foreach ($steps as $s) { ?>
            <div class="track-step <?php echo $s[2] ? 'done' : ''; ?>"><i class="fa-solid <?php echo $s[1]; ?>"></i><?php echo e($s[0]); ?></div>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="box">
      <div class="box-head"><h2>Items</h2><span class="muted small"><?php echo count($items); ?> product<?php echo count($items) == 1 ? '' : 's'; ?></span></div>
      <div class="box-body items">
        <?php foreach ($items as $it) { ?>
          <div class="item-row">
            <a href="../productDetails.php?getData=<?php echo (int)$it['product_id']; ?>"><img src="upload_images/<?php echo e($it['product_image']); ?>" alt=""></a>
            <div><strong><?php echo e($it['product_name']); ?></strong><small><?php echo (int)$it['qty']; ?> &times; <?php echo money($it['price']); ?></small></div>
            <span class="amt"><?php echo money($it['total']); ?></span>
          </div>
        <?php } ?>
        <?php if (!count($items)) { ?><p class="muted small">No items saved for this order.</p><?php } ?>
        <dl class="kv" style="grid-template-columns:1fr;margin:8px 0 0;border-top:1px solid var(--line);padding-top:6px">
          <div><span>Items</span><b><?php echo money($itemsTotal); ?></b></div>
          <div><span>Delivery</span><b><?php echo money($o['delivery_changes']); ?></b></div>
          <div><span>Tax</span><b><?php echo money($o['tax']); ?></b></div>
          <div><span style="color:var(--ink);font-weight:800">Total</span><b style="font-size:18px"><?php echo money($o['total']); ?></b></div>
        </dl>
      </div>
    </div>
  </div>

  <div class="stack">
    <div class="box">
      <div class="box-head"><h2>Delivery &amp; payment</h2></div>
      <div class="box-body">
        <dl class="kv" style="grid-template-columns:1fr;margin:0">
          <div><span>Address</span><b><?php echo e($o['delivery_address']); ?></b></div>
          <div><span>Payment</span><b><?php echo $o['payment_mode'] == '0' ? 'Pay on delivery' : 'Online'; ?></b></div>
          <div><span>Payment status</span><b><?php echo pill($paid ? 'Paid' : 'Unpaid', $paid ? 'green' : 'amber'); ?></b></div>
          <div><span>Delivery</span><b><?php echo pill($o['delivery_status'] == '1' ? 'Delivered' : 'Not delivered yet', $o['delivery_status'] == '1' ? 'green' : 'gray'); ?></b></div>
        </dl>
      </div>
    </div>
    <div class="form-actions">
      <?php if ($paid) { ?>
        <a class="btn btn-primary" href="viewInvoice.php?InvoiceId=<?php echo $id; ?>"><i class="fa-solid fa-file-invoice"></i> View invoice</a>
      <?php } elseif (!$canceled && $code != 4) { ?>
        <a class="btn btn-primary" href="../payment/payment.php?txtId=<?php echo $id; ?>"><i class="fa-regular fa-credit-card"></i> Pay online</a>
      <?php } ?>
      <?php if (!$paid && $code == 0) { ?>
        <form method="POST" action="userOrders.php" data-confirm="Cancel order #<?php echo $id; ?>?" data-confirm-btn="Cancel order" style="flex:1 1 160px;display:flex">
          <input type="hidden" name="cancelId" value="<?php echo $id; ?>">
          <button class="btn btn-danger" type="submit" style="flex:1"><i class="fa-solid fa-ban"></i> Cancel order</button>
        </form>
      <?php } ?>
      <a class="btn btn-ghost" href="../contact.php"><i class="fa-regular fa-life-ring"></i> Need help?</a>
    </div>
  </div>
</div>

<?php user_end(); ?>
