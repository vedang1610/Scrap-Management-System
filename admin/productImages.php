<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

$id = isset($_GET['getData']) ? (int)$_GET['getData'] : 0;
$product = selectData("SELECT * FROM `products` WHERE id='$id'");
if (!is_array($product) || !count($product)) redirect_to('products.php', 'Product not found.', 'error');
$product = $product[0];
$self = "productImages.php?getData=$id";

if (isset($_POST['deleteId']))
{
    $imgId = (int)$_POST['deleteId'];
    $ok = insert_update_delete_data("DELETE FROM `product_images` WHERE id='$imgId' AND product_id='$id'");
    redirect_to($self, $ok ? 'Photo deleted.' : 'Could not delete the photo.', $ok ? 'success' : 'error');
}

if (isset($_POST['upload']))
{
    $added = 0; $failed = 0;
    if (isset($_FILES['photo2']['name']) && is_array($_FILES['photo2']['name'])) {
        foreach ($_FILES['photo2']['name'] as $index => $fileName) {
            if ($_FILES['photo2']['error'][$index] == UPLOAD_ERR_NO_FILE) continue;
            $up = uploadFile2($_FILES, $index, "photo2", "upload_images/", array("png", "jpg", "jpeg"));
            if ($up['status'] == 'success') {
                $img = db_escape($up['image']);
                if (insert_update_delete_data("INSERT INTO `product_images`(`product_id`, `image`) VALUES ('$id','$img')")) $added++;
            } else $failed++;
        }
    }
    if (!$added && !$failed) redirect_to($self, 'Choose at least one photo.', 'error');
    redirect_to($self, "$added photo(s) added." . ($failed ? " $failed failed (PNG/JPG, max 5 MB)." : ''), $failed ? 'error' : 'success');
}

$images = selectData("SELECT * FROM `product_images` WHERE product_id='$id' ORDER BY id DESC");

admin_start('products', 'Photos', e($product['name']) . ' &middot; ' . (1 + count($images)) . ' photo' . (count($images) ? 's' : ''), '', 'products.php');
?>

<div class="stack">
  <div class="box">
    <form class="box-body" method="POST" enctype="multipart/form-data" style="display:flex;gap:12px;flex-wrap:wrap;align-items:center">
      <input type="hidden" name="upload" value="1">
      <input class="input" style="flex:1 1 240px;padding:12px;height:auto" type="file" name="photo2[]" accept="image/png,image/jpeg" multiple required aria-label="Choose photos">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload photos</button>
      <a href="addProducts.php?getData=<?php echo $id; ?>" class="btn btn-ghost"><i class="fa-solid fa-pen"></i> Edit product</a>
    </form>
  </div>

  <div class="img-grid">
    <div class="img-tile">
      <a href="upload_images/<?php echo e($product['image']); ?>" data-lightbox><img src="upload_images/<?php echo e($product['image']); ?>" alt="Main photo"></a>
      <span class="tag"><?php echo pill('Main photo', 'green'); ?></span>
    </div>
    <?php foreach ($images as $img) { ?>
      <div class="img-tile">
        <a href="upload_images/<?php echo e($img['image']); ?>" data-lightbox><img src="upload_images/<?php echo e($img['image']); ?>" alt=""></a>
        <form method="POST" data-confirm="Delete this photo?" data-confirm-btn="Delete">
          <input type="hidden" name="deleteId" value="<?php echo (int)$img['id']; ?>">
          <button type="submit" class="btn btn-danger btn-xs btn-icon" aria-label="Delete photo"><i class="fa-regular fa-trash-can"></i></button>
        </form>
      </div>
    <?php } ?>
  </div>
  <?php if (!count($images)) { ?><p class="muted small">No extra photos yet. Upload some above to show a gallery on the product page.</p><?php } ?>
</div>

<?php admin_end(); ?>
