<?php

include_once("db.php");
session_start();
if(!isset($_SESSION['userId']))
{
  header('location:userLogout.php');
}
// else
// {
//   header('location:logout.php');
// }
?>
