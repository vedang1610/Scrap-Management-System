<?php
// admin/ui/user_layout.php - shell for the customer "My account" pages.
// Reuses the helpers and styles of the admin panel (ui/layout.php, assets/admin.css).
include_once(__DIR__ . '/layout.php');

// Same codes as the admin side, worded for the customer
function user_order_status($code)
{
    $s = order_status($code);
    if ((int)$code == 3) $s[0] = 'Canceled by you';
    return $s;
}

// The photo uploaded at registration (users.idproof), or '' if missing
function user_photo($file = null)
{
    if ($file === null) $file = isset($_SESSION['userImage']) ? $_SESSION['userImage'] : '';
    return ($file && is_file(__DIR__ . '/../upload_images/' . $file)) ? 'upload_images/' . $file : '';
}

// Round avatar: the user's photo, or their initials when there is no photo
function user_avatar($class = '', $name = null, $file = null)
{
    if ($name === null) $name = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';
    $photo = user_photo($file);
    $inner = $photo ? '<img src="' . e($photo) . '" alt="">' : e(initials($name));
    return '<span class="avatar ' . $class . ($photo ? ' has-photo' : '') . '">' . $inner . '</span>';
}

function user_start($active, $title, $subtitle = '', $actions = '', $back = '')
{
    $GLOBALS['userActive'] = $active;
    $userId = (int)$_SESSION['userId'];
    $name = isset($_SESSION['userName']) ? $_SESSION['userName'] : 'My account';
    $cartCount = count_of("SELECT COALESCE(SUM(qty), 0) FROM cart WHERE user_id='$userId'");
    $openOrders = count_of("SELECT COUNT(*) FROM sales WHERE user_id='$userId' AND status IN ('0','1')");
    $GLOBALS['userBadges'] = array('cart' => $cartCount, 'orders' => $openOrders);
    $nav = array(
        'dashboard' => array('userDashboard.php', 'Dashboard', 'fa-house-user'),
        'orders'    => array('userOrders.php',    'My orders', 'fa-receipt', $openOrders),
        'profile'   => array('userProfile.php',   'Profile',   'fa-user-pen'),
    );
    $shop = array(
        array('../scrap.php', 'Shop scrap', 'fa-recycle', 0),
        array('../cart.php',  'My cart',    'fa-cart-shopping', $cartCount),
        array('../index.php', 'Website home', 'fa-globe', 0),
    );
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#ffffff">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <title><?php echo e($title); ?> · My account · Scrap Management System</title>
  <link rel="manifest" href="../manifest.webmanifest">
  <link rel="icon" type="image/png" href="../images/logo-mark.png">
  <link rel="apple-touch-icon" href="../images/icon-192.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="../assets/sms.css">
  <link rel="stylesheet" href="../assets/admin.css">
</head>
<body class="sms admin user-area">

<aside class="side" id="adminSide">
  <a href="../index.php" class="side-brand">
    <span class="side-logo"><img src="../images/logo-mark.png" alt=""></span>
    <span><strong>Scrap MS</strong><small>My account</small></span>
  </a>
  <div class="side-user">
    <?php echo user_avatar(); ?>
    <span><strong><?php echo e($name); ?></strong><small><?php echo e(isset($_SESSION['userEmail']) ? $_SESSION['userEmail'] : ''); ?></small></span>
  </div>
  <nav class="side-nav">
    <?php foreach ($nav as $key => $item) { ?>
      <a href="<?php echo $item[0]; ?>" class="<?php echo $active == $key ? 'is-active' : ''; ?>">
        <i class="fa-solid <?php echo $item[2]; ?>"></i><span><?php echo $item[1]; ?></span>
        <?php if (!empty($item[3])) { ?><em><?php echo $item[3]; ?></em><?php } ?>
      </a>
    <?php } ?>
    <span class="side-label">Shop</span>
    <?php foreach ($shop as $item) { ?>
      <a href="<?php echo $item[0]; ?>"><i class="fa-solid <?php echo $item[2]; ?>"></i><span><?php echo $item[1]; ?></span>
        <?php if (!empty($item[3])) { ?><em><?php echo $item[3]; ?></em><?php } ?></a>
    <?php } ?>
  </nav>
  <div class="side-foot">
    <a href="userLogout.php" class="danger"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Logout</span></a>
  </div>
</aside>
<div class="side-backdrop" data-side-close></div>

<div class="main">
  <header class="abar">
    <?php if ($back) { ?>
      <a href="<?php echo e($back); ?>" class="icon-btn abar-back" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <?php } else { ?>
      <button type="button" class="icon-btn abar-menu" data-side-open aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    <?php } ?>
    <div class="abar-title">
      <h1><?php echo e($title); ?></h1>
      <?php if ($subtitle) { ?><p><?php echo $subtitle; ?></p><?php } ?>
    </div>
    <div class="abar-actions"><?php echo $actions; ?></div>
    <a href="../cart.php" class="icon-btn" aria-label="Cart"><i class="fa-solid fa-cart-shopping"></i><?php if ($cartCount) { ?><span class="badge-count"><?php echo $cartCount; ?></span><?php } ?></a>
    <a href="userProfile.php" class="abar-avatar" aria-label="My profile"><?php echo user_avatar(); ?></a>
  </header>
  <main class="content">
    <?php
}

