<?php

session_start();

unset($_SESSION['adminId']);
unset($_SESSION['adminName']);
unset($_SESSION['adminEmail']);
unset($_SESSION['adminPassword']);
unset($_SESSION['adminDate']);

// session_destroy();
header('location:index.php');

?>
