<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

if (isset($_POST['deleteId']))
{
    $id = (int)$_POST['deleteId'];
    $ok = insert_update_delete_data("DELETE FROM `products` WHERE id='$id'");
    if ($ok) {
        // Clean up rows that point at the deleted product (past orders keep their own copy)
        insert_update_delete_data("DELETE FROM `product_images` WHERE product_id='$id'");
        insert_update_delete_data("DELETE FROM `cart` WHERE product_id='$id'");
    }
    redirect_to('products.php', $ok ? 'Product deleted.' : 'Could not delete the product.', $ok ? 'success' : 'error');
}

if (isset($_POST['toggleId']))
{
    $id = (int)$_POST['toggleId'];
    $ok = insert_update_delete_data("UPDATE `products` SET status = IF(status='1','0','1') WHERE id='$id'");
    redirect_to('products.php', $ok ? 'Product status changed.' : 'Could not change the status.', $ok ? 'success' : 'error');
}

$data = selectData("SELECT p.*, c.name AS category_name,
                      (SELECT COUNT(*) FROM product_images pi WHERE pi.product_id = p.id) AS images
                    FROM `products` p LEFT JOIN `category` c ON c.id = p.category_id
                    ORDER BY p.id DESC");
$active = 0;
foreach ($data as $p) if ($p['status'] == '1') $active++;

admin_start('products', 'Products', count($data) . ' product' . (count($data) == 1 ? '' : 's') . " &middot; $active active",
    '<a href="addProducts.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i><span>Add product</span></a>');
?>

<div class="toolbar">
  <label class="searchbox"><i class="fa-solid fa-magnifying-glass"></i>
    <input type="search" placeholder="Search products or categories" data-list-search="products" aria-label="Search products"></label>
  <div class="chips" data-list-filter="products">
    <a href="#" class="chip no-img is-active" data-f="all">All <em><?php echo count($data); ?></em></a>
    <a href="#" class="chip no-img" data-f="active">Active <em><?php echo $active; ?></em></a>
    <a href="#" class="chip no-img" data-f="inactive">Hidden <em><?php echo count($data) - $active; ?></em></a>
  </div>
</div>

<div class="box table-box">
  <table class="dtable" data-list="products">
    <thead><tr><th>Product</th><th>Price</th><th>Photos</th><th>Status</th><th>Added</th><th></th></tr></thead>
    <tbody>
    <?php if (count($data)) { foreach ($data as $p) { $isActive = $p['status'] == '1'; ?>
      <tr data-search="<?php echo e($p['name'] . ' ' . $p['category_name'] . ' ' . $p['description']); ?>" data-filter="<?php echo $isActive ? 'active' : 'inactive'; ?>">
        <td class="td-main" data-label="Product">
          <div class="cell-main">
            <a href="upload_images/<?php echo e($p['image']); ?>" data-lightbox><img class="thumb thumb-lg" src="upload_images/<?php echo e($p['image']); ?>" alt=""></a>
            <div style="min-width:0"><strong><?php echo e($p['name']); ?></strong>
              <small><?php echo e($p['category_name'] ?: 'No category'); ?></small>
              <small class="clamp"><?php echo e($p['description']); ?></small></div>
          </div>
        </td>
        <td data-label="Price"><b><?php echo money($p['price']); ?></b></td>
        <td data-label="Photos"><a class="link-more" href="productImages.php?getData=<?php echo (int)$p['id']; ?>"><?php echo 1 + (int)$p['images']; ?> photo<?php echo $p['images'] ? 's' : ''; ?> <i class="fa-solid fa-images"></i></a></td>
        <td data-label="Status">
          <form method="POST" style="margin:0">
            <input type="hidden" name="toggleId" value="<?php echo (int)$p['id']; ?>">
            <button type="submit" class="btn btn-xs <?php echo $isActive ? 'btn-success-soft' : 'btn-soft'; ?>" title="Tap to <?php echo $isActive ? 'hide from' : 'show on'; ?> the website">
              <i class="fa-solid <?php echo $isActive ? 'fa-eye' : 'fa-eye-slash'; ?>"></i> <?php echo $isActive ? 'Active' : 'Hidden'; ?></button>
          </form>
        </td>
        <td data-label="Added"><?php echo $p['dt'] ? date('d M Y', strtotime($p['dt'])) : '-'; ?></td>
        <td class="td-actions">
          <div class="actions">
            <a class="btn btn-soft btn-xs" href="addProducts.php?getData=<?php echo (int)$p['id']; ?>"><i class="fa-solid fa-pen"></i> Edit</a>
            <form method="POST" data-confirm="Delete “<?php echo e($p['name']); ?>”?" data-confirm-text="Its photos are removed too. Past orders are kept." data-confirm-btn="Delete">
              <input type="hidden" name="deleteId" value="<?php echo (int)$p['id']; ?>">
              <button class="btn btn-danger btn-xs" type="submit"><i class="fa-regular fa-trash-can"></i> Delete</button>
            </form>
          </div>
        </td>
      </tr>
    <?php } } else { ?>
      <tr><td colspan="6" class="empty-row"><i class="fa-solid fa-box"></i>No products yet. <a href="addProducts.php" class="link-more">Add the first one</a></td></tr>
    <?php } ?>
    </tbody>
  </table>
  <div class="no-match" data-list-empty="products">No products match.</div>
</div>

<?php admin_end(); ?>
