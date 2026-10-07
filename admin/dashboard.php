<?php include_once("checkLogin.php");
include_once('functions.php');
include_once('ui/layout.php');

$dt = date("Y-m-d");
$todayOrders    = count_of("SELECT COUNT(*) FROM `sales` WHERE dt='$dt'");
$totalOrders    = count_of("SELECT COUNT(*) FROM `sales`");
$pendingOrders  = count_of("SELECT COUNT(*) FROM `sales` WHERE status='0'");
$canceledOrders = count_of("SELECT COUNT(*) FROM `sales` WHERE status='2' OR status='3'");
$usersCount     = count_of("SELECT COUNT(*) FROM `users`");
$productsCount  = count_of("SELECT COUNT(*) FROM `products`");
$activeProducts = count_of("SELECT COUNT(*) FROM `products` WHERE status='1'");
$feedbackCount  = count_of("SELECT COUNT(*) FROM `feedback`");

$rev = selectData("SELECT COALESCE(SUM(total), 0) AS t FROM `sales` WHERE payment_status='1' AND status NOT IN ('2','3')");
$revenue = (is_array($rev) && count($rev)) ? $rev[0]['t'] : 0;

$recent = selectData("SELECT s.*, u.name AS user_name,
                        (SELECT COUNT(*) FROM sales_items si WHERE si.sales_id = s.id) AS items
                      FROM `sales` s LEFT JOIN `users` u ON u.id = s.user_id
                      ORDER BY s.id DESC LIMIT 6");
$feedback = selectData("SELECT * FROM `feedback` ORDER BY id DESC LIMIT 3");

$hello = date('H') < 12 ? 'Good morning' : (date('H') < 17 ? 'Good afternoon' : 'Good evening');
$name = isset($_SESSION['adminName']) ? $_SESSION['adminName'] : 'Admin';

admin_start('dashboard', 'Dashboard', e($hello . ', ' . $name) . ' &middot; ' . date('d M Y'));
?>

<div class="stack">
  <div class="stats-grid">
    <a class="stat-card" href="adminOrders.php?filterByTodayDate=true">
      <span class="f-icon blue"><i class="fa-solid fa-calendar-day"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $todayOrders; ?></strong><span>Today's orders</span>
    </a>
    <a class="stat-card" href="adminOrders.php">
      <span class="f-icon"><i class="fa-solid fa-receipt"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $totalOrders; ?></strong><span>Total orders</span>
    </a>
    <a class="stat-card amber" href="adminOrders.php?status=pending">
      <span class="f-icon"><i class="fa-solid fa-hourglass-half"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $pendingOrders; ?></strong><span>Pending orders</span>
    </a>
    <a class="stat-card red" href="adminOrders.php?status=canceled">
      <span class="f-icon"><i class="fa-solid fa-ban"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $canceledOrders; ?></strong><span>Canceled orders</span>
    </a>
    <div class="stat-card">
      <span class="f-icon violet"><i class="fa-solid fa-indian-rupee-sign"></i></span>
      <strong><?php echo money($revenue); ?></strong><span>Paid revenue</span>
    </div>
    <a class="stat-card" href="viewusers.php">
      <span class="f-icon pink"><i class="fa-solid fa-users"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $usersCount; ?></strong><span>Registered users</span>
    </a>
    <a class="stat-card" href="products.php">
      <span class="f-icon orange"><i class="fa-solid fa-box"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $activeProducts; ?><small class="muted" style="font-size:14px"> / <?php echo $productsCount; ?></small></strong><span>Active products</span>
    </a>
    <a class="stat-card" href="feedback.php">
      <span class="f-icon blue"><i class="fa-solid fa-comment-dots"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $feedbackCount; ?></strong><span>Feedback messages</span>
    </a>
  </div>

  <div class="box">
    <div class="box-head"><h2>Quick actions</h2></div>
    <div class="box-body quick">
      <a href="addProducts.php"><i class="fa-solid fa-plus"></i>Add product</a>
      <a href="addCategory.php"><i class="fa-solid fa-layer-group"></i>Add category</a>
      <a href="adminOrders.php?status=pending"><i class="fa-solid fa-hourglass-half"></i>Pending orders</a>
      <a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i>View website</a>
    </div>
  </div>

  <div class="two-col">
    <div class="box table-box">
      <div class="box-head"><h2>Recent orders</h2><a href="adminOrders.php" class="link-more">View all<i class="fa-solid fa-arrow-right"></i></a></div>
      <div class="box-body">
        <table class="dtable">
          <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead>
          <tbody>
          <?php if (is_array($recent) && count($recent)) { foreach ($recent as $o) { $st = order_status($o['status']); ?>
            <tr>
              <td data-label="Order"><b>#<?php echo (int)$o['id']; ?></b> <small class="muted"><?php echo date('d/m/Y', strtotime($o['dt'])); ?></small></td>
              <td data-label="Customer"><?php echo e($o['user_name'] ?: 'Deleted user'); ?></td>
              <td data-label="Total"><b><?php echo money($o['total']); ?></b></td>
              <td data-label="Status"><?php echo pill($st[0], $st[1]); ?></td>
              <td class="td-actions"><div class="actions"><a class="btn btn-soft btn-xs" href="adminOrderDetails.php?id=<?php echo (int)$o['id']; ?>">Open</a></div></td>
            </tr>
          <?php } } else { ?>
            <tr><td colspan="5" class="empty-row"><i class="fa-solid fa-receipt"></i>No orders yet</td></tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <div class="box">
      <div class="box-head"><h2>Latest feedback</h2><a href="feedback.php" class="link-more">View all<i class="fa-solid fa-arrow-right"></i></a></div>
      <div class="box-body stack" style="gap:12px">
        <?php if (is_array($feedback) && count($feedback)) { foreach ($feedback as $f) { ?>
          <div>
            <div class="fb-top"><span class="avatar av-sm"><?php echo e(initials($f['name'])); ?></span>
              <div><strong><?php echo e($f['name']); ?></strong><small><?php echo e($f['email']); ?></small></div></div>
            <div class="fb-msg" style="margin-top:8px"><?php echo e($f['message']); ?></div>
          </div>
        <?php } } else { ?>
          <p class="muted small">No feedback yet.</p>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<?php admin_end(); ?>
