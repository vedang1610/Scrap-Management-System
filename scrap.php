<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

function e($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;
$searchId = isset($_GET['searchId']) ? trim($_GET['searchId']) : '';

$sql = "SELECT * FROM `products` where status='1' ";
if ($categoryId) {
    $sql .= " and category_id='$categoryId' ";
}
if ($searchId != '') {
    $sql .= " and name like '%" . mysqli_real_escape_string($con, $searchId) . "%' ";
}
$sql .= " order by id desc";
$products = selectData($sql);
if (!is_array($products)) $products = array();

$categories = selectData("SELECT * FROM `category` order by id desc");
if (!is_array($categories)) $categories = array();
$categoryNames = array();
foreach ($categories as $row) $categoryNames[$row['id']] = $row['name'];

$heading = 'All scrap';
if ($categoryId && isset($categoryNames[$categoryId])) $heading = $categoryNames[$categoryId];
if ($searchId != '') $heading = 'Results for "' . $searchId . '"';

$pageTitle = 'Scrap Products';
$activePage = 'scrap';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include('ui/head.php'); ?>
</head>
<body class="sms has-tabbar">

  <?php include('ui/header.php'); ?>

  <section class="hero">
    <div class="wrap">
      <div class="crumbs"><a href="index.php">Home</a><i class="fa-solid fa-chevron-right"></i><span>Scrap</span></div>
      <h1>Scrap products</h1>
      <p>Browse scrap by category, pick what you need and add it to your cart.</p>
      <form class="search" action="scrap.php" method="get" role="search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <?php if ($categoryId) { ?><input type="hidden" name="categoryId" value="<?php echo $categoryId; ?>"><?php } ?>
        <input type="search" name="searchId" value="<?php echo e($searchId); ?>" placeholder="Search scrap, e.g. bottle" aria-label="Search scrap" enterkeyhint="search">
        <button class="btn btn-primary btn-sm" type="submit">Search</button>
      </form>
    </div>
  </section>

  <main class="wrap">
    <div class="chips-bar">
      <div class="chips">
        <a href="scrap.php" class="chip no-img <?php echo !$categoryId ? 'is-active' : ''; ?>">All</a>
        <?php foreach ($categories as $row) { ?>
          <a href="scrap.php?categoryId=<?php echo (int)$row['id']; ?>" class="chip <?php echo $categoryId == $row['id'] ? 'is-active' : ''; ?>">
            <img src="admin/upload_images/<?php echo e($row['image']); ?>" alt="" loading="lazy">
            <?php echo e($row['name']); ?>
          </a>
        <?php } ?>
      </div>
    </div>

    <div class="section-head">
      <h2><?php echo e($heading); ?></h2>
      <span><?php echo count($products); ?> item<?php echo count($products) == 1 ? '' : 's'; ?></span>
    </div>

    <div class="grid">
      <?php if (count($products)) { ?>
        <?php foreach ($products as $row) { ?>
          <article class="card">
            <a class="card-media" href="productDetails.php?getData=<?php echo (int)$row['id']; ?>">
              <img src="admin/upload_images/<?php echo e($row['image']); ?>" alt="<?php echo e($row['name']); ?>" loading="lazy">
              <?php if (isset($categoryNames[$row['category_id']])) { ?>
                <span class="badge"><?php echo e($categoryNames[$row['category_id']]); ?></span>
              <?php } ?>
            </a>
            <div class="card-body">
              <a href="productDetails.php?getData=<?php echo (int)$row['id']; ?>" class="card-title"><?php echo e($row['name']); ?></a>
              <div class="price">&#8377;<?php echo e($row['price']); ?> <small>/ item</small></div>
              <div class="card-actions">
                <a href="cart.php?addProduct=<?php echo (int)$row['id']; ?>&amp;qty=1" class="btn btn-primary"><i class="fa-solid fa-cart-plus"></i> Add to cart</a>
                <a href="productDetails.php?getData=<?php echo (int)$row['id']; ?>" class="btn btn-ghost" aria-label="View details"><i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </article>
        <?php } ?>
      <?php } else { ?>
        <div class="empty">
          <i class="fa-solid fa-box-open"></i>
          <h3>No scrap found</h3>
          <p>Try another category or search word.</p>
          <a href="scrap.php" class="btn btn-primary">Show all scrap</a>
        </div>
      <?php } ?>
    </div>
  </main>

  <?php include('ui/footer.php'); ?>

  <script src="assets/sms.js?v=<?php echo @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/sms.js'); ?>"></script>
</body>
</html>
