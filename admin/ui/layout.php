<?php
// admin/ui/layout.php - shared shell for the redesigned admin panel.
// Usage (after checkLogin.php and functions.php):
//   admin_start('orders', 'Orders', 'Optional subtitle', '<a class="btn ...">Action</a>');
//   ... page content ...
//   admin_end();

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function db_escape($s)
{
    global $con;
    return mysqli_real_escape_string($con, trim((string)$s));
}

// One-time message shown after a redirect (Post/Redirect/Get)
function flash($message, $type = 'success')
{
    $_SESSION['adminFlash'] = array('message' => $message, 'type' => $type);
}

function redirect_to($url, $message = null, $type = 'success')
{
    if ($message !== null) flash($message, $type);
    header('Location: ' . $url);
    exit;
}

function count_of($sql)
{
    $rows = selectData($sql);
    return (is_array($rows) && count($rows)) ? (int)array_values($rows[0])[0] : 0;
}

// sales.status: 0 Pending, 1 Confirmed, 2 Canceled (by admin), 3 Canceled by user, 4 Completed
function order_status($code)
{
    $map = array(
        0 => array('Pending', 'amber'),
        1 => array('Confirmed', 'blue'),
        2 => array('Canceled', 'red'),
        3 => array('Canceled by user', 'red'),
        4 => array('Completed', 'green'),
    );
    return isset($map[(int)$code]) ? $map[(int)$code] : array('Unknown', 'gray');
}

function pill($text, $color)
{
    return '<span class="pill-s ' . $color . '">' . e($text) . '</span>';
}

function money($n) { return '&#8377;' . number_format((float)$n, 2); }

function initials($name)
{
    $parts = preg_split('/\s+/', trim($name));
    $out = '';
    foreach (array_slice($parts, 0, 2) as $p) $out .= strtoupper(substr($p, 0, 1));
    return $out ?: '?';
}

function admin_nav()
{
    return array(
        'dashboard' => array('dashboard.php',    'Dashboard', 'fa-gauge-high'),
        'orders'    => array('adminOrders.php',  'Orders',    'fa-receipt'),
        'category'  => array('category.php',     'Categories','fa-layer-group'),
        'products'  => array('products.php',     'Products',  'fa-box'),
        'users'     => array('viewusers.php',    'Users',     'fa-users'),
        'feedback'  => array('feedback.php',     'Feedback',  'fa-comment-dots'),
        'profile'   => array('adminProfile.php', 'Profile',   'fa-user-gear'),
    );
}

function admin_start($active, $title, $subtitle = '', $actions = '', $back = '')
{
    $GLOBALS['adminActive'] = $active;
    $adminName = isset($_SESSION['adminName']) ? $_SESSION['adminName'] : 'Admin';
    $adminEmail = isset($_SESSION['adminEmail']) ? $_SESSION['adminEmail'] : '';
    $badges = array(
        'orders'   => count_of("SELECT COUNT(*) FROM sales WHERE status='0'"),
        'feedback' => count_of("SELECT COUNT(*) FROM feedback"),
    );
    $GLOBALS['adminBadges'] = $badges;
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="theme-color" content="#0f172a">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <title><?php echo e($title); ?> · Admin · Scrap Management System</title>
  <link rel="icon" type="image/png" href="../images/logo-mark.png">
  <link rel="apple-touch-icon" href="../images/icon-192.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="../assets/sms.css">
  <link rel="stylesheet" href="../assets/admin.css">
</head>
<body class="sms admin">

<aside class="side" id="adminSide">
  <a href="dashboard.php" class="side-brand">
    <span class="side-logo"><img src="../images/logo-mark.png" alt=""></span>
    <span><strong>Scrap MS</strong><small>Admin panel</small></span>
  </a>
  <nav class="side-nav">
    <?php foreach (admin_nav() as $key => $item) { ?>
      <a href="<?php echo $item[0]; ?>" class="<?php echo $active == $key ? 'is-active' : ''; ?>">
        <i class="fa-solid <?php echo $item[2]; ?>"></i><span><?php echo $item[1]; ?></span>
        <?php if (!empty($badges[$key])) { ?><em><?php echo $badges[$key]; ?></em><?php } ?>
      </a>
    <?php } ?>
  </nav>
  <div class="side-foot">
    <a href="../index.php" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i><span>View website</span></a>
    <a href="logout.php" class="danger"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Logout</span></a>
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
    <a href="adminProfile.php" class="avatar" title="<?php echo e($adminName . ' ' . $adminEmail); ?>"><?php echo e(initials($adminName)); ?></a>
  </header>
  <main class="content">
    <?php
}

function admin_end($extraScripts = '')
{
    $active = isset($GLOBALS['adminActive']) ? $GLOBALS['adminActive'] : '';
    $badges = isset($GLOBALS['adminBadges']) ? $GLOBALS['adminBadges'] : array();
    $flash = isset($_SESSION['adminFlash']) ? $_SESSION['adminFlash'] : null;
    unset($_SESSION['adminFlash']);
    $tabs = array(
        'dashboard' => array('dashboard.php',   'Home',     'fa-gauge-high'),
        'orders'    => array('adminOrders.php', 'Orders',   'fa-receipt'),
        'products'  => array('products.php',    'Products', 'fa-box'),
        'users'     => array('viewusers.php',   'Users',    'fa-users'),
    );
    ?>
  </main>
</div>

<nav class="tabbar admin-tabs" aria-label="Admin">
  <?php foreach ($tabs as $key => $t) { ?>
    <a href="<?php echo $t[0]; ?>" class="<?php echo $active == $key ? 'is-active' : ''; ?>"><i class="fa-solid <?php echo $t[2]; ?>"></i><?php echo $t[1]; ?>
      <?php if (!empty($badges[$key])) { ?><span class="badge-count"><?php echo $badges[$key]; ?></span><?php } ?></a>
  <?php } ?>
  <button type="button" data-side-open class="<?php echo in_array($active, array('category', 'feedback', 'profile')) ? 'is-active' : ''; ?>"><i class="fa-solid fa-ellipsis"></i>More</button>
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
