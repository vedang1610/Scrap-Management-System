<?php

include_once("db.php");
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION['adminId']))
{
  header('location:logout.php');
  exit; // stop here so the protected page is never sent
}
?>
