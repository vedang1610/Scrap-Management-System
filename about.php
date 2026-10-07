<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

$pageTitle = 'About Us';
$activePage = 'about';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('ui/head.php'); ?>
</head>
<body class="sms has-tabbar">

  <?php include('ui/header.php'); ?>

  <section class="hero">
    <div class="wrap">
      <div class="crumbs"><a href="index.php">Home</a><i class="fa-solid fa-chevron-right"></i><span>About Us</span></div>
      <h1>About us</h1>
      <p>An online marketplace to recycle, buy and sell scrap responsibly.</p>
    </div>
  </section>

  <section class="section">
    <div class="wrap about-grid">
      <div class="gallery">
        <?php for ($i = 1; $i <= 6; $i++) { ?>
          <a href="extra-images/about-gallery-img<?php echo $i; ?>.jpg" data-lightbox aria-label="Open photo <?php echo $i; ?>">
            <img src="extra-images/about-gallery-img<?php echo $i; ?>.jpg" alt="" loading="lazy">
          </a>
        <?php } ?>
      </div>
      <div class="prose">
        <span class="eyebrow">Who we are</span>
        <div class="sec-title" style="margin-bottom:0"><h2>About Scrap Management System</h2></div>
        <p class="lead">An online business built on scrap management. Solid waste can be recycled and sold using this system, and it's designed to prevent further damage to the environment.</p>
        <p>Recycled vehicles and their parts, like paper, plastic, metal and glass, can be bought and sold here. The system has two modules, Admin and User: the admin monitors users, and everyone gets a simple interface and a wide market. More competition means more benefit for users.</p>
        <p>We take pride in being a top service provider through our sales, logistics and accounting departments. Dealing with us is convenient, straightforward and prompt, and our main focus is keeping your scrap moving in all market conditions.</p>
        <p>Our experienced sales team helps you minimise disposal costs and maximise your return, with a focus on diverting plastics from landfills and incineration plants.</p>
        <div class="mini-stats">
          <div><strong>1500+</strong><span>Customers</span></div>
          <div><strong>97%</strong><span>Recommend us</span></div>
          <div><strong>2</strong><span>Modules</span></div>
        </div>
      </div>
    </div>
  </section>

  <section class="section alt">
    <div class="wrap">
      <div class="sec-title center"><span class="eyebrow">Simple process</span><h2>How it works</h2>
        <p>From collecting your scrap to delivering recycled products to your door.</p></div>
      <div class="steps">
        <div class="step"><h3>Collect &amp; recycle</h3><p>Collect scrap, recycle it and make new scrap products.</p></div>
        <div class="step"><h3>Purchase products</h3><p>Pick the products you need and place an order.</p></div>
        <div class="step"><h3>Customer service</h3><p>We're here to help with every order.</p></div>
        <div class="step"><h3>Delivered to you</h3><p>Your products are delivered to your door.</p></div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="cta">
        <div><h2>Find scrap today</h2><p>Browse recycled scrap products from our marketplace.</p></div>
        <div class="cta-actions">
          <a href="scrap.php" class="btn">Browse scrap <i class="fa-solid fa-arrow-right"></i></a>
          <a href="contact.php" class="btn btn-glass">Contact us</a>
        </div>
      </div>
    </div>
  </section>

  <?php include('ui/footer.php'); ?>

  <script src="assets/sms.js?v=<?php echo @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/sms.js'); ?>"></script>
</body>
</html>
