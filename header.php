<header id="scrapcar-header" class="scrapcar-header-one">
            <div class="scrapcar-top-strip">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- <div class="scrapcar-top-strip-info">
                                <ul>
                                    <li><a href="404.html">Why Us?</a></li>
                                    <li><a href="404.html">How will I be paid?</a></li>
                                    <li><a href="faq.html">FAQs</a></li>
                                    <li><a href="404.html">Video</a></li>
                                </ul>
                            </div> -->
                            <div class="scrapcar-right-section">
                                <!-- <span><i class="icon icon-telephone2"></i>Call Us Free <small>(012) 345 - 6789</small></span> -->
                                <?php 
                                if(isset($_SESSION['userId']))
                                {
                                  ?>
                                    <a href="admin/userDashboard.php" class="scrapcar-simple-btn">
                                        <i class="automechanic-icon automechanic-people"></i>
                                        My Account
                                    </a>
                                  <?php
                                }
                                else
                                {
                                  ?>
                                    <a href="register.php" class="scrapcar-simple-btn">
                                        <i class="automechanic-icon automechanic-people"></i>
                                        Register / Login
                                    </a>
                                  <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="scrapcar-main-header">
                <div class="container">
                    <div class="row">
                        <aside class="col-md-2">
                            <a href="index.php" class="logo1"><img src="images/logo1.png" alt=""></a>
                        </aside>
                        <aside class="col-md-10">
                            <div class="scrapcar-navigation">

                                <a href="#menu" class="menu-link active"><span></span></a>
                                <nav id="menu" class="menu navbar navbar-default">
                                    <ul class="level-1 navbar-nav">
                                        <li class="active"><a href="index.php">Home</a></li>
                                        <li class=""><a href="scrap.php">Scrap Products</a></li>
										<li class=""><a href="feedback.php">feedback</a></li>
                                        <li class=""><a href="about.php">About Us</a></li>
                                        <li class=""><a href="contact.php">Contact</a></li>
										
                                    </ul>
                                </nav>

                                <!-- <a href="404.html" class="scrap-fancy-btn"><i class="fa fa-file-text-o"></i>make an appointment</a> -->
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </header>
