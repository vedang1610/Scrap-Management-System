<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

$userId = (int)$_SESSION['adminId'];
$rows = selectData("SELECT * FROM `admin` WHERE id='$userId'");
if (!is_array($rows) || !count($rows)) redirect_to('logout.php');
$admin = $rows[0];

$error = '';
if (isset($_POST['txtName']))
{
    $name = trim($_POST['txtName']);
    $email = trim($_POST['txtEmail']);
    $password = trim($_POST['txtPassword']);

    if ($name === '') $error = 'Please enter your name.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Please enter a valid email address.';
    elseif (strlen($password) < 4) $error = 'Password must be at least 4 characters.';
    elseif (count_of("SELECT COUNT(*) FROM admin WHERE email='" . db_escape($email) . "' AND id != '$userId'")) $error = 'Another admin already uses this email.';
    else {
        $ok = insert_update_delete_data("UPDATE `admin` SET `name`='" . db_escape($name) . "', `email`='" . db_escape($email) . "',
                                         `pass`='" . db_escape($password) . "' WHERE id='$userId'");
        if ($ok) {
            // Keep the sidebar and top bar in sync with the new details
            $_SESSION['adminName'] = $name;
            $_SESSION['adminEmail'] = $email;
            redirect_to('adminProfile.php', 'Profile updated.');
        }
        $error = 'Could not update the profile.';
    }
}

$v = function ($post, $col) use ($admin) { return isset($_POST[$post]) ? $_POST[$post] : $admin[$col]; };

admin_start('profile', 'Profile', 'Your admin account');
?>

<div class="two-col even" style="max-width:980px">
  <div class="box">
    <div class="box-body" style="display:flex;align-items:center;gap:16px">
      <span class="avatar" style="width:64px;height:64px;font-size:22px"><?php echo e(initials($admin['name'])); ?></span>
      <div><h2 style="font-size:20px;font-weight:800"><?php echo e($admin['name']); ?></h2>
        <p class="muted small"><?php echo e($admin['email']); ?></p>
        <p class="muted small">Admin since <?php echo date('d M Y', strtotime($admin['dt'])); ?></p></div>
    </div>
    <div class="box-body" style="padding-top:0">
      <div class="quick" style="grid-template-columns:1fr 1fr">
        <a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i>View website</a>
        <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>
      </div>
    </div>
  </div>

  <div class="box">
    <div class="box-head"><h2>Edit details</h2></div>
    <form class="box-body stack" method="POST" data-validate novalidate>
      <div class="field">
        <label for="txtName">Name <span class="req">*</span></label>
        <div class="control"><i class="fa-regular fa-user"></i>
          <input class="input" type="text" id="txtName" name="txtName" maxlength="100" autocomplete="name" value="<?php echo e($v('txtName', 'name')); ?>" required>
        </div>
      </div>
      <div class="field">
        <label for="txtEmail">Email <span class="req">*</span></label>
        <div class="control"><i class="fa-regular fa-envelope"></i>
          <input class="input" type="email" id="txtEmail" name="txtEmail" maxlength="100" autocomplete="email" value="<?php echo e($v('txtEmail', 'email')); ?>" required>
        </div>
        <span class="hint">You log in with this email</span>
      </div>
      <div class="field">
        <label for="txtPassword">Password <span class="req">*</span></label>
        <div class="control has-toggle"><i class="fa-solid fa-lock"></i>
          <input class="input" type="password" id="txtPassword" name="txtPassword" minlength="4" maxlength="100" autocomplete="new-password" value="<?php echo e($v('txtPassword', 'pass')); ?>" required>
          <button type="button" class="toggle-pass" aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
        </div>
        <span class="hint">At least 4 characters</span>
      </div>
      <div class="form-actions"><button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Save changes</button></div>
    </form>
  </div>
</div>

<?php admin_end($error ? '<script>adminToast(' . json_encode($error) . ', "error");</script>' : ''); ?>
