<?php

include_once("db.php");
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION['userId']))
{
  header('location:userLogout.php');
  exit; // stop here so the protected page is never sent
}
?>
