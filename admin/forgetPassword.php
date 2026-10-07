<?php
  session_start();
  include('db.php');

  if(isset($_SESSION['userId']))
  {
      header('location:userDashboard.php');
      exit;
  }

  $questions = array(
      "In what city were you born?",
      "What is your mothers maiden name?",
      "What high school did you attend?",
      "What is the name of your first school?",
      "What was the make of your first car?",
      "What was your favorite food as a child?",
  );
  $pageTitle = 'Forgot Password';
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
      <a href="userLogin.php" class="back" aria-label="Back to login"><i class="fa-solid fa-arrow-left"></i></a>
      <a href="../index.php" class="auth-logo"><img src="../images/logo1.png" alt="Scrap Management System"></a>
    </div>
    <h1>Forgot your password?</h1>
    <p>Answer the security question you picked when you registered and we'll show your password.</p>
    <ul class="auth-points">
      <li><i class="fa-solid fa-envelope"></i><span>Enter the email you registered with</span></li>
      <li><i class="fa-solid fa-shield-halved"></i><span>Choose your security question</span></li>
      <li><i class="fa-solid fa-key"></i><span>Type the answer to get your password</span></li>
    </ul>
  </section>

  <section class="auth-panel">
    <form class="auth-form" action="" method="post" data-validate novalidate>
      <span class="pill"><i class="fa-solid fa-key"></i> Account recovery</span>
      <h2>Forgot password</h2>
      <p class="sub">We'll help you get back into your account.</p>

      <div class="field">
        <label for="txtEmail">Email</label>
        <div class="control"><i class="fa-regular fa-envelope"></i>
          <input class="input" type="email" id="txtEmail" name="txtEmail" placeholder="you@example.com" autocomplete="username" inputmode="email" value="<?php echo isset($_POST['txtEmail']) ? htmlspecialchars($_POST['txtEmail'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
        </div>
      </div>

      <div class="field">
        <label for="txtQuestion">Security question</label>
        <div class="control"><i class="fa-solid fa-shield-halved"></i>
          <select class="input" name="txtQuestion" id="txtQuestion" required>
            <option value="">Select your question</option>
            <?php foreach ($questions as $q) { ?>
              <option value="<?php echo $q; ?>" <?php echo (isset($_POST['txtQuestion']) && $_POST['txtQuestion'] == $q) ? 'selected' : ''; ?>><?php echo $q; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="field">
        <label for="txtAnswer">Answer</label>
        <div class="control"><i class="fa-regular fa-comment"></i>
          <input class="input" type="text" id="txtAnswer" name="txtAnswer" placeholder="Your answer" autocomplete="off" required>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-unlock"></i> Recover password</button>

      <p class="auth-foot">Remembered it? <a href="userLogin.php">Back to login</a> &middot; <a href="../register.php">Register</a></p>
    </form>
  </section>
</div>

<script src="../assets/sms.js?v=<?php echo @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/sms.js'); ?>"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>
</html>

<?php
include('functions.php');
if(isset($_REQUEST['txtEmail']))
{
     $email = mysqli_real_escape_string($con, trim($_REQUEST['txtEmail']));
     $question = mysqli_real_escape_string($con, $_REQUEST['txtQuestion']);
     $answer = mysqli_real_escape_string($con, trim($_REQUEST['txtAnswer']));

     if($email!="" && $question!="" && $answer != "")
     {
               $sql="select * from users where email='$email' and question='$question' and answer = '$answer' ";

               $data = selectData($sql);
               if(is_array($data) && count($data) >= 1)
               {
                    $data = $data[0];
                    $message = "Your Password is ".addslashes($data['pass']). " .";

                    $msg = getSwalMessgage($message,"",'window.location = "userLogin.php"','window.location = "userLogin.php"',"success");
                    runJavascript($msg);
               }
               else
               {
                    echo '<script> swal("Email, Security Question Or Answer is Incorrect.", "", "error");
                         </script>';
               }

     }
     else
     {
      echo '<script> swal("Fields can not be Empty.", "White Space or Blank Data Not Allowed.", "info");
      </script>';
     }
}


?>
