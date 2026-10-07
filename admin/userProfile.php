<?php include_once("userCheckLogin.php");
include_once("functions.php");
include_once("ui/user_layout.php");

$userId = (int)$_SESSION['userId'];
$rows = selectData("SELECT * FROM `users` WHERE id='$userId'");
if (!count($rows)) redirect_to('userLogout.php');
$u = $rows[0];

$error = '';
if (isset($_POST['txtName']))
{
    $name = trim($_POST['txtName']);
    $phone = trim($_POST['txtPhone']);
    $email = trim($_POST['txtEmail']);
    $password = trim($_POST['txtPassword']);
    $address = trim($_POST['txtAddress']);
    $idproof = $u['idproof'];

    if ($name === '') $error = 'Please enter your name.';
    elseif (!preg_match('/^[0-9]{10}$/', $phone)) $error = 'Phone must be 10 digits.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Please enter a valid email address.';
    elseif (count_of("SELECT COUNT(*) FROM users WHERE email='" . db_escape($email) . "' AND id != '$userId'")) $error = 'Another account already uses this email.';
    elseif (strlen($password) < 4) $error = 'Password must be at least 4 characters.';
    elseif ($address === '') $error = 'Please enter your address.';
    elseif (isset($_FILES['idProofImage']) && $_FILES['idProofImage']['error'] != UPLOAD_ERR_NO_FILE) {
        $up = uploadFile($_FILES, "idProofImage", "upload_images/", array("png", "jpg", "jpeg"));
        if ($up['status'] == 'success') $idproof = $up['image'];
        else $error = 'ID proof: ' . $up['message'];
    }

    if (!$error)
    {
        $ok = insert_update_delete_data("UPDATE `users` SET
                  `name` = '" . db_escape($name) . "', `email` = '" . db_escape($email) . "',
                  `pass` = '" . db_escape($password) . "', `phone` = '" . db_escape($phone) . "',
                  `address` = '" . db_escape($address) . "', `idproof` = '" . db_escape($idproof) . "'
                  WHERE id = '$userId'");
        if ($ok) {
            $_SESSION['userName'] = $name;
            $_SESSION['userEmail'] = $email;
            $_SESSION['userImage'] = $idproof;
            redirect_to('userProfile.php', 'Profile updated.');
        }
        $error = 'Could not update your profile.';
    }
}

$v = function ($post, $col) use ($u) { return isset($_POST[$post]) ? $_POST[$post] : $u[$col]; };
$proof = 'upload_images/' . $u['idproof'];

user_start('profile', 'Profile', 'Member since ' . date('M Y', strtotime($u['dt'])));
?>

<div class="box profile-head">
  <label for="idProofImage" class="profile-photo" title="Change photo">
    <?php echo user_avatar('avatar-xl', $u['name'], $u['idproof']); ?>
    <span class="cam"><i class="fa-solid fa-camera"></i></span>
  </label>
  <div style="min-width:0">
    <h2><?php echo e($u['name']); ?></h2>
    <p class="muted small"><?php echo e($u['email']); ?></p>
    <p class="muted small"><i class="fa-solid fa-phone" style="font-size:11px"></i> <?php echo e($u['phone']); ?></p>
  </div>
</div>

<form class="two-col" method="POST" enctype="multipart/form-data" data-validate novalidate>
  <div class="box">
    <div class="box-head"><h2>Your details</h2></div>
    <div class="box-body form-grid">
      <div class="field span-2">
        <label for="txtName">Full name <span class="req">*</span></label>
        <div class="control"><i class="fa-regular fa-user"></i>
          <input class="input" type="text" id="txtName" name="txtName" maxlength="100" autocomplete="name" value="<?php echo e($v('txtName', 'name')); ?>" required>
        </div>
      </div>
      <div class="field">
        <label for="txtPhone">Phone <span class="req">*</span></label>
        <div class="control"><i class="fa-solid fa-phone"></i>
          <input class="input" type="tel" id="txtPhone" name="txtPhone" inputmode="numeric" pattern="[0-9]{10}" maxlength="10" autocomplete="tel-national" value="<?php echo e($v('txtPhone', 'phone')); ?>" required>
        </div>
        <span class="hint">10 digits, without +91</span>
      </div>
      <div class="field">
        <label for="txtEmail">Email <span class="req">*</span></label>
        <div class="control"><i class="fa-regular fa-envelope"></i>
          <input class="input" type="email" id="txtEmail" name="txtEmail" maxlength="100" autocomplete="email" value="<?php echo e($v('txtEmail', 'email')); ?>" required>
        </div>
        <span class="hint">You log in with this email</span>
      </div>
      <div class="field span-2">
        <label for="txtAddress">Address <span class="req">*</span></label>
        <div class="control top"><i class="fa-solid fa-location-dot"></i>
          <textarea class="input" id="txtAddress" name="txtAddress" rows="3" autocomplete="street-address" required><?php echo e($v('txtAddress', 'address')); ?></textarea>
        </div>
        <span class="hint">Used as your default delivery address</span>
      </div>
      <div class="field span-2">
        <label for="txtPassword">Password <span class="req">*</span></label>
        <div class="control has-toggle"><i class="fa-solid fa-lock"></i>
          <input class="input" type="password" id="txtPassword" name="txtPassword" minlength="4" maxlength="100" autocomplete="new-password" value="<?php echo e($v('txtPassword', 'pass')); ?>" required>
          <button type="button" class="toggle-pass" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>
    </div>
  </div>

  <div class="stack">
    <div class="box">
      <div class="box-head"><h2>Profile photo</h2></div>
      <div class="box-body stack">
        <?php if ($u['idproof'] && file_exists($proof)) { ?>
          <a href="<?php echo e($proof); ?>" data-lightbox class="current-img"><img src="<?php echo e($proof); ?>" alt="">Current photo. Tap to view.</a>
        <?php } ?>
        <label class="dropzone" for="idProofImage">
          <span class="dz-thumb"><i class="fa-regular fa-image"></i></span>
          <span class="dz-text"><strong><?php echo user_photo($u['idproof']) ? 'Change photo' : 'Add a photo'; ?></strong><span>Optional · PNG or JPG, max 5 MB · Click Save to apply</span></span>
          <input type="file" id="idProofImage" name="idProofImage" accept="image/png,image/jpeg">
        </label>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save changes</button>
      <a href="userLogout.php" class="btn btn-ghost"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </div>
  </div>
</form>

<?php
// Preview the chosen photo in the big avatar straight away
$preview = '<script>
document.getElementById("idProofImage").addEventListener("change", function () {
  var f = this.files[0]; if (!f || !/^image\//.test(f.type)) return;
  var r = new FileReader();
  r.onload = function (ev) {
    var av = document.querySelector(".profile-photo .avatar");
    av.innerHTML = "<img alt=\"\">"; av.querySelector("img").src = ev.target.result; av.classList.add("has-photo");
  };
  r.readAsDataURL(f);
});
</script>';
user_end($preview . ($error ? '<script>adminToast(' . json_encode($error) . ', "error");</script>' : '')); ?>
