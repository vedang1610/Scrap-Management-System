<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$categories = selectData("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id AND p.status = '1') AS total
                          FROM `category` c order by c.id desc");
if (!is_array($categories)) $categories = array();
$categoryNames = array();
foreach ($categories as $row) $categoryNames[$row['id']] = $row['name'];

$products = selectData("SELECT * FROM `products` where status='1' order by id desc limit 8");
if (!is_array($products)) $products = array();

$pageTitle = 'Home';
$activePage = 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('ui/head.php'); ?>
</head>
<body class="sms has-tabbar">

  <?php include('ui/header.php'); ?>

  <section class="hero hero-home">
    <div class="wrap">
      <span class="hero-tag"><i class="fa-solid fa-leaf"></i> Recycle smarter, earn more</span>
      <h1>Get or sell scrap <em>the easy way</em></h1>
      <p>We buy your scrap whatever its condition: damaged, flood damaged, non-runner, write-off or unroadworthy, any make, model or age.</p>
      <div class="hero-cta">
        <a href="scrap.php" class="btn btn-primary"><i class="fa-solid fa-recycle"></i> Browse scrap</a>
        <?php if (isset($_SESSION['userId'])) { ?>
          <a href="admin/userDashboard.php" class="btn btn-glass"><i class="fa-regular fa-user"></i> My account</a>
        <?php } else { ?>
          <a href="register.php" class="btn btn-glass"><i class="fa-solid fa-user-plus"></i> Register free</a>
        <?php } ?>
      </div>
      <div class="stats">
        <div class="stat"><strong>1500+</strong><span>Happy customers</span></div>
        <div class="stat"><strong>97%</strong><span>Would recommend</span></div>
        <div class="stat"><strong>Free</strong><span>Doorstep pickup</span></div>
      </div>
    </div>
  </section>

  <?php if (count($categories)) { ?>
  <section class="section">
    <div class="wrap">
      <div class="sec-row">
        <div class="sec-title"><span class="eyebrow">Categories</span><h2>Shop by category</h2></div>
        <a href="scrap.php" class="link-more">View all<i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="cat-grid">
        <?php foreach ($categories as $row) { ?>
          <a href="scrap.php?categoryId=<?php echo (int)$row['id']; ?>" class="cat-tile">
            <img src="admin/upload_images/<?php echo e($row['image']); ?>" alt="" loading="lazy">
            <div><strong><?php echo e($row['name']); ?></strong><span><?php echo (int)$row['total']; ?> item<?php echo $row['total'] == 1 ? '' : 's'; ?></span></div>
          </a>
        <?php } ?>
      </div>
    </div>
  </section>
  <?php } ?>

  <?php if (count($products)) { ?>
  <section class="section" style="padding-top:0">
    <div class="wrap">
      <div class="sec-row">
        <div class="sec-title"><span class="eyebrow">Fresh in</span><h2>Latest scrap</h2></div>
        <a href="scrap.php" class="link-more">See all<i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="grid" style="padding-bottom:0">
        <?php foreach ($products as $row) { ?>
          <article class="card">
            <a class="card-media" href="productDetails.php?getData=<?php echo (int)$row['id']; ?>">
              <img src="admin/upload_images/<?php echo e($row['image']); ?>" alt="<?php echo e($row['name']); ?>" loading="lazy">
              <?php if (isset($categoryNames[$row['category_id']])) { ?>
                <span class="badge"><?php echo e($categoryNames[$row['category_id']]); ?></span>
              <?php } ?>
            </a>
            <div class="card-body">
              <a href="productDetails.php?getData=<?php echo (int)$row['id']; ?>" class="card-title"><?php echo e($row['name']); ?></a>
              <div class="price">&#8377;<?php echo e($row['price']); ?> <small>/ item</small></div>
              <div class="card-actions">
                <a href="cart.php?addProduct=<?php echo (int)$row['id']; ?>&amp;qty=1" class="btn btn-primary"><i class="fa-solid fa-cart-plus"></i> Add to cart</a>
                <a href="productDetails.php?getData=<?php echo (int)$row['id']; ?>" class="btn btn-ghost" aria-label="View details"><i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </article>
        <?php } ?>
      </div>
    </div>
  </section>
  <?php } ?>

  <section class="section alt">
    <div class="wrap">
      <div class="sec-title center"><span class="eyebrow">Our advantages</span><h2>Why choose us</h2>
        <p>Selling scrap should be quick, fair and hassle free. Here's what you get with us.</p></div>
      <div class="features">
        <div class="feature"><span class="f-icon"><i class="fa-solid fa-indian-rupee-sign"></i></span>
          <div><h3>Best price in an instant</h3><p>We offer you the best price for your scrap, instantly.</p></div></div>
        <div class="feature"><span class="f-icon orange"><i class="fa-solid fa-file-signature"></i></span>
          <div><h3>Official paperwork sorted</h3><p>We'll take care of all the official paperwork for you.</p></div></div>
        <div class="feature"><span class="f-icon blue"><i class="fa-solid fa-truck"></i></span>
          <div><h3>Scrap collection</h3><p>We collect scrap from all over India, right from your door.</p></div></div>
        <div class="feature"><span class="f-icon"><i class="fa-solid fa-recycle"></i></span>
          <div><h3>Responsible recycling</h3><p>Everything is recycled following the government rules for scrap.</p></div></div>
        <div class="feature"><span class="f-icon pink"><i class="fa-solid fa-heart"></i></span>
          <div><h3>Our customers love us</h3><p>We're proud that 97% of our customers would recommend us.</p></div></div>
        <div class="feature"><span class="f-icon violet"><i class="fa-solid fa-location-dot"></i></span>
          <div><h3>Wide service area</h3><p>Free scrap collection all over your city.</p></div></div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="sec-title center"><span class="eyebrow">Simple process</span><h2>How it works</h2>
        <p>We collect waste from users, recycle it into new products and sell them.</p></div>
      <div class="steps">
        <div class="step"><h3>Collect &amp; recycle</h3><p>We collect your scrap and recycle it into new products.</p></div>
        <div class="step"><h3>Purchase products</h3><p>Browse recycled scrap products and add them to your cart.</p></div>
        <div class="step"><h3>Customer service</h3><p>Our team helps you at every step of your order.</p></div>
        <div class="step"><h3>Delivered to you</h3><p>Your products are delivered right to your doorstep.</p></div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0">
    <div class="wrap">
      <div class="sec-title"><span class="eyebrow">Know the law</span><h2>Government rules for scrap</h2></div>
      <div class="link-list">
        <a class="link-card" href="https://moef.gov.in/en/service/environment/waste-management/" target="_blank" rel="noopener">
          <span class="f-icon"><i class="fa-solid fa-scale-balanced"></i></span>
          <div><strong>Government rules</strong><span>Waste management rules and services</span></div>
          <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
        <a class="link-card" href="https://www.npcindia.gov.in/NPC/Files/delhiOFC/EM/Hazardous-waste-management-rules-2016.pdf" target="_blank" rel="noopener">
          <span class="f-icon orange"><i class="fa-solid fa-landmark"></i></span>
          <div><strong>Ministry of Environment, Forests &amp; Climate Change</strong><span>Hazardous waste management rules, 2016 (PDF)</span></div>
          <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0">
    <div class="wrap">
      <div class="cta">
        <div><h2>Get your scrap now</h2><p>Find recycled scrap products or tell us what you'd like to sell.</p></div>
        <div class="cta-actions">
          <a href="scrap.php" class="btn">Find scrap <i class="fa-solid fa-arrow-right"></i></a>
          <a href="contact.php" class="btn btn-glass">Contact us</a>
        </div>
      </div>
    </div>
  </section>

  <?php include('ui/footer.php'); ?>

  <script src="assets/sms.js"></script>
</body>
</html>
