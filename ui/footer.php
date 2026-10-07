<?php
// ui/footer.php - footer + phone bottom tab bar for the redesigned public pages.
// Set $showTabbar = false on pages that use their own bottom action bar.
$activePage = isset($activePage) ? $activePage : '';
$showTabbar = isset($showTabbar) ? $showTabbar : true;
$cartBadge = isset($cartBadge) ? $cartBadge : '';
$accountUrl = isset($_SESSION['userId']) ? 'admin/userDashboard.php' : 'admin/userLogin.php';
?>
<footer class="footer">
  <div class="wrap">
    <div class="footer-grid">
      <div class="f-brand">
        <img src="images/logo1.png" alt="Scrap Management System">
        <p>Sell your scrap at the best price, get it collected from your door and recycled responsibly.</p>
      </div>
      <div>
        <h4>Quick links</h4>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="scrap.php">Scrap Products</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="contact.php">Contact Us</a></li>
        </ul>
      </div>
      <div>
        <h4>Related websites</h4>
        <ul>
          <li><a href="https://scrapmanagement.com/about-us/" target="_blank" rel="noopener">New way to Scrap</a></li>
          <li><a href="http://scrapmanagementsystem.com/" target="_blank" rel="noopener">Information for Scrap</a></li>
          <li><a href="https://www.slideshare.net/sinunstah/scrap-management" target="_blank" rel="noopener">How to create slide</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">&copy; <?php echo date('Y'); ?> Scrap Management System</div>
  </div>
</footer>

<?php if ($showTabbar) { ?>
<nav class="tabbar" aria-label="App">
  <a href="index.php" class="<?php echo $activePage == 'home' ? 'is-active' : ''; ?>"><i class="fa-solid fa-house"></i>Home</a>
  <a href="scrap.php" class="<?php echo $activePage == 'scrap' ? 'is-active' : ''; ?>"><i class="fa-solid fa-recycle"></i>Scrap</a>
  <a href="cart.php" class="<?php echo $activePage == 'cart' ? 'is-active' : ''; ?>"><i class="fa-solid fa-cart-shopping"></i>Cart<?php echo $cartBadge; ?></a>
  <a href="<?php echo $accountUrl; ?>" class="<?php echo $activePage == 'account' ? 'is-active' : ''; ?>"><?php echo !empty($userPhoto) ? '<img class="tab-photo" src="' . $userPhoto . '" alt="">' : '<i class="fa-regular fa-user"></i>'; ?>Account</a>
</nav>
<?php } ?>
