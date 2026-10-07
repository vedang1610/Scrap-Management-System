<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

$id = isset($_GET['getData']) ? (int)$_GET['getData'] : 0;
$data = null;
if ($id)
{
    $rows = selectData("SELECT * FROM `category` WHERE id='$id'");
    if (!is_array($rows) || !count($rows)) redirect_to('category.php', 'Category not found.', 'error');
    $data = $rows[0];
}

$error = '';
if (isset($_POST['name']))
{
    $name = db_escape($_POST['name']);
    $photo = $data ? $data['image'] : '';
    $hasFile = isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE;

    if ($name === '') {
        $error = 'Please enter a category name.';
    } elseif ($hasFile) {
        $uploadResult = uploadFile($_FILES, "photo", "upload_images/", array("png", "jpg", "jpeg"));
        if ($uploadResult['status'] == 'success') $photo = $uploadResult['image'];
        else $error = $uploadResult['message'];
    } elseif (!$data) {
        $error = 'Please choose a photo for the category.';
    }

    if (!$error)
    {
        $photo = db_escape($photo);
        if ($data) {
            $ok = insert_update_delete_data("UPDATE `category` SET `name`='$name', `image`='$photo' WHERE id='$id'");
            $msg = 'Category updated.';
        } else {
            $dt = date("Y-m-d");
            $ok = insert_update_delete_data("INSERT INTO `category`(`name`, `image`, `dt`) VALUES ('$name','$photo','$dt')");
            $msg = 'Category added.';
        }
        if ($ok) redirect_to('category.php', $msg);
        $error = 'Could not save the category. Please try again.';
    }
}

$nameValue = isset($_POST['name']) ? $_POST['name'] : ($data ? $data['name'] : '');
admin_start('category', $data ? 'Edit category' : 'Add category', $data ? e($data['name']) : 'Create a new product category', '', 'category.php');
?>

<div class="box" style="max-width:720px">
  <form class="box-body stack" method="POST" enctype="multipart/form-data" data-validate novalidate>
    <div class="field">
      <label for="name">Category name <span class="req">*</span></label>
      <div class="control"><i class="fa-solid fa-tag"></i>
        <input class="input" type="text" id="name" name="name" maxlength="255" placeholder="e.g. Plastic, Metal, Paper" value="<?php echo e($nameValue); ?>" required>
      </div>
    </div>

    <div class="field">
      <label for="photo">Photo <?php if (!$data) { ?><span class="req">*</span><?php } ?></label>
      <?php if ($data && $data['image']) { ?>
        <div class="current-img"><img src="upload_images/<?php echo e($data['image']); ?>" alt="">Current photo. Choose a new one below to replace it.</div>
      <?php } ?>
      <label class="dropzone" for="photo">
        <span class="dz-thumb"><i class="fa-regular fa-image"></i></span>
        <span class="dz-text"><strong>Tap to choose a photo</strong><span>PNG or JPG, max 1 MB</span></span>
        <input type="file" id="photo" name="photo" accept="image/png,image/jpeg" <?php echo $data ? '' : 'required'; ?>>
      </label>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> <?php echo $data ? 'Save changes' : 'Add category'; ?></button>
      <a href="category.php" class="btn btn-ghost">Cancel</a>
    </div>
  </form>
</div>

<?php admin_end($error ? '<script>adminToast(' . json_encode($error) . ', "error");</script>' : ''); ?>
