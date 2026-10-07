<?php include_once("userCheckLogin.php");
include_once('functions.php');
include_once('ui/user_layout.php');

$userId = (int)$_SESSION['userId'];
$totalOrders    = count_of("SELECT COUNT(*) FROM `sales` WHERE user_id='$userId'");
// was status='1' (confirmed) and an OR without brackets that counted every user's canceled orders
$pendingOrders  = count_of("SELECT COUNT(*) FROM `sales` WHERE user_id='$userId' AND status IN ('0','1')");
$doneOrders     = count_of("SELECT COUNT(*) FROM `sales` WHERE user_id='$userId' AND status='4'");
$canceledOrders = count_of("SELECT COUNT(*) FROM `sales` WHERE user_id='$userId' AND status IN ('2','3')");
$spentRow = selectData("SELECT COALESCE(SUM(total), 0) AS t FROM `sales` WHERE user_id='$userId' AND payment_status='1'");
$spent = count($spentRow) ? $spentRow[0]['t'] : 0;
$cartCount = count_of("SELECT COALESCE(SUM(qty), 0) FROM cart WHERE user_id='$userId'");
$recent = user_orders($userId, 4);

$first = explode(' ', trim(isset($_SESSION['userName']) ? $_SESSION['userName'] : ''))[0];
$hello = date('H') < 12 ? 'Good morning' : (date('H') < 17 ? 'Good afternoon' : 'Good evening');

user_start('dashboard', 'Dashboard', date('l, d M Y'));
?>

<div class="stack">
  <section class="welcome">
    <div>
      <h2><?php echo e($hello . ($first ? ', ' . $first : '')); ?>!</h2>
      <p><?php echo $pendingOrders ? "You have $pendingOrders order" . ($pendingOrders == 1 ? '' : 's') . ' on the way.' : 'Find recycled scrap products at the best price.'; ?></p>
    </div>
    <div class="cta-actions">
      <a href="../scrap.php" class="btn"><i class="fa-solid fa-recycle"></i> Shop scrap</a>
      <a href="../cart.php" class="btn btn-glass"><i class="fa-solid fa-cart-shopping"></i> Cart<?php echo $cartCount ? " ($cartCount)" : ''; ?></a>
    </div>
  </section>

  <div class="stats-grid">
    <a class="stat-card" href="userOrders.php">
      <span class="f-icon"><i class="fa-solid fa-receipt"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $totalOrders; ?></strong><span>Total orders</span>
    </a>
    <a class="stat-card amber" href="userOrders.php?status=active">
      <span class="f-icon"><i class="fa-solid fa-truck-fast"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $pendingOrders; ?></strong><span>In progress</span>
    </a>
    <a class="stat-card" href="userOrders.php?status=completed">
      <span class="f-icon blue"><i class="fa-solid fa-circle-check"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $doneOrders; ?></strong><span>Completed</span>
    </a>
    <a class="stat-card red" href="userOrders.php?status=canceled">
      <span class="f-icon"><i class="fa-solid fa-ban"></i></span><i class="fa-solid fa-arrow-right go"></i>
      <strong><?php echo $canceledOrders; ?></strong><span>Canceled</span>
    </a>
  </div>

  <div class="two-col">
    <div class="box">
      <div class="box-head"><h2>Recent orders</h2><a href="userOrders.php" class="link-more">View all<i class="fa-solid fa-arrow-right"></i></a></div>
      <div class="box-body">
        <?php if (count($recent)) { ?>
          <div class="order-list" style="grid-template-columns:1fr">
            <?php foreach ($recent as $o) order_card($o); ?>
          </div>
        <?php } else { ?>
          <div class="empty-row" style="display:block"><i class="fa-solid fa-bag-shopping"></i>No orders yet<br><br>
            <a href="../scrap.php" class="btn btn-primary btn-sm">Start shopping</a></div>
        <?php } ?>
      </div>
    </div>

    <div class="stack">
      <div class="box">
        <div class="box-head"><h2>Total paid</h2></div>
        <div class="box-body"><strong style="font-size:28px;font-weight:800"><?php echo money($spent); ?></strong>
          <p class="muted small">across your paid orders</p></div>
      </div>
      <div class="box">
        <div class="box-head"><h2>Quick links</h2></div>
        <div class="box-body quick" style="grid-template-columns:1fr 1fr">
          <a href="../scrap.php"><i class="fa-solid fa-recycle"></i>Shop scrap</a>
          <a href="../cart.php"><i class="fa-solid fa-cart-shopping"></i>My cart</a>
          <a href="userProfile.php"><i class="fa-solid fa-user-pen"></i>Edit profile</a>
          <a href="../feedback.php"><i class="fa-solid fa-comment-dots"></i>Feedback</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php user_end(); ?>
