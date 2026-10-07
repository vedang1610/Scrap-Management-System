<?php
session_start();
unset($_SESSION['userId']);
unset($_SESSION['userAadharNo']);
unset($_SESSION['userName']);
unset($_SESSION['userEmail']);
unset($_SESSION['userPassword']);
unset($_SESSION['userDate']);

header('location:userLogin.php');

?>