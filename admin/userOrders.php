<?php include_once("userCheckLogin.php");
include_once("functions.php");
include_once("ui/user_layout.php");

$userId = (int)$_SESSION['userId'];

// Cancel: only your own, unpaid, still-pending order (anyone could cancel any order by id before)
if (isset($_POST['cancelId']))
{
    $id = (int)$_POST['cancelId'];
    $ok = insert_update_delete_data("UPDATE `sales` SET status='3' WHERE id='$id' AND user_id='$userId' AND status='0' AND payment_status='0'");
    $ok = $ok && mysqli_affected_rows($con) > 0;
    redirect_to('userOrders.php', $ok ? "Order #$id canceled." : 'This order can no longer be canceled.', $ok ? 'success' : 'error');
}

$orders = user_orders($userId);
$counts = array('all' => count($orders), 'active' => 0, 'completed' => 0, 'canceled' => 0, 'unpaid' => 0);
foreach ($orders as $o) {
    if (in_array((int)$o['status'], array(0, 1))) $counts['active']++;
    if ((int)$o['status'] == 4) $counts['completed']++;
    if (in_array((int)$o['status'], array(2, 3))) $counts['canceled']++;
    if ($o['payment_status'] == '0' && !in_array((int)$o['status'], array(2, 3))) $counts['unpaid']++;
}
$start = isset($_GET['status']) && isset($counts[$_GET['status']]) ? $_GET['status'] : 'all';

user_start('orders', 'My orders', count($orders) . ' order' . (count($orders) == 1 ? '' : 's'));
?>

<?php if (count($orders)) { ?>
  <div class="toolbar">
    <label class="searchbox"><i class="fa-solid fa-magnifying-glass"></i>
      <input type="search" placeholder="Search order no or product" data-list-search="orders" aria-label="Search orders"></label>
    <div class="chips" data-list-filter="orders">
      <?php foreach (array('all' => 'All', 'active' => 'In progress', 'unpaid' => 'Unpaid', 'completed' => 'Completed', 'canceled' => 'Canceled') as $k => $label) { ?>
        <a href="#" class="chip no-img <?php echo $start == $k ? 'is-active' : ''; ?>" data-f="<?php echo $k; ?>"><?php echo $label; ?> <em><?php echo $counts[$k]; ?></em></a>
      <?php } ?>
    </div>
  </div>
  <div class="order-list" data-list="orders">
    <?php foreach ($orders as $o) order_card($o); ?>
  </div>
  <div class="no-match" data-list-empty="orders">No orders match.</div>
<?php } else { ?>
  <div class="box"><div class="empty-row" style="display:block"><i class="fa-solid fa-bag-shopping"></i>You haven't ordered anything yet<br><br>
    <a href="../scrap.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-recycle"></i> Shop scrap</a></div></div>
<?php } ?>

<?php user_end(); ?>
