<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

if (isset($_POST['deleteId']))
{
    $id = (int)$_POST['deleteId'];
    $inUse = count_of("SELECT COUNT(*) FROM products WHERE category_id='$id'");
    if ($inUse) {
        redirect_to('category.php', "This category still has $inUse product" . ($inUse == 1 ? '' : 's') . '. Move or delete them first.', 'error');
    }
    $ok = insert_update_delete_data("DELETE FROM `category` WHERE id='$id'");
    redirect_to('category.php', $ok ? 'Category deleted.' : 'Could not delete the category.', $ok ? 'success' : 'error');
}

$data = selectData("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS total
                    FROM `category` c ORDER BY c.id DESC");

admin_start('category', 'Categories', count($data) . ' categor' . (count($data) == 1 ? 'y' : 'ies'),
    '<a href="addCategory.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i><span>Add category</span></a>');
?>

<div class="toolbar">
  <label class="searchbox"><i class="fa-solid fa-magnifying-glass"></i>
    <input type="search" placeholder="Search categories" data-list-search="cats" aria-label="Search categories"></label>
</div>

<div class="box table-box">
  <table class="dtable" data-list="cats">
    <thead><tr><th>Category</th><th>Products</th><th>Added</th><th></th></tr></thead>
    <tbody>
    <?php if (count($data)) { foreach ($data as $c) { ?>
      <tr data-search="<?php echo e($c['name']); ?>">
        <td class="td-main" data-label="Category">
          <div class="cell-main">
            <a href="upload_images/<?php echo e($c['image']); ?>" data-lightbox><img class="thumb" src="upload_images/<?php echo e($c['image']); ?>" alt=""></a>
            <div><strong><?php echo e($c['name']); ?></strong><small>ID <?php echo (int)$c['id']; ?></small></div>
          </div>
        </td>
        <td data-label="Products"><a href="../scrap.php?categoryId=<?php echo (int)$c['id']; ?>" target="_blank" class="link-more"><?php echo (int)$c['total']; ?> product<?php echo $c['total'] == 1 ? '' : 's'; ?></a></td>
        <td data-label="Added"><?php echo $c['dt'] ? date('d M Y', strtotime($c['dt'])) : '-'; ?></td>
        <td class="td-actions">
          <div class="actions">
            <a class="btn btn-soft btn-xs" href="addCategory.php?getData=<?php echo (int)$c['id']; ?>"><i class="fa-solid fa-pen"></i> Edit</a>
            <form method="POST" data-confirm="Delete “<?php echo e($c['name']); ?>”?" data-confirm-text="This can't be undone." data-confirm-btn="Delete">
              <input type="hidden" name="deleteId" value="<?php echo (int)$c['id']; ?>">
              <button class="btn btn-danger btn-xs" type="submit"><i class="fa-regular fa-trash-can"></i> Delete</button>
            </form>
          </div>
        </td>
      </tr>
    <?php } } else { ?>
      <tr><td colspan="4" class="empty-row"><i class="fa-solid fa-layer-group"></i>No categories yet. <a href="addCategory.php" class="link-more">Add the first one</a></td></tr>
    <?php } ?>
    </tbody>
  </table>
  <div class="no-match" data-list-empty="cats">No categories match your search.</div>
</div>

<?php admin_end(); ?>
