<?php
// ui/head.php - <head> tags shared by the redesigned pages.
// Set $pageTitle and $assetBase ('' for root pages, '../' for admin pages) before including.
$assetBase = isset($assetBase) ? $assetBase : '';
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#ffffff">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Scrap MS">
<title><?php echo htmlspecialchars($pageTitle); ?> · Scrap Management System</title>
<link rel="manifest" href="<?php echo $assetBase; ?>manifest.webmanifest">
<link rel="icon" type="image/png" href="<?php echo $assetBase; ?>images/logo-mark.png">
<link rel="apple-touch-icon" href="<?php echo $assetBase; ?>images/icon-192.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo $assetBase; ?>assets/sms.css?v=<?php echo @filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/sms.css'); ?>">
