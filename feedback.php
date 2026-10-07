<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

function old($name) { return isset($_POST[$name]) ? htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8') : ''; }

// Prefill name and email for logged-in users
$defName = isset($_SESSION['userName']) ? $_SESSION['userName'] : '';
$defEmail = isset($_SESSION['userEmail']) ? $_SESSION['userEmail'] : '';

$pageTitle = 'Feedback';
$activePage = 'feedback';
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
      <div class="crumbs"><a href="index.php">Home</a><i class="fa-solid fa-chevron-right"></i><span>Feedback</span></div>
      <h1>Feedback</h1>
      <p>Tell us what you think about our service. It helps us get better.</p>
    </div>
  </section>

  <section class="section">
    <div class="wrap split">
      <div class="panel">
        <h2>Send us your feedback</h2>
        <p>All fields are required.</p>
        <form action="" method="POST" data-validate novalidate>
          <div class="form-grid">
            <div class="field span-2">
              <label for="fbName">Name <span class="req">*</span></label>
              <div class="control"><i class="fa-regular fa-user"></i>
                <input class="input" type="text" id="fbName" name="name" maxlength="100" placeholder="Your name" autocomplete="name" value="<?php echo old('name') ?: htmlspecialchars($defName); ?>" required>
              </div>
            </div>
            <div class="field">
              <label for="fbEmail">Email <span class="req">*</span></label>
              <div class="control"><i class="fa-regular fa-envelope"></i>
                <input class="input" type="email" id="fbEmail" name="email" maxlength="50" placeholder="you@example.com" autocomplete="email" inputmode="email" value="<?php echo old('email') ?: htmlspecialchars($defEmail); ?>" required>
              </div>
            </div>
            <div class="field">
              <label for="fbPhone">Phone <span class="req">*</span></label>
              <div class="control"><i class="fa-solid fa-phone"></i>
                <input class="input" type="tel" id="fbPhone" name="phone" placeholder="10 digit mobile number" inputmode="numeric" autocomplete="tel-national" pattern="[0-9]{10}" maxlength="10" value="<?php echo old('phone'); ?>" required>
              </div>
              <span class="hint">10 digits, without +91</span>
            </div>
            <div class="field span-2">
              <label for="fbMessage">Message <span class="req">*</span></label>
              <div class="control top"><i class="fa-regular fa-comment"></i>
                <textarea class="input" id="fbMessage" name="message" maxlength="255" rows="5" placeholder="Write your feedback here" data-counter="fbCount" required><?php echo old('message'); ?></textarea>
              </div>
              <span class="counter" id="fbCount"></span>
            </div>
            <div class="span-2">
              <button class="btn btn-primary btn-block" type="submit"><i class="fa-solid fa-paper-plane"></i> Send feedback</button>
            </div>
          </div>
        </form>
      </div>

      <aside class="side-card">
        <h3>We'd love to hear from you</h3>
        <p>Your feedback goes straight to our team. For anything urgent, reach us directly.</p>
        <ul>
          <li><i class="fa-solid fa-phone"></i><a href="tel:+918140599726">+91 8140599726</a></li>
          <li><i class="fa-regular fa-envelope"></i><a href="mailto:vedang16102000@gmail.com" style="word-break:break-all">vedang16102000@gmail.com</a></li>
          <li><i class="fa-solid fa-location-dot"></i><span>Nr Railway Station, Vadodara</span></li>
        </ul>
      </aside>
    </div>
  </section>

  <?php include('ui/footer.php'); ?>

  <script src="assets/sms.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>
</html>
<?php

if(isset($_POST['name']))
{
			$name = mysqli_real_escape_string($con, trim($_REQUEST['name']));
			$email = mysqli_real_escape_string($con, trim($_REQUEST['email']));
			$phone = mysqli_real_escape_string($con, trim($_REQUEST['phone']));
			$message = mysqli_real_escape_string($con, trim($_REQUEST['message']));

			$sql = "INSERT INTO `feedback`(`name`,`email`, `phone`,`message`)
				VALUES ('$name','$email','$phone','$message')";
			if(insert_update_delete_data($sql) == true)
			{
                    $msg = getSwalMessgage("Thank you! Your feedback was sent successfully.","",'window.location = "feedback.php"','window.location = "feedback.php"',"success");
                    runJavascript($msg);
			}
			else
			{
				runJavascript('swal("Error !!!.", "", "error")');
			}
  }

?>
