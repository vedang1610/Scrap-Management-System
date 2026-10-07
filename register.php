<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

// Refill the form after an error so nothing has to be typed again
function old($name) { return isset($_POST[$name]) ? htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8') : ''; }

$questions = array(
     "In what city were you born?",
     "What is your mothers maiden name?",
     "What high school did you attend?",
     "What is the name of your first school?",
     "What was the make of your first car?",
     "What was your favorite food as a child?",
);

$pageTitle = 'Register';
$activePage = 'account';
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
               <div class="crumbs"><a href="index.php">Home</a><i class="fa-solid fa-chevron-right"></i><span>Register</span></div>
               <h1>Create your account</h1>
               <p>Register once to sell scrap, place orders and track your pickups.</p>
          </div>
     </section>

     <main class="wrap reg">
          <div class="reg-layout">
               <aside class="perks">
                    <h3>Why join us?</h3>
                    <p>Thousands of people already recycle their scrap with us.</p>
                    <ul>
                         <li><i class="fa-solid fa-indian-rupee-sign"></i><div><strong>Best price, instantly</strong>Get a fair price for your scrap.</div></li>
                         <li><i class="fa-solid fa-truck"></i><div><strong>Free pickup</strong>We collect scrap from your doorstep.</div></li>
                         <li><i class="fa-solid fa-file-signature"></i><div><strong>Paperwork sorted</strong>We handle the official paperwork.</div></li>
                         <li><i class="fa-solid fa-leaf"></i><div><strong>Responsible recycling</strong>Following government scrap rules.</div></li>
                    </ul>
               </aside>

               <div class="reg-card">
                    <h2>User registration</h2>
                    <p>Already registered? <a href="admin/userLogin.php">Login here</a></p>

                    <form id="userForm" method="POST" enctype="multipart/form-data" data-validate novalidate>
                         <div class="form-grid">
                              <div class="form-section span-2">Personal details</div>

                              <div class="field span-2">
                                   <label for="txtName">Full name <span class="req">*</span></label>
                                   <div class="control"><i class="fa-regular fa-user"></i>
                                        <input class="input" type="text" id="txtName" name="txtName" placeholder="Enter your full name" autocomplete="name" value="<?php echo old('txtName'); ?>" required>
                                   </div>
                              </div>

                              <div class="field">
                                   <label for="txtPhone">Phone <span class="req">*</span></label>
                                   <div class="control"><i class="fa-solid fa-phone"></i>
                                        <input class="input" type="tel" id="txtPhone" name="txtPhone" placeholder="10 digit mobile number" inputmode="numeric" autocomplete="tel-national" pattern="[1-9][0-9]{9}" maxlength="10" value="<?php echo old('txtPhone'); ?>" required>
                                   </div>
                                   <span class="hint">10 digits, without +91</span>
                              </div>

                              <div class="field">
                                   <label for="txtEmail">Email <span class="req">*</span></label>
                                   <div class="control"><i class="fa-regular fa-envelope"></i>
                                        <input class="input" type="email" id="txtEmail" name="txtEmail" placeholder="you@example.com" autocomplete="email" inputmode="email" value="<?php echo old('txtEmail'); ?>" required>
                                   </div>
                              </div>

                              <div class="field span-2">
                                   <label for="txtAddress">Address <span class="req">*</span></label>
                                   <div class="control top"><i class="fa-solid fa-location-dot"></i>
                                        <textarea class="input" id="txtAddress" name="txtAddress" placeholder="House no, street, city, state" autocomplete="street-address" required><?php echo old('txtAddress'); ?></textarea>
                                   </div>
                              </div>

                              <div class="field span-2">
                                   <label for="idProofImage">ID proof photo <span class="req">*</span></label>
                                   <label class="dropzone" for="idProofImage">
                                        <span class="dz-thumb"><i class="fa-regular fa-id-card"></i></span>
                                        <span class="dz-text"><strong>Tap to upload your ID proof</strong><span>PNG or JPG, max 1 MB</span></span>
                                        <input type="file" id="idProofImage" name="idProofImage" accept="image/png,image/jpeg" required>
                                   </label>
                              </div>

                              <div class="form-section span-2">Login &amp; security</div>

                              <div class="field">
                                   <label for="txtPassword">Password <span class="req">*</span></label>
                                   <div class="control has-toggle"><i class="fa-solid fa-lock"></i>
                                        <input class="input" type="password" id="txtPassword" name="txtPassword" placeholder="Create a password" autocomplete="new-password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-pass required>
                                        <button type="button" class="toggle-pass" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
                                   </div>
                                   <span class="hint">8+ characters with uppercase, lowercase and a number</span>
                              </div>

                              <div class="field">
                                   <label for="txtPasswordC">Confirm password <span class="req">*</span></label>
                                   <div class="control has-toggle"><i class="fa-solid fa-lock"></i>
                                        <input class="input" type="password" id="txtPasswordC" name="txtPasswordC" placeholder="Type it again" autocomplete="new-password" data-confirm required>
                                        <button type="button" class="toggle-pass" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
                                   </div>
                                   <span class="hint">Must match the password</span>
                              </div>

                              <div class="field">
                                   <label for="txtQuestion">Security question <span class="req">*</span></label>
                                   <div class="control"><i class="fa-solid fa-shield-halved"></i>
                                        <select class="input" name="txtQuestion" id="txtQuestion" required>
                                             <option value="">Select a question</option>
                                             <?php foreach ($questions as $q) { ?>
                                                  <option value="<?php echo $q; ?>" <?php echo old('txtQuestion') == $q ? 'selected' : ''; ?>><?php echo $q; ?></option>
                                             <?php } ?>
                                        </select>
                                   </div>
                                   <span class="hint">Used if you forget your password</span>
                              </div>

                              <div class="field">
                                   <label for="txtAnswer">Answer <span class="req">*</span></label>
                                   <div class="control"><i class="fa-regular fa-comment"></i>
                                        <input class="input" type="text" id="txtAnswer" name="txtAnswer" placeholder="Your answer" autocomplete="off" value="<?php echo old('txtAnswer'); ?>" required>
                                   </div>
                              </div>

                              <div class="span-2">
                                   <button type="submit" class="btn btn-primary btn-block"><i class="fa-solid fa-user-plus"></i> Create account</button>
                              </div>
                         </div>
                    </form>
               </div>
          </div>
     </main>

     <?php include('ui/footer.php'); ?>

     <script src="assets/sms.js"></script>
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>
</html>

<?php
if(isset($_POST['txtName']))
{
    if(isset($_FILES['idProofImage']['name']))
    {
	   $uploadResult = uploadFile($_FILES,"idProofImage","admin/upload_images/", array("png","jpg","jpeg") );

	   if($uploadResult['status'] == 'success')
	   {
			$name = mysqli_real_escape_string($con, trim($_REQUEST['txtName']));
			$phone = mysqli_real_escape_string($con, trim($_REQUEST['txtPhone']));
			$email = mysqli_real_escape_string($con, trim($_REQUEST['txtEmail']));
			$password = $_REQUEST['txtPassword'];
               $passwordC = $_REQUEST['txtPasswordC'];
			$address = mysqli_real_escape_string($con, trim($_REQUEST['txtAddress']));
               $question = mysqli_real_escape_string($con, $_REQUEST['txtQuestion']);
               $answer = mysqli_real_escape_string($con, trim($_REQUEST['txtAnswer']));
			$status = 1;
			$idproof = $uploadResult['image'];

               if($password != $passwordC)
               {
                    runJavascript('swal("Password and Confirm Password should be same.", "", "error")');
                    exit;
               }
               $password = mysqli_real_escape_string($con, $password);

			$sql = "INSERT INTO `users`(`name`,`email`, `pass`, `phone`,`address`, `idproof`, `status`,`question`, `answer`)
				VALUES ('$name','$email','$password','$phone','$address','$idproof','$status','$question','$answer')";
			if(insert_update_delete_data($sql) == true)
			{
                    $msg = getSwalMessgage("You're Registered Successfully, please Login.","",'window.location = "admin/userLogin.php"','window.location = "admin/userLogin.php"',"success");
                    runJavascript($msg);
			}
			else
			{
				runJavascript('swal("Error !!!.", "", "error")');
			}
	   }
	   else
	   {
               $why = isset($uploadResult['message']) ? addslashes($uploadResult['message']) : '';
               runJavascript('swal("Image upload failed", "' . $why . '", "error")');
	   }
    }

  }

?>