function user_end($extraScripts = '')
{
    $active = isset($GLOBALS['userActive']) ? $GLOBALS['userActive'] : '';
    $badges = isset($GLOBALS['userBadges']) ? $GLOBALS['userBadges'] : array();
    $flash = isset($_SESSION['adminFlash']) ? $_SESSION['adminFlash'] : null;
    unset($_SESSION['adminFlash']);
    $tabs = array(
        'dashboard' => array('userDashboard.php', 'Home',    'fa-house-user', 0),
        'orders'    => array('userOrders.php',    'Orders',  'fa-receipt', isset($badges['orders']) ? $badges['orders'] : 0),
        'shop'      => array('../scrap.php',      'Shop',    'fa-recycle', 0),
        'cart'      => array('../cart.php',       'Cart',    'fa-cart-shopping', isset($badges['cart']) ? $badges['cart'] : 0),
        'profile'   => array('userProfile.php',   'Profile', 'fa-user', 0),
    );
    ?>
  </main>
</div>

<nav class="tabbar admin-tabs" aria-label="My account">
  <?php foreach ($tabs as $key => $t) { ?>
    <a href="<?php echo $t[0]; ?>" class="<?php echo $active == $key ? 'is-active' : ''; ?>"><?php
      // Profile tab shows the user's own photo, like a phone app
      echo ($key == 'profile' && user_photo()) ? user_avatar('tab-avatar') : '<i class="fa-solid ' . $t[2] . '"></i>'; ?><?php echo $t[1]; ?>
      <?php if (!empty($t[3])) { ?><span class="badge-count"><?php echo $t[3]; ?></span><?php } ?></a>
  <?php } ?>
</nav>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="../assets/sms.js"></script>
<script src="../assets/admin.js"></script>
<?php if ($flash) { ?>
<script>adminToast(<?php echo json_encode($flash['message']); ?>, <?php echo json_encode($flash['type']); ?>);</script>
<?php } ?>
<?php echo $extraScripts; ?>
</body>
</html>
    <?php
}
// Load a user's orders with item count and up to 3 product photos each
function user_orders($userId, $limit = 0)
{
    $userId = (int)$userId;
    $orders = selectData("SELECT s.*, (SELECT COUNT(*) FROM sales_items si WHERE si.sales_id = s.id) AS items
                          FROM sales s WHERE s.user_id = '$userId' ORDER BY s.id DESC" . ($limit ? " LIMIT " . (int)$limit : ''));
    foreach ($orders as $i => $o) {
        $orders[$i]['thumbs'] = selectData("SELECT product_image, product_name FROM sales_items WHERE sales_id = '" . (int)$o['id'] . "' ORDER BY id LIMIT 3");
    }
    return $orders;
}

function order_card($o)
{
    $st = user_order_status($o['status']);
    $paid = $o['payment_status'] == '1';
    $open = in_array((int)$o['status'], array(0, 1));
    $group = in_array((int)$o['status'], array(2, 3)) ? 'canceled' : ((int)$o['status'] == 4 ? 'completed' : 'active');
    ?>
    <article class="order-card" data-search="<?php echo e('#' . $o['id'] . ' ' . $o['delivery_address'] . ' ' . implode(' ', array_column($o['thumbs'], 'product_name'))); ?>" data-filter="<?php echo $group . ($paid ? ' paid' : ($group == 'canceled' ? '' : ' unpaid')); ?>">
      <div class="oc-top">
        <div><strong>Order #<?php echo (int)$o['id']; ?></strong><small><?php echo date('d M Y', strtotime($o['dt'])); ?> &middot; <?php echo (int)$o['items']; ?> item<?php echo $o['items'] == 1 ? '' : 's'; ?></small></div>
        <?php echo pill($st[0], $st[1]); ?>
      </div>
      <div class="oc-thumbs">
        <?php foreach ($o['thumbs'] as $t) { ?><img src="upload_images/<?php echo e($t['product_image']); ?>" alt="<?php echo e($t['product_name']); ?>"><?php } ?>
        <?php if ($o['items'] > 3) { ?><span>+<?php echo $o['items'] - 3; ?></span><?php } ?>
      </div>
      <div class="oc-meta">
        <?php echo pill(($o['payment_mode'] == '0' ? 'Pay on delivery' : 'Online') . ' · ' . ($paid ? 'Paid' : 'Unpaid'), $paid ? 'green' : 'amber'); ?>
        <?php echo pill($o['delivery_status'] == '1' ? 'Delivered' : 'Not delivered yet', $o['delivery_status'] == '1' ? 'green' : 'gray'); ?>
      </div>
      <div class="oc-foot">
        <div class="oc-total"><?php echo money($o['total']); ?><small>incl. <?php echo money($o['delivery_changes']); ?> delivery</small></div>
        <div class="actions">
          <a class="btn btn-soft btn-xs" href="userOrderDetails.php?id=<?php echo (int)$o['id']; ?>">Details</a>
          <?php if ($paid) { ?>
            <a class="btn btn-soft btn-xs" href="viewInvoice.php?InvoiceId=<?php echo (int)$o['id']; ?>"><i class="fa-solid fa-file-invoice"></i> Invoice</a>
          <?php } elseif ($open) { ?>
            <a class="btn btn-primary btn-xs" href="../payment/payment.php?txtId=<?php echo (int)$o['id']; ?>"><i class="fa-regular fa-credit-card"></i> Pay online</a>
          <?php } ?>
          <?php if (!$paid && $o['status'] == '0') { ?>
            <form method="POST" action="userOrders.php" data-confirm="Cancel order #<?php echo (int)$o['id']; ?>?" data-confirm-btn="Cancel order">
              <input type="hidden" name="cancelId" value="<?php echo (int)$o['id']; ?>">
              <button class="btn btn-danger btn-xs" type="submit">Cancel</button>
            </form>
          <?php } ?>
        </div>
      </div>
    </article>
    <?php
}
