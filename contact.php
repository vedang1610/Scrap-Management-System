<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

$phones = array('+91 8140599726', '+91 9428458305', '+91 9537487821', '+91 7874838715');
$email = 'vedang16102000@gmail.com';
$address = 'Nr Railway Station, Vadodara';

$pageTitle = 'Contact Us';
$activePage = 'contact';
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
      <div class="crumbs"><a href="index.php">Home</a><i class="fa-solid fa-chevron-right"></i><span>Contact Us</span></div>
      <h1>Contact us</h1>
      <p>Questions about selling or buying scrap? Call, email or visit us. We're happy to help.</p>
    </div>
  </section>

  <section class="section">
    <div class="wrap">
      <div class="contact-grid">
        <div class="contact-card">
          <span class="f-icon"><i class="fa-solid fa-phone"></i></span>
          <h3>Call us now</h3>
          <?php foreach ($phones as $p) { ?>
            <a class="row-link" href="tel:<?php echo str_replace(' ', '', $p); ?>"><?php echo $p; ?><i class="fa-solid fa-phone-volume"></i></a>
          <?php } ?>
        </div>
        <div class="contact-card">
          <span class="f-icon blue"><i class="fa-regular fa-envelope"></i></span>
          <h3>Mail us</h3>
          <a class="row-link" href="mailto:<?php echo $email; ?>" style="word-break:break-all"><?php echo $email; ?><i class="fa-solid fa-paper-plane"></i></a>
          <a class="row-link" href="feedback.php">Send us feedback<i class="fa-solid fa-comment-dots"></i></a>
        </div>
        <div class="contact-card">
          <span class="f-icon orange"><i class="fa-solid fa-location-dot"></i></span>
          <h3>Find us</h3>
          <div class="row-text"><?php echo $address; ?></div>
          <a class="row-link" href="https://www.google.com/maps/search/?api=1&amp;query=<?php echo urlencode('Railway Station, Vadodara'); ?>" target="_blank" rel="noopener">Get directions<i class="fa-solid fa-diamond-turn-right"></i></a>
        </div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0">
    <div class="wrap">
      <iframe class="map-frame" title="Map: <?php echo $address; ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
        src="https://www.google.com/maps?q=<?php echo urlencode('Railway Station, Vadodara'); ?>&amp;output=embed"></iframe>
    </div>
  </section>

  <section class="section" style="padding-top:0">
    <div class="wrap">
      <div class="cta">
        <div><h2>Have feedback for us?</h2><p>Tell us how we're doing. We read every message.</p></div>
        <div class="cta-actions">
          <a href="feedback.php" class="btn">Give feedback <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </section>

  <?php include('ui/footer.php'); ?>

  <script src="assets/sms.js?v=<?php echo @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/sms.js'); ?>"></script>
</body>
</html>
