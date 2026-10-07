<?php
session_start();
include('admin/functions.php');
include('admin/db.php');

if(isset($_SESSION['userId']))
{
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
                            <h1>Blank</h1>
                            <ul class="scrapcar-breadcrumb">
                                <li><a href="index.php">Home</a></li>
                                <li class="active">Blank</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="scrapcar-footer-newslatter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- <div class="scrapcar-newslatter-text"> -->
                            <!-- <h2>Find a branch near you</h2>
                            <form>
                                <input type="text" value="Type your location" onblur="if(this.value == '') { this.value ='Type your location'; }" onfocus="if(this.value =='Type your location') { this.value = ''; }">
                                <label><i class="fa fa-search"></i><input type="submit" value="Find my branch"></label>
                            </form> -->
                        <!-- </div> -->
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