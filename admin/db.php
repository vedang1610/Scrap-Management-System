<?php

// Database login. On a live server put the hosting details in admin/config.php
// (copy config.example.php). config.php is not uploaded to GitHub.
$host = "localhost";
$user = "root";
$password = "";
$database = "nsp_scrap";

if (is_file(__DIR__ . '/config.php')) {
    include(__DIR__ . '/config.php');
}

try{
  $con = mysqli_connect($host,$user,$password,$database) or die("Unable to Connect with the Database.");
  mysqli_set_charset($con, 'utf8mb4');
}
catch(Exception $e)
{
    die("Unable to Connect with the Database.");
}

?>
