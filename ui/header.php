<?php
// ui/header.php - top bar + mobile menu sheet for the redesigned public pages.
// Set $activePage ('home', 'scrap', 'feedback', 'about', 'contact', 'account') before including.
$activePage = isset($activePage) ? $activePage : '';
$loggedIn = isset($_SESSION['userId']);
// Number of items in the cart, shown as a badge on the cart icons
$cartCount = 0;
if ($loggedIn && function_exists('selectData')) {
    $cartRows = selectData("SELECT COALESCE(SUM(qty), 0) AS n FROM cart WHERE user_id = '" . (int)$_SESSION['userId'] . "'");
    if (is_array($cartRows) && count($cartRows)) $cartCount = (int)$cartRows[0]['n'];
}
// The logged-in user's photo (uploaded at registration), shown in the account buttons
$userPhoto = '';
if ($loggedIn && !empty($_SESSION['userImage']) && is_file(__DIR__ . '/../admin/upload_images/' . $_SESSION['userImage'])) {
    $userPhoto = 'admin/upload_images/' . htmlspecialchars($_SESSION['userImage'], ENT_QUOTES, 'UTF-8');
}
$cartBadge = $cartCount ?'<span class="badge-count">' . ($cartCount > 99 ? '99+' : $cartCount) . '</span>' : '';
$navLinks = array(
    'home'     => array('index.php',    'Home',           'fa-house'),
    'scrap'    => array('scrap.php',    'Scrap Products', 'fa-recycle'),
    'feedback' => array('feedback.php', 'Feedback',       'fa-comment-dots'),
    'about'    => array('about.php',    'About Us',       'fa-circle-info'),
    'contact'  => array('contact.php',  'Contact',        'fa-phone'),
);
?>
<header class="topbar">
  <div class="wrap">
    <a href="index.php" class="brand" aria-label="Scrap Management System home">
      <img src="images/logo1.png" alt="Scrap Management System">
    </a>

    <nav class="nav" aria-label="Main">
      <?php foreach ($navLinks as $key => $link) { ?>
        <a href="<?php echo $link[0]; ?>" class="<?php echo $activePage == $key ? 'is-active' : ''; ?>"><?php echo $link[1]; ?></a>
      <?php } ?>
    </nav>

    <div class="top-actions">
      <a href="cart.php" class="icon-btn" aria-label="Cart"><i class="fa-solid fa-cart-shopping"></i><?php echo $cartBadge; ?></a>
      <?php if ($loggedIn) { ?>
        <a href="admin/userDashboard.php" class="btn btn-primary btn-sm"><?php echo $userPhoto ? '<img class="btn-avatar" src="' . $userPhoto . '" alt="">' : '<i class="fa-regular fa-user"></i>'; ?> My Account</a>
      <?php } else { ?>
        <a href="admin/userLogin.php" class="btn btn-ghost btn-sm">Login</a>
        <a href="register.php" class="btn btn-primary btn-sm">Register</a>
      <?php } ?>
      <button type="button" class="icon-btn menu-btn" data-sheet-open aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    </div>
  </div>
</header>

<div class="sheet-backdrop" data-sheet-close></div>
<div class="sheet" role="dialog" aria-label="Menu">
  <div class="sheet-handle"></div>
  <h3>Menu</h3>
  <?php foreach ($navLinks as $key => $link) { ?>
    <a href="<?php echo $link[0]; ?>" class="<?php echo $activePage == $key ? 'is-active' : ''; ?>"><i class="fa-solid <?php echo $link[2]; ?>"></i><?php echo $link[1]; ?></a>
  <?php } ?>
  <?php if ($loggedIn) { ?>
    <a href="admin/userDashboard.php"><?php echo $userPhoto ? '<img class="sheet-photo" src="' . $userPhoto . '" alt="">' : '<i class="fa-regular fa-user"></i>'; ?>My Account</a>
    <a href="admin/userLogout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
  <?php } else { ?>
    <a href="admin/userLogin.php"><i class="fa-solid fa-arrow-right-to-bracket"></i>Login</a>
    <a href="register.php" class="<?php echo $activePage == 'account' ? 'is-active' : ''; ?>"><i class="fa-solid fa-user-plus"></i>Register</a>
  <?php } ?>
</div>
