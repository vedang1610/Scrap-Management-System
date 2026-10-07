<?php
  session_start();
  include('db.php');

  if(isset($_SESSION['userId']))
  {
      header('location:userDashboard.php');
  }

  $pageTitle = 'User Login';
  $assetBase = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('../ui/head.php'); ?>
</head>
<body class="sms auth">

<div class="auth-shell">
  <section class="auth-visual">
    <div class="auth-top">
      <a href="../index.php" class="back" aria-label="Back to website"><i class="fa-solid fa-arrow-left"></i></a>
      <a href="../index.php" class="auth-logo"><img src="../images/logo1.png" alt="Scrap Management System"></a>
    </div>
    <h1>Turn scrap into cash</h1>
    <p>Log in to sell scrap, place orders and track your pickups.</p>
    <ul class="auth-points">
      <li><i class="fa-solid fa-indian-rupee-sign"></i><span>Best price for your scrap, instantly</span></li>
      <li><i class="fa-solid fa-truck"></i><span>Free collection from your doorstep</span></li>
      <li><i class="fa-solid fa-leaf"></i><span>Responsible, rule-following recycling</span></li>
    </ul>
  </section>

  <section class="auth-panel">
    <form class="auth-form" action="" method="post" data-validate novalidate>
      <span class="pill"><i class="fa-regular fa-user"></i> User account</span>
      <h2>Login</h2>
      <p class="sub">Welcome back! Enter your details below.</p>

      <div class="field">
        <label for="txtEmail">Email</label>
        <div class="control"><i class="fa-regular fa-envelope"></i>
          <input class="input" type="email" id="txtEmail" name="txtEmail" placeholder="you@example.com" autocomplete="username" inputmode="email" value="<?php echo isset($_POST['txtEmail']) ? htmlspecialchars($_POST['txtEmail'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
        </div>
      </div>

      <div class="field">
        <label for="txtPassword">Password</label>
        <div class="control has-toggle"><i class="fa-solid fa-lock"></i>
          <input class="input" type="password" id="txtPassword" name="txtPassword" placeholder="Enter password" autocomplete="current-password" required>
          <button type="button" class="toggle-pass" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>

      <div class="auth-links">
        <span></span>
        <a href="forgetPassword.php">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-arrow-right-to-bracket"></i> Login</button>

      <p class="auth-foot">New here? <a href="../register.php">Create an account</a></p>
    </form>
  </section>
</div>

<script src="../assets/sms.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>
</html>

<?php
if(isset($_REQUEST['txtEmail']))
{
    $email = mysqli_real_escape_string($con, trim($_REQUEST['txtEmail']));
    $password = mysqli_real_escape_string($con, trim($_REQUEST['txtPassword']));

     if($email!="" && $password!="")
     {
            $sql="select * from users where email='$email' and pass='$password' and status = 1";

            $r=mysqli_query($con,$sql);
            if($r  && mysqli_num_rows($r)==1){
                    $res=mysqli_fetch_assoc($r);
                    $_SESSION['userId']=$res['id'];
                    $_SESSION['userName']=$res['name'];
                    $_SESSION['userEmail']=$res['email'];
                    $_SESSION['userPassword']=$res['pass'];
                    $_SESSION['userDate']=$res['dt'];
                    $_SESSION['userImage']=$res['idproof'];

                    echo "<script type='text/javascript'>  window.location='../index.php'; </script>";
            }
            else
            {
                echo '<script> swal("Username Or Password is Incorrect.", "Check your Username And Password And Try Again or youre not authorised contact to admin for more info.", "error");
                    </script>';
            }

     }
     else
     {
      echo '<script> swal("Username And Password can not be Empty.", "White Space or Blank Data Not Allowed.", "info");
      </script>';
     }
}


?>
