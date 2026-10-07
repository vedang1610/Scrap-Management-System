<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['btnUpdateOrder']))
{
    $payment_status  = $_POST['payment_status'] == '1' ? 1 : 0;
    $delivery_status = $_POST['delivery_status'] == '1' ? 1 : 0;
    $status = max(0, min(4, (int)$_POST['status']));
    $ok = insert_update_delete_data("UPDATE sales SET payment_status='$payment_status', delivery_status='$delivery_status', status='$status' WHERE id='$id'");
    redirect_to("adminOrderDetails.php?id=$id", $ok ? 'Order updated successfully.' : 'Could not update the order.', $ok ? 'success' : 'error');
}

$salesData = selectData("SELECT * FROM `sales` WHERE id='$id'");
if (!is_array($salesData) || !count($salesData)) redirect_to('adminOrders.php', 'Order not found.', 'error');
$salesData = $salesData[0];

$userData = selectData("SELECT * FROM users WHERE id='" . (int)$salesData['user_id'] . "'");
$userData = (is_array($userData) && count($userData)) ? $userData[0] : null;

// Use what was saved with the order (name, image, price) so it stays right even if the product changes later
$items = selectData("SELECT si.*, p.description FROM `sales_items` si
                     LEFT JOIN products p ON p.id = si.product_id
                     WHERE si.sales_id='$id' ORDER BY si.id");

$st = order_status($salesData['status']);
$paid = $salesData['payment_status'] == '1';
$itemsTotal = 0;
foreach ($items as $it) $itemsTotal += $it['total'];

admin_start('orders', 'Order #' . $id, date('d M Y', strtotime($salesData['dt'])) . ' &middot; ' . pill($st[0], $st[1]), '', 'adminOrders.php');
?>

<div class="two-col">
  <div class="stack">
    <div class="box">
      <div class="box-head"><h2>Items</h2><span class="muted small"><?php echo count($items); ?> product<?php echo count($items) == 1 ? '' : 's'; ?></span></div>
      <div class="box-body items">
        <?php if (count($items)) { foreach ($items as $it) { ?>
          <div class="item-row">
            <img src="upload_images/<?php echo e($it['product_image']); ?>" alt="">
            <div><strong><?php echo e($it['product_name']); ?></strong>
              <small><?php echo (int)$it['qty']; ?> &times; <?php echo money($it['price']); ?><?php echo $it['description'] ? ' &middot; ' . e($it['description']) : ''; ?></small></div>
            <span class="amt"><?php echo money($it['total']); ?></span>
          </div>
        <?php } } else { ?>
          <p class="muted small">No items saved for this order.</p>
        <?php } ?>
        <dl class="kv" style="grid-template-columns:1fr;margin:8px 0 0;border-top:1px solid var(--line);padding-top:6px">
          <div><span>Items</span><b><?php echo money($itemsTotal); ?></b></div>
          <div><span>Delivery</span><b><?php echo money($salesData['delivery_changes']); ?></b></div>
          <div><span>Tax</span><b><?php echo money($salesData['tax']); ?></b></div>
          <div><span style="color:var(--ink);font-weight:800">Grand total</span><b style="font-size:18px"><?php echo money($salesData['total']); ?></b></div>
        </dl>
      </div>
    </div>

    <div class="box">
      <div class="box-head"><h2>Customer &amp; delivery</h2></div>
      <div class="box-body">
        <?php if ($userData) { ?>
          <div class="fb-top" style="margin-bottom:12px"><span class="avatar"><?php echo e(initials($userData['name'])); ?></span>
            <div><strong><?php echo e($userData['name']); ?></strong><small><?php echo e($userData['email']); ?></small></div></div>
          <div class="fb-links" style="margin-bottom:12px">
            <a class="btn btn-soft btn-xs" href="tel:<?php echo e($userData['phone']); ?>"><i class="fa-solid fa-phone"></i> <?php echo e($userData['phone']); ?></a>
            <a class="btn btn-soft btn-xs" href="mailto:<?php echo e($userData['email']); ?>"><i class="fa-regular fa-envelope"></i> Email</a>
          </div>
        <?php } else { ?>
          <p class="muted small" style="margin-bottom:12px">This user account was deleted.</p>
        <?php } ?>
        <dl class="kv" style="grid-template-columns:1fr;margin:0">
          <div><span>Address</span><b><?php echo e($salesData['delivery_address']); ?></b></div>
          <div><span>Payment mode</span><b><?php echo $salesData['payment_mode'] == '0' ? 'Offline (pay on delivery)' : 'Online'; ?></b></div>
        </dl>
      </div>
    </div>
  </div>

  <div class="box">
    <div class="box-head"><h2>Update order</h2></div>
    <form class="box-body stack" method="POST" data-validate novalidate>
      <div class="field">
        <label for="status">Order status</label>
        <div class="control"><i class="fa-solid fa-flag"></i>
          <select class="input" id="status" name="status">
            <?php foreach (array(0, 1, 4, 2, 3) as $code) { $o = order_status($code); ?>
              <option value="<?php echo $code; ?>" <?php echo $salesData['status'] == $code ? 'selected' : ''; ?>><?php echo $o[0]; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="payment_status">Payment status</label>
        <div class="control"><i class="fa-solid fa-wallet"></i>
          <select class="input" id="payment_status" name="payment_status">
            <option value="0" <?php echo !$paid ? 'selected' : ''; ?>>Pending</option>
            <option value="1" <?php echo $paid ? 'selected' : ''; ?>>Paid</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="delivery_status">Delivery status</label>
        <div class="control"><i class="fa-solid fa-truck"></i>
          <select class="input" id="delivery_status" name="delivery_status">
            <option value="0" <?php echo $salesData['delivery_status'] == '0' ? 'selected' : ''; ?>>Pending</option>
            <option value="1" <?php echo $salesData['delivery_status'] == '1' ? 'selected' : ''; ?>>Delivered</option>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" name="btnUpdateOrder" value="1" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save changes</button>
        <?php if ($paid) { ?>
          <a class="btn btn-ghost" href="viewInvoice.php?InvoiceId=<?php echo $id; ?>" target="_blank"><i class="fa-solid fa-file-invoice"></i> Invoice</a>
        <?php } ?>
      </div>
    </form>
  </div>
</div>

<?php admin_end(); ?>
