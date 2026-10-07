<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

// Admin cancels an unpaid pending order (status 2 = canceled by admin, 3 = canceled by the user)
if (isset($_POST['cancelId']))
{
    $id = (int)$_POST['cancelId'];
    $ok = insert_update_delete_data("UPDATE `sales` SET status='2' WHERE id='$id' AND status='0' AND payment_status='0'");
    redirect_to('adminOrders.php', $ok ? "Order #$id canceled." : 'Could not cancel the order.', $ok ? 'success' : 'error');
}

$today = isset($_GET['filterByTodayDate']);
$where = $today ? "WHERE s.dt = '" . date('Y-m-d') . "'" : '';
$orders = selectData("SELECT s.*, u.name AS user_name, u.phone AS user_phone,
                        (SELECT COUNT(*) FROM sales_items si WHERE si.sales_id = s.id) AS items
                      FROM `sales` s LEFT JOIN `users` u ON u.id = s.user_id
                      $where ORDER BY s.id DESC");

$groups = array('pending' => array(0), 'confirmed' => array(1), 'completed' => array(4), 'canceled' => array(2, 3));
$counts = array('all' => count($orders), 'pending' => 0, 'confirmed' => 0, 'completed' => 0, 'canceled' => 0);
foreach ($orders as $o) foreach ($groups as $g => $codes) if (in_array((int)$o['status'], $codes)) $counts[$g]++;
$startFilter = isset($_GET['status']) && isset($groups[$_GET['status']]) ? $_GET['status'] : 'all';

admin_start('orders', $today ? "Today's orders" : 'Orders', count($orders) . ' order' . (count($orders) == 1 ? '' : 's') . ($today ? ' &middot; <a href="adminOrders.php" style="color:var(--brand-dark);font-weight:700">Show all</a>' : ''));
?>

<div class="toolbar">
  <label class="searchbox"><i class="fa-solid fa-magnifying-glass"></i>
    <input type="search" placeholder="Search by order no, name, address" data-list-search="orders" aria-label="Search orders"></label>
  <div class="chips" data-list-filter="orders">
    <?php foreach (array('all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'canceled' => 'Canceled') as $k => $label) { ?>
      <a href="#" class="chip no-img <?php echo $startFilter == $k ? 'is-active' : ''; ?>" data-f="<?php echo $k; ?>"><?php echo $label; ?> <em><?php echo $counts[$k]; ?></em></a>
    <?php } ?>
  </div>
</div>

<div class="box table-box">
  <table class="dtable" data-list="orders">
    <thead><tr><th>Order</th><th>Phone</th><th>Items</th><th>Payment</th><th>Delivery</th><th>Status</th><th>Total</th><th></th></tr></thead>
    <tbody>
    <?php if (count($orders)) { foreach ($orders as $o) {
        $st = order_status($o['status']);
        $group = 'all';
        foreach ($groups as $g => $codes) if (in_array((int)$o['status'], $codes)) $group = $g;
        $paid = $o['payment_status'] == '1';
        $search = '#' . $o['id'] . ' ' . $o['user_name'] . ' ' . $o['user_phone'] . ' ' . $o['delivery_address'];
    ?>
      <tr data-search="<?php echo e($search); ?>" data-filter="<?php echo $group; ?>">
        <td class="td-main" data-label="Order">
          <div class="cell-main"><span class="f-icon" style="width:42px;height:42px;font-size:15px"><i class="fa-solid fa-receipt"></i></span>
            <div><strong>#<?php echo (int)$o['id']; ?> &middot; <?php echo e($o['user_name'] ?: 'Deleted user'); ?></strong>
            <small><?php echo date('d M Y', strtotime($o['dt'])); ?></small>
            <small class="clamp" style="max-width:240px"><?php echo e($o['delivery_address']); ?></small></div></div>
        </td>
        <td data-label="Phone"><?php echo e($o['user_phone'] ?: '-'); ?></td>
        <td data-label="Items"><?php echo (int)$o['items']; ?></td>
        <td data-label="Payment"><?php echo pill(($o['payment_mode'] == '0' ? 'Offline' : 'Online') . ' · ' . ($paid ? 'Paid' : 'Unpaid'), $paid ? 'green' : 'amber'); ?></td>
        <td data-label="Delivery"><?php echo pill($o['delivery_status'] == '0' ? 'Pending' : 'Delivered', $o['delivery_status'] == '0' ? 'gray' : 'green'); ?></td>
        <td data-label="Status"><?php echo pill($st[0], $st[1]); ?></td>
        <td data-label="Total"><b><?php echo money($o['total']); ?></b><br><small class="muted">incl. <?php echo money($o['delivery_changes']); ?> delivery</small></td>
        <td class="td-actions">
          <div class="actions">
            <a class="btn btn-primary btn-xs" href="adminOrderDetails.php?id=<?php echo (int)$o['id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Manage</a>
            <?php if ($paid) { ?>
              <a class="btn btn-soft btn-xs" href="viewInvoice.php?InvoiceId=<?php echo (int)$o['id']; ?>" target="_blank"><i class="fa-solid fa-file-invoice"></i> Invoice</a>
            <?php } ?>
            <?php if (!$paid && $o['status'] == '0') { ?>
              <form method="POST" data-confirm="Cancel order #<?php echo (int)$o['id']; ?>?" data-confirm-btn="Cancel order">
                <input type="hidden" name="cancelId" value="<?php echo (int)$o['id']; ?>">
                <button class="btn btn-danger btn-xs" type="submit"><i class="fa-solid fa-ban"></i> Cancel</button>
              </form>
            <?php } ?>
          </div>
        </td>
      </tr>
    <?php } } else { ?>
      <tr><td colspan="8" class="empty-row"><i class="fa-solid fa-receipt"></i><?php echo $today ? 'No orders today' : 'No orders yet'; ?></td></tr>
    <?php } ?>
    </tbody>
  </table>
  <div class="no-match" data-list-empty="orders">No orders match your search.</div>
</div>

<?php admin_end(); ?>
