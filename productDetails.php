<?php
session_start();
include('admin/db.php');
include('admin/functions.php');

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$id = isset($_GET['getData']) ? (int)$_GET['getData'] : 0;
$productsData = $id ? selectData("SELECT * FROM `products` where id='$id'") : array();
if (is_array($productsData) && count($productsData) >= 1) {
  $productsData = $productsData[0];
} else {
  header('location:scrap.php');
  exit;
}

$categoryName = '';
$cat = selectData("SELECT name FROM `category` where id='" . (int)$productsData['category_id'] . "'");
if (is_array($cat) && count($cat)) $categoryName = $cat[0]['name'];

// Main image first, then the extra gallery images
$images = array($productsData['image']);
$productImages = selectData("SELECT * FROM `product_images` where product_id='$id' order by id desc");
if (is_array($productImages)) {
  foreach ($productImages as $productImage) $images[] = $productImage['image'];
}

$related = selectData("SELECT * FROM `products` where status='1' and id != '$id' order by (category_id = '" . (int)$productsData['category_id'] . "') desc, id desc limit 4");
if (!is_array($related)) $related = array();

$pageTitle = $productsData['name'];
$activePage = 'scrap';
$showTabbar = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('ui/head.php'); ?>
</head>
<body class="sms has-actionbar">

  <?php include('ui/header.php'); ?>

  <main class="wrap pd">
    <div class="crumbs" style="color:var(--muted);margin-bottom:14px">
      <a href="index.php">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="scrap.php">Scrap</a><i class="fa-solid fa-chevron-right"></i>
      <span><?php echo e($productsData['name']); ?></span>
    </div>

    <div class="pd-grid">
      <div class="pd-gallery">
        <a class="pd-main" href="admin/upload_images/<?php echo e($images[0]); ?>" data-lightbox>
          <img src="admin/upload_images/<?php echo e($images[0]); ?>" alt="<?php echo e($productsData['name']); ?>">
        </a>
        <?php if (count($images) > 1) { ?>
          <div class="pd-thumbs">
            <?php foreach ($images as $i => $img) { ?>
              <button type="button" class="<?php echo $i == 0 ? 'is-active' : ''; ?>" data-src="admin/upload_images/<?php echo e($img); ?>" aria-label="Show photo <?php echo $i + 1; ?>">
                <img src="admin/upload_images/<?php echo e($img); ?>" alt="" loading="lazy">
              </button>
            <?php } ?>
          </div>
        <?php } ?>
      </div>

      <div class="pd-info">
        <?php if ($categoryName) { ?>
          <a href="scrap.php?categoryId=<?php echo (int)$productsData['category_id']; ?>" class="badge-inline"><i class="fa-solid fa-tag"></i><?php echo e($categoryName); ?></a>
        <?php } ?>
        <h1><?php echo e($productsData['name']); ?></h1>
        <div class="pd-price">&#8377;<?php echo e($productsData['price']); ?> <small>/ item</small></div>

        <div class="pd-desc">
          <h2>Description</h2>
          <p><?php echo nl2br(e(trim($productsData['description']) ?: 'No description yet.')); ?></p>
        </div>

        <div class="pd-buy">
          <div class="stepper">
            <button type="button" data-step="-1" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
            <input type="number" id="qty" name="qty" min="1" max="50" value="1" inputmode="numeric" aria-label="Quantity">
            <button type="button" data-step="1" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
          </div>
          <button type="button" class="btn btn-primary" onclick="addCard(<?php echo (int)$productsData['id']; ?>)"><i class="fa-solid fa-cart-plus"></i> Add to cart</button>
        </div>

        <div class="pd-perks">
          <div><i class="fa-solid fa-truck"></i>Doorstep delivery</div>
          <div><i class="fa-solid fa-recycle"></i>Recycled</div>
          <div><i class="fa-solid fa-shield-halved"></i>Safe payment</div>
        </div>
      </div>
    </div>

    <?php if (count($related)) { ?>
      <div class="section-head" style="margin-top:36px">
        <h2>You may also like</h2>
        <a href="scrap.php" class="link-more">See all<i class="fa-solid fa-arrow-right"></i></a>
      </div>
      <div class="grid" style="padding-bottom:0">
        <?php foreach ($related as $row) { ?>
          <article class="card">
            <a class="card-media" href="productDetails.php?getData=<?php echo (int)$row['id']; ?>">
              <img src="admin/upload_images/<?php echo e($row['image']); ?>" alt="<?php echo e($row['name']); ?>" loading="lazy">
            </a>
            <div class="card-body">
              <a href="productDetails.php?getData=<?php echo (int)$row['id']; ?>" class="card-title"><?php echo e($row['name']); ?></a>
              <div class="price">&#8377;<?php echo e($row['price']); ?> <small>/ item</small></div>
            </div>
          </article>
        <?php } ?>
      </div>
    <?php } ?>
  </main>

  <div class="actionbar">
    <div class="ab-price"><span>Price</span><strong>&#8377;<?php echo e($productsData['price']); ?></strong></div>
    <button type="button" class="btn btn-primary" onclick="addCard(<?php echo (int)$productsData['id']; ?>)"><i class="fa-solid fa-cart-plus"></i> Add to cart</button>
  </div>

  <?php include('ui/footer.php'); ?>

  <script src="assets/sms.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script>
    function addCard(id)
    {
        var qty = Number(document.getElementById("qty").value);
        if (!qty || qty < 1 || qty > 50 || Math.floor(qty) !== qty)
        {
            swal("Quantity should be a whole number from 1 to 50.", "", "error");
        }
        else
        {
            window.location = "cart.php?addProduct=" + id + "&qty=" + qty;
        }
    }
  </script>
</body>

</html>

<?php

if(isset($_POST['btn_book']))
{
    if(isset($_POST['product_id']) && isset($_POST['product_price']))
    {
        $userId = isset($_SESSION['userId']);
        $product_price = $_POST['product_price'];
        $product_id = $_POST['product_id'];
        $from_date = " ";
        $to_date = "-";
        $dt = date("Y-m-d");


        $salesSql = "INSERT INTO `sales`(
                     `user_id`,
                     `product_id`,
                     `total`,
                     `from_date`,
                     `to_date`,
                     `dt`,
                     `payment_mode`,
                     `payment_status`,
                     `status`
               )
               VALUES(
                  '$userId',
                  '$product_id',
                  '$product_price',
                  '$from_date',
                  '$to_date',
                  '$dt',
                  '1',
                  '0',
                  '0'

               )";
            insert_update_delete_data($salesSql);

            $lastData = selectData("SELECT * FROM sales where user_id='$userId' order by id desc limit 1");
            if(is_array($lastData) && count($lastData) >= 1)
            {
               $lastId = $lastData[0]['id'];
               $link = "window.location = 'cart.php?txtId=$lastId&txtAmount=$product_price'";
               runJavascript($link);
            }
    }
}
?>
