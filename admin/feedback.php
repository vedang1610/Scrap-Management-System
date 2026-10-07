<?php include_once("checkLogin.php"); // this page had no login check before
include_once("functions.php");
include_once("ui/layout.php");

if (isset($_POST['deleteId']))
{
    $id = (int)$_POST['deleteId'];
    $ok = insert_update_delete_data("DELETE FROM `feedback` WHERE id='$id'");
    redirect_to('feedback.php', $ok ? 'Feedback deleted.' : 'Could not delete the feedback.', $ok ? 'success' : 'error');
}

$data = selectData("SELECT * FROM `feedback` ORDER BY id DESC");

admin_start('feedback', 'Feedback', count($data) . ' message' . (count($data) == 1 ? '' : 's') . ' from customers');
?>

<div class="toolbar">
  <label class="searchbox"><i class="fa-solid fa-magnifying-glass"></i>
    <input type="search" placeholder="Search name, email, message" data-list-search="fb" aria-label="Search feedback"></label>
</div>

<?php if (count($data)) { ?>
  <div class="fb-list" data-list="fb">
    <?php foreach ($data as $f) { ?>
      <article class="fb-card" data-search="<?php echo e($f['name'] . ' ' . $f['email'] . ' ' . $f['phone'] . ' ' . $f['message']); ?>">
        <div class="fb-top">
          <span class="avatar av-sm"><?php echo e(initials($f['name'])); ?></span>
          <div style="min-width:0"><strong><?php echo e($f['name']); ?></strong><small>#<?php echo (int)$f['id']; ?></small></div>
          <form method="POST" data-confirm="Delete this feedback?" data-confirm-btn="Delete">
            <input type="hidden" name="deleteId" value="<?php echo (int)$f['id']; ?>">
            <button class="btn btn-danger btn-xs btn-icon" type="submit" aria-label="Delete feedback"><i class="fa-regular fa-trash-can"></i></button>
          </form>
        </div>
        <div class="fb-msg"><?php echo e($f['message']); ?></div>
        <div class="fb-links">
          <a class="btn btn-soft btn-xs" href="mailto:<?php echo e($f['email']); ?>?subject=<?php echo rawurlencode('Re: your feedback'); ?>"><i class="fa-regular fa-envelope"></i> <?php echo e($f['email']); ?></a>
          <?php if ($f['phone']) { ?><a class="btn btn-soft btn-xs" href="tel:<?php echo e($f['phone']); ?>"><i class="fa-solid fa-phone"></i> <?php echo e($f['phone']); ?></a><?php } ?>
        </div>
      </article>
    <?php } ?>
  </div>
  <div class="no-match" data-list-empty="fb">No feedback matches your search.</div>
<?php } else { ?>
  <div class="box"><div class="empty-row" style="display:block"><i class="fa-solid fa-comment-dots"></i>No feedback yet</div></div>
<?php } ?>

<?php admin_end(); ?>
