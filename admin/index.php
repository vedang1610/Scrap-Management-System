<?php
  session_start();
  include('db.php');

  if(isset($_SESSION['adminId']))
  {
      header('location:dashboard.php');
  }

  $pageTitle = 'Admin Login';
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
    <h1>Admin panel</h1>
    <p>Manage scrap products, categories, orders, staff and users in one place.</p>
    <ul class="auth-points">
      <li><i class="fa-solid fa-boxes-stacked"></i><span>Add and update scrap products and stock</span></li>
      <li><i class="fa-solid fa-receipt"></i><span>Review orders and user bookings</span></li>
      <li><i class="fa-solid fa-users"></i><span>Assign staff and manage users</span></li>
    </ul>
  </section>

  <section class="auth-panel">
    <form class="auth-form" action="" method="post" data-validate novalidate>
      <span class="pill admin"><i class="fa-solid fa-user-shield"></i> Admin access</span>
      <h2>Welcome back</h2>
      <p class="sub">Sign in to continue to the dashboard.</p>

      <div class="field">
        <label for="txtEmail">Email</label>
        <div class="control"><i class="fa-regular fa-envelope"></i>
          <input class="input" type="email" id="txtEmail" name="txtEmail" placeholder="admin@example.com" autocomplete="username" inputmode="email" value="<?php echo isset($_POST['txtEmail']) ? htmlspecialchars($_POST['txtEmail'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
        </div>
      </div>

      <div class="field">
        <label for="txtPassword">Password</label>
        <div class="control has-toggle"><i class="fa-solid fa-lock"></i>
          <input class="input" type="password" id="txtPassword" name="txtPassword" placeholder="Enter password" autocomplete="current-password" required>
          <button type="button" class="toggle-pass" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign in</button>

      <div class="divider">or</div>
      <a href="userLogin.php" class="btn btn-ghost btn-block"><i class="fa-regular fa-user"></i> User login</a>
      <p class="auth-foot"><a href="../index.php">&larr; Back to website</a></p>
    </form>
  </section>
</div>

<script src="../assets/sms.js?v=<?php echo @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/sms.js'); ?>"></script>
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
            $sql="select * from admin where email='$email' and pass='$password'";

            $r=mysqli_query($con,$sql);
            if($r  && mysqli_num_rows($r)==1){
                    $res=mysqli_fetch_assoc($r);
                    $_SESSION['adminId']=$res['id'];
                    $_SESSION['adminName']=$res['name'];
                    $_SESSION['adminEmail']=$res['email'];
                    $_SESSION['adminPassword']=$res['pass'];
                    $_SESSION['adminDate']=$res['dt'];

                    echo "<script type='text/javascript'>  window.location='dashboard.php'; </script>";
            }
            else
            {
                echo '<script> swal("Username Or Password is Incorrect.", "Check your Username And Password And Try Again.", "error");
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
