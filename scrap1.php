<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

if (isset($_SESSION['userId'])) {
  // header('location:admin/userDashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Scrap Management System</title>

  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/font-awesome.css" rel="stylesheet">
  <link href="css/flaticon.css" rel="stylesheet">
  <link href="css/slick-slider.css" rel="stylesheet">
  <link href="css/fancybox.css" rel="stylesheet">
  <link href="build/mediaelementplayer.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <link href="css/color.css" rel="stylesheet">
  <link href="css/responsive.css" rel="stylesheet">

<link href="css/c/font-awesome.css" rel="stylesheet">
      <!-- Bootstrap -->
      <link href="css/c/bootstrap.css" rel="stylesheet">
      <!-- SmartMenus jQuery Bootstrap Addon CSS -->
      <link href="css/c/jquery.smartmenus.bootstrap.css" rel="stylesheet">
      <!-- Product view slider -->
      <link rel="stylesheet" type="text/css" href="css/c/jquery.simpleLens.css">
      <!-- slick slider -->
      <link rel="stylesheet" type="text/css" href="css/c/slick.css">
      <!-- price picker slider -->
      <link rel="stylesheet" type="text/css" href="css/c/nouislider.css">
      <!-- Theme color -->
      <link id="switcher" href="css/c/theme-color/default-theme.css" rel="stylesheet">
      <!-- <link id="switcher" href="css/theme-color/bridge-theme.css" rel="stylesheet"> -->
      <!-- Top Slider CSS -->
      <link href="css/c/sequence-theme.modern-slide-in.css" rel="stylesheet" media="all">
      <!-- Main style sheet -->
      <link href="css/c/style.css" rel="stylesheet">
      <!-- Google Font -->
      <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
      <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

  <!--[if lt IE 9]>
		 <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	 <![endif]-->
</head>

<body>

  <div class="scrapcar-main-wrapper">

    <?php include("header.php"); ?>

    <!-- Content -->
    <div class="scrapcar-subheader">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="scrapcar-subheader-wrap">
              <h1>Scrap</h1>
              <ul class="scrapcar-breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Scrap</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="scrapcar-main-content">

      <div class="scrapcar-main-section">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <!-- <div class="scrapcar-listing-filter">
                <span><strong>12</strong> Listings </span>

                <ul class="nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="automechanic-icon automechanic-squares2"></i></a></li>
                  <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="automechanic-icon  automechanic-signs22"></i></a></li>
                </ul>

                <div class="scrapcar-search-select">
                  <label>Sort by:</label>
                  <select>
                    <option value="Default Sorting">Default Sorting</option>
                    <option value="Default Sorting">Default Sorting</option>
                    <option value="Default Sorting">Default Sorting</option>
                    <option value="Default Sorting">Default Sorting</option>
                  </select>
                </div>
              </div> -->
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="profile">
                  <div class="scrapcar-listing scrapcar-listing-grid">
                    <ul class="row">

                    <?php
                           $sql = "SELECT * FROM `products` where status='1' ";
                           if(isset($_GET['categoryId']))
                           {
                                 $categoryId = $_GET['categoryId'];
                                 $sql .= " and category_id='$categoryId' ";
                           }
                           if(isset($_GET['searchId']))
                           {
                                 $searchId = $_GET['searchId'];
                                 $sql .= " and name like'%".$searchId."%' ";
                           }
                           $sql .= " order by id desc";
                           $products = selectData($sql);
                           if(is_array($products) && count($products) >= 1)
                           {
                              foreach ($products as $productsRow) 
                              {
                                 ?>
								 <li>
                                                    <figure>
                                                        <a class="aa-product-img" href="productDetails.php?getData=<?php echo $productsRow['id']; ?>">
                                                            <img src="admin/upload_images/<?php echo $productsRow['image'] ?>" alt="img">
                                                        </a>
                                                        <a class="aa-add-card-btn"href="cart.php?addProduct=<?php echo $productsRow['id']; ?>&qty=1">
                                                            <span class="fa fa-shopping-cart"></span>
                                                            Add To Cart
                                                        </a>
                                                        <figcaption>
                                                            <h4 class="aa-product-title"><?php echo $productsRow['name']; ?></h4>
                                                            <span class="aa-product-price"><?php echo $productsRow['price']; ?></span>
                                                        </figcaption>
                                                    </figure>
                                                        <!-- <div class="aa-product-hvr-content">
                                                            <a href="#" data-toggle="tooltip" data-placement="top" title="Add to Wishlist"><span class="fa fa-heart-o"></span></a>
                                                            <a href="#" data-toggle="tooltip" data-placement="top" title="Compare"><span class="fa fa-exchange"></span></a>
                                                            <a href="#" data-toggle2="tooltip" data-placement="top" title="Quick View" data-toggle="modal" data-target="#quick-view-modal"><span class="fa fa-search"></span></a>                            
                                                        </div> -->
                                                </li>
                                <!-- <li class="col-md-4">
                                  <!-- <figure><span>Featured</span> -->
                                   <!-- <a href="productDetails.php?getData=<?php echo $productsRow['id']; ?>"><img src="admin/upload_images/<?php echo $productsRow['image'] ?>" alt=""></a>
                                  </figure>
                                  <div class="scrapcar-listing-grid-text">
                                    <!-- <small>Vehicles Cars </small> -->
                                 <!--   <h2><a href="productDetails.php?getData=<?php echo $productsRow['id']; ?>"><?php echo $productsRow['name']; ?></a></h2>
                                    <p><?php echo $productsRow['description']; ?></p>
                                    <span>Rs. <?php echo $productsRow['price']; ?></span>
                                  </div>
                                </li>
                                
                                 <!-- <div class="col-lg-4 col-md-6 col-12 item-width mb-30">
                                    <div class="product-item box-shadow card packages-box overflow-hidden">
                                       <figure>
                                       <div class="package-img">
                                          <a href="productDetails.php?getData=<?php echo $productsRow['id']; ?>">
                                             <img class="grid-img" alt="TravelRide" src="admin/upload_images/<?php echo $productsRow['image'] ?>">
                                             <div class="effect"></div>
                                          </a>
                                       </div> 
                                       <figcaption>
                                          <div class="card-body p-25 p-xs-15">
                                             <div class="packages-details">
                                             <h4><a href="productDetails.php?getData=<?php echo $productsRow['id']; ?>" class="title"><?php echo $productsRow['name']; ?></a></h4>
                                             <div class="rating-summary-block">
                                                <div class="rating-result" title="70%"> <span style="width:66%"></span> </div>
                                                <span class="label-review">10 Reviews</span>
                                             </div>
                                             <div class="d-xl-flex align-items-center mt-3 mt-xl-4"> 
                                                <div class="tour-info"> 
                                                   <ul>
                                                   <li>
                                                      <div class="days">
                                                         <?php echo $productsRow['description']; ?>
                                                      </div>
                                                   </li>
                                                   </ul>
                                                </div> 
                                                <br>
                                                <div class="price-box ml-xl-auto text-xl-center mt-xl-0 mt-3"> 
                                                   <div class="price-text mb-1">Price</div> 
                                                   <div class="price mb-0">Rs. <?php echo $productsRow['price']; ?></div> 
                                                </div>
                                             </div>
                                             <p class="dec mb-0 mt-3"><?php echo $productsRow['description']; ?></p>
                                             <div class="packages-btn mt-30 mt-xs-20">
                                                <a class="btn btn-light" href="productDetails.php?getData=<?php echo $productsRow['id']; ?>">View Detail</a>
                                             </div>
                                             </div>
                                          </div>
                                       </figcaption>
                                       </figure>                                
                                    </div>
                                 </div> -->
                                 <?php
                              }
                           }
                           else
                           {
                              echo "<h1>No Found !!!</h1> <br><br><br><br><br>";
                           }
                     ?>
	<div class="col-lg-3 col-md-3 col-sm-4 col-md-pull-9">
                  <aside class="aa-sidebar">
                     <!-- single sidebar -->
                     <div class="aa-sidebar-widget">
                        <h3>Category</h3>
                        <ul class="aa-catg-nav">
                        <?php
                            $category = selectData("SELECT * FROM `category` order by id desc");
                            if(is_array($category) && count($category) >= 1)
                            {
                                foreach ($category as $row) 
                                {
                                    ?>
                                    <li class="menu-item">
                                        <a href="products.php?categoryId=<?php echo $row['id']; ?>">
                                            <i class="las la-paw"></i> <?php echo $row['name']; ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                           <!-- <li><a href="#">Men</a></li>
                           <li><a href="">Women</a></li>
                           <li><a href="">Kids</a></li>
                           <li><a href="">Electornics</a></li>
                           <li><a href="">Sports</a></li> -->
                        </ul>
                     </div>
                     <!-- single sidebar -->
                     <!-- <div class="aa-sidebar-widget">
                        <h3>Tags</h3>
                        <div class="tag-cloud">
                           <a href="#">Fashion</a>
                           <a href="#">Ecommerce</a>
                           <a href="#">Shop</a>
                           <a href="#">Hand Bag</a>
                           <a href="#">Laptop</a>
                           <a href="#">Head Phone</a>
                           <a href="#">Pen Drive</a>
                        </div>
                     </div> -->
                     <!-- single sidebar -->
                     <!-- <div class="aa-sidebar-widget">
                        <h3>Shop By Price</h3>
                        <div class="aa-sidebar-price-range">
                           <form action="">
                              <div id="skipstep" class="noUi-target noUi-ltr noUi-horizontal noUi-background">
                              </div>
                              <span id="skip-value-lower" class="example-val">30.00</span>
                              <span id="skip-value-upper" class="example-val">100.00</span>
                              <button class="aa-filter-btn" type="submit">Filter</button>
                           </form>
                        </div>
                     </div>
     
                     <div class="aa-sidebar-widget">
                        <h3>Shop By Color</h3>
                        <div class="aa-color-tag">
                           <a class="aa-color-green" href="#"></a>
                           <a class="aa-color-yellow" href="#"></a>
                           <a class="aa-color-pink" href="#"></a>
                           <a class="aa-color-purple" href="#"></a>
                           <a class="aa-color-blue" href="#"></a>
                           <a class="aa-color-orange" href="#"></a>
                           <a class="aa-color-gray" href="#"></a>
                           <a class="aa-color-black" href="#"></a>
                           <a class="aa-color-white" href="#"></a>
                           <a class="aa-color-cyan" href="#"></a>
                           <a class="aa-color-olive" href="#"></a>
                           <a class="aa-color-orchid" href="#"></a>
                        </div>
                     </div> -->
                     <!-- single sidebar -->
                     <!-- <div class="aa-sidebar-widget">
                        <h3>Recently Views</h3>
                        <div class="aa-recently-views">
                           <ul>
                              <li>
                                 <a href="#" class="aa-cartbox-img"><img alt="img" src="img/woman-small-2.jpg"></a>
                                 <div class="aa-cartbox-info">
                                    <h4><a href="#">Product Name</a></h4>
                                    <p>1 x $250</p>
                                 </div>
                              </li>
                              <li>
                                 <a href="#" class="aa-cartbox-img"><img alt="img" src="img/woman-small-1.jpg"></a>
                                 <div class="aa-cartbox-info">
                                    <h4><a href="#">Product Name</a></h4>
                                    <p>1 x $250</p>
                                 </div>
                              </li>
                              <li>
                                 <a href="#" class="aa-cartbox-img"><img alt="img" src="img/woman-small-2.jpg"></a>
                                 <div class="aa-cartbox-info">
                                    <h4><a href="#">Product Name</a></h4>
                                    <p>1 x $250</p>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                   
                     <div class="aa-sidebar-widget">
                        <h3>Top Rated Products</h3>
                        <div class="aa-recently-views">
                           <ul>
                              <li>
                                 <a href="#" class="aa-cartbox-img"><img alt="img" src="img/woman-small-2.jpg"></a>
                                 <div class="aa-cartbox-info">
                                    <h4><a href="#">Product Name</a></h4>
                                    <p>1 x $250</p>
                                 </div>
                              </li>
                              <li>
                                 <a href="#" class="aa-cartbox-img"><img alt="img" src="img/woman-small-1.jpg"></a>
                                 <div class="aa-cartbox-info">
                                    <h4><a href="#">Product Name</a></h4>
                                    <p>1 x $250</p>
                                 </div>
                              </li>
                              <li>
                                 <a href="#" class="aa-cartbox-img"><img alt="img" src="img/woman-small-2.jpg"></a>
                                 <div class="aa-cartbox-info">
                                    <h4><a href="#">Product Name</a></h4>
                                    <p>1 x $250</p>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div> -->
                  </aside>
               </div>
                      <!-- <li class="col-md-4">
                        <figure><span>Featured</span>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img1.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li> -->
                      <!-- <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img2.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img3.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img4.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img5.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img6.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img7.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img8.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img9.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img10.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img11.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img12.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img13.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img14.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img15.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img1.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img2.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img3.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img2.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img3.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li>
                      <li class="col-md-4">
                        <figure>
                          <a href="listing-detail.html"><img src="extra-images/listing-grid-img4.jpg" alt=""></a>
                        </figure>
                        <div class="scrapcar-listing-grid-text">
                          <small>Vehicles Cars </small>
                          <h2><a href="listing-detail.html">Home For Rent In Low Price New</a></h2>
                          <p><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</p>
                          <span>$ 1,000 <del>$ 1,500</del></span>
                        </div>
                      </li> -->
                    </ul>
                  </div>

                  <!-- <div class="scrapcar-pagination">
                    <ul class="page-numbers">
                      <li><a class="previous page-numbers" href="404.html"><span aria-label="Next"><i class="fa fa-angle-left"></i></span></a></li>
                      <li><span class="page-numbers current">01</span></li>
                      <li><a class="page-numbers" href="404.html">02</a></li>
                      <li><a class="page-numbers" href="404.html">03</a></li>
                      <li><a class="page-numbers" href="404.html">04</a></li>
                      <li><a class="next page-numbers" href="404.html"><span aria-label="Next"><i class="fa fa-angle-right"></i></span></a></li>
                    </ul>
                  </div> -->

                </div>
                <!-- <div role="tabpanel" class="tab-pane" id="home">
                  <div class="scrapcar-listing scrapcar-listing-medium">
                    <ul class="row">
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure><span>Featured</span>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img1.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img2.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img3.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img4.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img5.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img6.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img7.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img8.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                      <li class="col-md-12">
                        <div class="scrapcar-listing-medium-wrap">
                          <figure>
                            <a href="listing-detail.html"><img src="extra-images/listing-medium-img9.jpg" alt=""></a>
                          </figure>
                          <div class="scrapcar-listing-medium-text">
                            <small>Vehicles Cars </small>
                            <h2><a href="listing-detail.html">Mercedes benz 300 SEL 1993 Sale as scrap/No....</a></h2>
                            <span><i class="automechanic-icon automechanic-placeholder"></i>Roswell, New Mexico, United States</span>
                            <p>Saab 93 2006 plate diesel, manual, 4 door 130,000 miles Can be sold for parts for a lot more Engine siezed Quick sale </p>
                            <ul class="scrapcar-listing-medium-option">
                              <li>2006 </li>
                              <li>130,000 miles</li>
                              <li>Diesel</li>
                              <li>1,910 cc</li>
                            </ul>
                            <strong>$ 1,000<del>$ 1,500</del></strong>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>

                  <div class="scrapcar-pagination">
                    <ul class="page-numbers">
                      <li><a class="previous page-numbers" href="404.html"><span aria-label="Next"><i class="fa fa-angle-left"></i></span></a></li>
                      <li><span class="page-numbers current">01</span></li>
                      <li><a class="page-numbers" href="404.html">02</a></li>
                      <li><a class="page-numbers" href="404.html">03</a></li>
                      <li><a class="page-numbers" href="404.html">04</a></li>
                      <li><a class="next page-numbers" href="404.html"><span aria-label="Next"><i class="fa fa-angle-right"></i></span></a></li>
                    </ul>
                  </div>

                </div> -->
              </div>
            </div>

            <!-- <aside class="col-md-3">
              <div class="scrapcar-sidebar-colr">

                <div class="widget widget_location">
                  <ul>
                    <li>
                      <label>Select Location:</label>
                      <input value="Roswell, New Mexico" onblur="if(this.value == '') { this.value ='Roswell, New Mexico'; }" onfocus="if(this.value =='Roswell, New Mexico') { this.value = ''; }" tabindex="0" type="text">
                      <i class="automechanic-icon automechanic-placeholder"></i>
                    </li>
                    <li>
                      <label>Price:</label>
                      <div class="widget-location-select">
                        <select>
                          <option value="Select Make">Select Make:</option>
                          <option value="Select Make">Select Make:</option>
                          <option value="Select Make">Select Make:</option>
                          <option value="Select Make">Select Make:</option>
                        </select>
                      </div>
                      <div class="widget-location-select">
                        <select>
                          <option value="Select Make">Select Make:</option>
                          <option value="Select Make">Select Make:</option>
                          <option value="Select Make">Select Make:</option>
                          <option value="Select Make">Select Make:</option>
                        </select>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_choose_type">
                  <h2 class="widget-heading">choose by Make:</h2>
                  <ul>
                    <li>
                      <div class="widget-check">
                        <input id="make1" type="checkbox">
                        <label for="make1">Suzuki <span>6</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="make2" type="checkbox">
                        <label for="make2">Toyota <span>5</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="make3" type="checkbox">
                        <label for="make3">Nissan <span>4</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="make4" type="checkbox">
                        <label for="make4">Daewoo <span>1</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="make5" type="checkbox">
                        <label for="make5">Daihatsu <span>2</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="make6" type="checkbox">
                        <label for="make6">Mercedes <span>7</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="make7" type="checkbox">
                        <label for="make7">Mitsubishi <span>15</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="make8" type="checkbox">
                        <label for="make8">Volkswagen <span>25</span></label>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_choose_type">
                  <h2 class="widget-heading">choose by Model:</h2>
                  <ul>
                    <li>
                      <div class="widget-check">
                        <input id="model1" type="checkbox">
                        <label for="model1">Corolla <span>6</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="model2" type="checkbox">
                        <label for="model2">Beetle <span>5</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="model3" type="checkbox">
                        <label for="model3">Khyber <span>4</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="model4" type="checkbox">
                        <label for="model4">Mehran <span>1</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="model5" type="checkbox">
                        <label for="model5">Racer <span>2</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="model6" type="checkbox">
                        <label for="model6">S-Class <span>7</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="model7" type="checkbox">
                        <label for="model7">Safari <span>15</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="model8" type="checkbox">
                        <label for="model8">Sunny <span>25</span></label>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_choose_type">
                  <h2 class="widget-heading">choose by Cities:</h2>
                  <ul>
                    <li>
                      <div class="widget-check">
                        <input id="city1" type="checkbox">
                        <label for="city1">Karachi <span>10</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="city2" type="checkbox">
                        <label for="city2">Islamabad <span>4</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="city3" type="checkbox">
                        <label for="city3">Bhakkar <span>2</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="city4" type="checkbox">
                        <label for="city4">Hafizabad <span>2</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="city5" type="checkbox">
                        <label for="city5">Lahore <span>2</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="city6" type="checkbox">
                        <label for="city6">Peshawar <span>1</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="city7" type="checkbox">
                        <label for="city7">Rawalpindi <span>1</span></label>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_choose_type">
                  <h2 class="widget-heading">choose by Regions:</h2>
                  <ul>
                    <li>
                      <div class="widget-check">
                        <input id="region1" type="checkbox">
                        <label for="region1">Sindh <span>10</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="region2" type="checkbox">
                        <label for="region2">Punjab <span>7</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="region3" type="checkbox">
                        <label for="region3">Islamabad <span>4</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="region4" type="checkbox">
                        <label for="region4">Khyber Pakhtunkhwa <span>1</span></label>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_location">
                  <ul>
                    <li>
                      <label>Year:</label>
                      <div class="widget-location-select">
                        <select>
                          <option value="No Min">No Min:</option>
                          <option value="No Min">No Min:</option>
                          <option value="No Min">No Min:</option>
                          <option value="No Min">No Min:</option>
                        </select>
                      </div>
                      <div class="widget-location-select">
                        <select>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                        </select>
                      </div>
                    </li>
                    <li>
                      <label>Mileage:</label>
                      <div class="widget-location-select">
                        <select>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                        </select>
                      </div>
                      <div class="widget-location-select">
                        <select>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                        </select>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_choose_type">
                  <h2 class="widget-heading">Kind of fuel:</h2>
                  <ul>
                    <li>
                      <div class="widget-check">
                        <input id="fuel1" type="checkbox">
                        <label for="fuel1">Diesel <span>5</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="fuel2" type="checkbox">
                        <label for="fuel2">Electric </label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="fuel3" type="checkbox">
                        <label for="fuel3">Hybrid <span>1</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="fuel4" type="checkbox">
                        <label for="fuel4">LPG </label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="fuel5" type="checkbox">
                        <label for="fuel5">Petrol <span>8</span></label>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_choose_type">
                  <h2 class="widget-heading">Transmission:</h2>
                  <ul>
                    <li>
                      <div class="widget-check">
                        <input id="transmission1" type="checkbox">
                        <label for="transmission1">Automatic <span>1</span></label>
                      </div>
                    </li>
                    <li>
                      <div class="widget-check">
                        <input id="transmission2" type="checkbox">
                        <label for="transmission2">Manual <span>5</span></label>
                      </div>
                    </li>
                  </ul>
                </div>


                <div class="widget widget_location">
                  <ul>
                    <li>
                      <label>Number of doors:</label>
                      <div class="widget-location-select">
                        <select>
                          <option value="No Min">No Min:</option>
                          <option value="No Min">No Min:</option>
                          <option value="No Min">No Min:</option>
                          <option value="No Min">No Min:</option>
                        </select>
                      </div>
                      <div class="widget-location-select">
                        <select>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                          <option value="No Max">No Max:</option>
                        </select>
                      </div>
                    </li>
                  </ul>
                </div>

              </div>
            </aside> -->

          </div>
        </div>
      </div>

    </div>

    <?php include("footer.php") ?>

    <div class="clearfix"></div>
  </div>


  <script type="text/javascript" src="script/jquery.js"></script>
  <script type="text/javascript" src="script/jquery-ui.js"></script>
  <script type="text/javascript" src="script/bootstrap.min.js"></script>
  <script type="text/javascript" src="script/slick.slider.min.js"></script>
  <script type="text/javascript" src="script/fancybox.pack.js"></script>
  <script type="text/javascript" src="script/isotope.min.js"></script>
  <script type="text/javascript" src="script/progressbar.js"></script>
  <script type="text/javascript" src="script/numscroller.js"></script>
  <script type="text/javascript" src="build/mediaelement-and-player.min.js"></script>
  <script type="text/javascript" src="script/functions.js"></script>
</body>

</html>