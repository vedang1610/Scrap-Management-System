<?php include_once("checkLogin.php");
include_once("functions.php");
include_once("ui/layout.php");

if (isset($_POST['deleteId']))
{
    $id = (int)$_POST['deleteId'];
    $ok = insert_update_delete_data("DELETE FROM `users` WHERE id='$id'");
    if ($ok) insert_update_delete_data("DELETE FROM `cart` WHERE user_id='$id'");
    redirect_to('viewusers.php', $ok ? 'User deleted. Their past orders are kept.' : 'Could not delete the user.', $ok ? 'success' : 'error');
}

if (isset($_POST['toggleId']))
{
    $id = (int)$_POST['toggleId'];
    $ok = insert_update_delete_data("UPDATE `users` SET status = IF(status='1','0','1') WHERE id='$id'");
    redirect_to('viewusers.php', $ok ? 'User status changed.' : 'Could not change the status.', $ok ? 'success' : 'error');
}

$data = selectData("SELECT u.*, (SELECT COUNT(*) FROM sales s WHERE s.user_id = u.id) AS orders
                    FROM `users` u ORDER BY u.id DESC");
$active = 0;
foreach ($data as $u) if ($u['status'] == '1') $active++;

admin_start('users', 'Users', count($data) . ' registered &middot; ' . $active . ' active');
?>

<div class="toolbar">
  <label class="searchbox"><i class="fa-solid fa-magnifying-glass"></i>
    <input type="search" placeholder="Search name, email, phone, address" data-list-search="users" aria-label="Search users"></label>
  <div class="chips" data-list-filter="users">
    <a href="#" class="chip no-img is-active" data-f="all">All <em><?php echo count($data); ?></em></a>
    <a href="#" class="chip no-img" data-f="active">Active <em><?php echo $active; ?></em></a>
    <a href="#" class="chip no-img" data-f="inactive">Blocked <em><?php echo count($data) - $active; ?></em></a>
  </div>
</div>

<div class="box table-box">
  <table class="dtable" data-list="users">
    <thead><tr><th>User</th><th>Phone</th><th>Address</th><th>ID proof</th><th>Orders</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php if (count($data)) { foreach ($data as $u) {
        $isActive = $u['status'] == '1';
        $proof = 'upload_images/' . $u['idproof'];
        $hasProof = $u['idproof'] && file_exists($proof);
    ?>
      <tr data-search="<?php echo e($u['name'] . ' ' . $u['email'] . ' ' . $u['phone'] . ' ' . $u['address']); ?>" data-filter="<?php echo $isActive ? 'active' : 'inactive'; ?>">
        <td class="td-main" data-label="User">
          <div class="cell-main"><span class="avatar av-sm"><?php echo e(initials($u['name'])); ?></span>
            <div style="min-width:0"><strong><?php echo e($u['name']); ?></strong><small><?php echo e($u['email']); ?></small>
            <small>Joined <?php echo date('d M Y', strtotime($u['dt'])); ?></small></div></div>
        </td>
        <td data-label="Phone"><a href="tel:<?php echo e($u['phone']); ?>" class="link-more"><?php echo e($u['phone']); ?></a></td>
        <td data-label="Address"><span class="clamp"><?php echo e($u['address']); ?></span></td>
        <td data-label="ID proof"><?php if ($hasProof) { ?><a href="<?php echo e($proof); ?>" data-lightbox><img class="thumb" src="<?php echo e($proof); ?>" alt="ID proof of <?php echo e($u['name']); ?>"></a><?php } else { ?><span class="muted small">Not uploaded</span><?php } ?></td>
        <td data-label="Orders"><?php echo (int)$u['orders']; ?></td>
        <td data-label="Status">
          <form method="POST" style="margin:0" <?php if ($isActive) { ?>data-confirm="Block <?php echo e($u['name']); ?>?" data-confirm-text="They won't be able to log in until you activate them again." data-confirm-btn="Block"<?php } ?>>
            <input type="hidden" name="toggleId" value="<?php echo (int)$u['id']; ?>">
            <button type="submit" class="btn btn-xs <?php echo $isActive ? 'btn-success-soft' : 'btn-danger'; ?>" title="Tap to <?php echo $isActive ? 'block' : 'activate'; ?>">
              <i class="fa-solid <?php echo $isActive ? 'fa-circle-check' : 'fa-ban'; ?>"></i> <?php echo $isActive ? 'Active' : 'Blocked'; ?></button>
          </form>
        </td>
        <td class="td-actions">
          <div class="actions">
            <a class="btn btn-soft btn-xs" href="mailto:<?php echo e($u['email']); ?>"><i class="fa-regular fa-envelope"></i> Email</a>
            <form method="POST" data-confirm="Delete <?php echo e($u['name']); ?>?" data-confirm-text="This removes the account. Their past orders are kept." data-confirm-btn="Delete">
              <input type="hidden" name="deleteId" value="<?php echo (int)$u['id']; ?>">
              <button class="btn btn-danger btn-xs" type="submit"><i class="fa-regular fa-trash-can"></i> Delete</button>
            </form>
          </div>
        </td>
      </tr>
    <?php } } else { ?>
      <tr><td colspan="7" class="empty-row"><i class="fa-solid fa-users"></i>No users have registered yet</td></tr>
    <?php } ?>
    </tbody>
  </table>
  <div class="no-match" data-list-empty="users">No users match.</div>
</div>

<?php admin_end(); ?>
