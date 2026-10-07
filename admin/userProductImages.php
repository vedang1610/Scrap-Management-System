<?php
// "Add Scrap Products" was removed from the customer account.
// It let any logged-in customer edit or delete every product in the shop.
// Products are managed by the admin only (admin/products.php). Original: _backup_before_redesign/admin/
include_once("userCheckLogin.php");
include_once("functions.php");
include_once("ui/layout.php");
redirect_to('userDashboard.php');
