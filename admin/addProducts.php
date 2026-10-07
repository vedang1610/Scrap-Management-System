<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

$id = isset($_GET['getData']) ? (int)$_GET['getData'] : 0;
$data = null;
if ($id)
{
    $rows = selectData("SELECT * FROM `products` WHERE id='$id'");
    if (!is_array($rows) || !count($rows)) redirect_to('products.php', 'Product not found.', 'error');
    $data = $rows[0];
}
$categories = selectData("SELECT * FROM `category` ORDER BY name");

$error = '';
if (isset($_POST['name']))
{
    $category = (int)$_POST['category'];
    $name = db_escape($_POST['name']);
    $price = trim($_POST['price']);
    $description = db_escape($_POST['description']);
    $status = isset($_POST['status']) ? 1 : 0;
    $photo = $data ? $data['image'] : '';
    $hasFile = isset($_FILES['photo']) && $_FILES['photo']['error'] != UPLOAD_ERR_NO_FILE;

    if (!$category || !count_of("SELECT COUNT(*) FROM category WHERE id='$category'")) $error = 'Please choose a category.';
    elseif ($name === '') $error = 'Please enter a product name.';
    elseif (!is_numeric($price) || $price < 0) $error = 'Price must be a number, e.g. 20 or 20.50';
    elseif ($description === '') $error = 'Please enter a description.';
    elseif ($hasFile) {
        $uploadResult = uploadFile($_FILES, "photo", "upload_images/", array("png", "jpg", "jpeg"));
        if ($uploadResult['status'] == 'success') $photo = $uploadResult['image'];
        else $error = 'Main photo: ' . $uploadResult['message'];
    } elseif (!$data) $error = 'Please choose a main photo.';

    if (!$error)
    {
        $price = (float)$price;
        $photo = db_escape($photo);
        if ($data) {
            $ok = insert_update_delete_data("UPDATE `products` SET `category_id`='$category', `name`='$name', `image`='$photo',
                                             `description`='$description', `price`='$price', `status`='$status' WHERE id='$id'");
            $productId = $id;
            $msg = 'Product updated.';
        } else {
            $dt = date("Y-m-d");
            $ok = insert_update_delete_data("INSERT INTO `products`(`category_id`, `name`, `image`, `description`, `price`, `status`, `dt`)
                                             VALUES ('$category','$name','$photo','$description','$price','$status','$dt')");
            $productId = mysqli_insert_id($con);
            $msg = 'Product added.';
        }

        if ($ok)
        {
            // Extra gallery photos go to THIS product (it used to pick the newest product instead)
            $failed = 0;
            if (isset($_FILES['photo2']['name']) && is_array($_FILES['photo2']['name'])) {
                foreach ($_FILES['photo2']['name'] as $index => $fileName) {
                    if ($_FILES['photo2']['error'][$index] == UPLOAD_ERR_NO_FILE) continue;
                    $up = uploadFile2($_FILES, $index, "photo2", "upload_images/", array("png", "jpg", "jpeg"));
                    if ($up['status'] == 'success') {
                        $img = db_escape($up['image']);
                        insert_update_delete_data("INSERT INTO `product_images`(`product_id`, `image`) VALUES ('$productId','$img')");
                    } else $failed++;
                }
            }
            if ($failed) redirect_to('products.php', "$msg $failed extra photo(s) could not be uploaded (PNG/JPG, max 5 MB).", 'error');
            redirect_to('products.php', $msg);
        }
        $error = 'Could not save the product. Please try again.';
    }
}

function val($key, $data) {
    if (isset($_POST[$key])) return $_POST[$key];
    return $data ? $data[$key] : '';
}
$checked = isset($_POST['name']) ? isset($_POST['status']) : ($data ? $data['status'] == '1' : true);
$selCat = isset($_POST['category']) ? $_POST['category'] : ($data ? $data['category_id'] : '');

admin_start('products', $data ? 'Edit product' : 'Add product', $data ? e($data['name']) : 'Add a new scrap product to the website', '', 'products.php');
?>

<form class="two-col" method="POST" enctype="multipart/form-data" data-validate novalidate>
  <div class="box">
    <div class="box-head"><h2>Details</h2></div>
    <div class="box-body form-grid">
      <div class="field span-2">
        <label for="name">Product name <span class="req">*</span></label>
        <div class="control"><i class="fa-solid fa-box"></i>
          <input class="input" type="text" id="name" name="name" maxlength="255" placeholder="e.g. Plastic bottles" value="<?php echo e(val('name', $data)); ?>" required>
        </div>
      </div>
      <div class="field">
        <label for="category">Category <span class="req">*</span></label>
        <div class="control"><i class="fa-solid fa-layer-group"></i>
          <select class="input" name="category" id="category" required>
            <option value="">Select category</option>
            <?php foreach ($categories as $c) { ?>
              <option value="<?php echo (int)$c['id']; ?>" <?php echo $selCat == $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
            <?php } ?>
          </select>
        </div>
        <?php if (!count($categories)) { ?><span class="hint">No categories yet. <a href="addCategory.php" class="link-more">Add one first</a></span><?php } ?>
      </div>
      <div class="field">
        <label for="price">Price (&#8377;) <span class="req">*</span></label>
        <div class="control"><i class="fa-solid fa-indian-rupee-sign"></i>
          <input class="input" type="text" id="price" name="price" inputmode="decimal" pattern="\d+(\.\d{1,2})?" placeholder="20.00" value="<?php echo e(val('price', $data)); ?>" required>
        </div>
        <span class="hint">Numbers only, e.g. 20 or 20.50</span>
      </div>
      <div class="field span-2">
        <label for="description">Description <span class="req">*</span></label>
        <div class="control top"><i class="fa-regular fa-file-lines"></i>
          <textarea class="input" id="description" name="description" rows="4" placeholder="What is it, condition, use…" required><?php echo e(val('description', $data)); ?></textarea>
        </div>
      </div>
      <label class="switch span-2">
        <span><strong>Show on website</strong><small>Hidden products are not listed for customers</small></span>
        <input type="checkbox" id="status" name="status" <?php echo $checked ? 'checked' : ''; ?>><span class="track"></span>
      </label>
    </div>
  </div>

  <div class="stack">
    <div class="box">
      <div class="box-head"><h2>Photos</h2></div>
      <div class="box-body stack">
        <div class="field">
          <label for="photo">Main photo <?php if (!$data) { ?><span class="req">*</span><?php } ?></label>
          <?php if ($data && $data['image']) { ?>
            <div class="current-img"><img src="upload_images/<?php echo e($data['image']); ?>" alt="">Current photo. Choose a new one to replace it.</div>
          <?php } ?>
          <label class="dropzone" for="photo">
            <span class="dz-thumb"><i class="fa-regular fa-image"></i></span>
            <span class="dz-text"><strong>Tap to choose</strong><span>PNG or JPG, max 5 MB</span></span>
            <input type="file" id="photo" name="photo" accept="image/png,image/jpeg" <?php echo $data ? '' : 'required'; ?>>
          </label>
        </div>
        <div class="field">
          <label for="photo2">Extra gallery photos</label>
          <input class="input" style="padding:12px;height:auto" type="file" id="photo2" name="photo2[]" accept="image/png,image/jpeg" multiple>
          <span class="hint">Optional. You can pick several at once.<?php if ($data) { ?> <a class="link-more" href="productImages.php?getData=<?php echo $id; ?>">Manage existing photos</a><?php } ?></span>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> <?php echo $data ? 'Save changes' : 'Add product'; ?></button>
      <a href="products.php" class="btn btn-ghost">Cancel</a>
    </div>
  </div>
</form>

<?php admin_end($error ? '<script>adminToast(' . json_encode($error) . ', "error");</script>' : ''); ?>
