<?php
include_once('db.php');
include_once('functions.php');
include_once('ui/layout.php');
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$isAdmin = isset($_SESSION['adminId']);
$userId = isset($_SESSION['userId']) ? (int)$_SESSION['userId'] : 0;
if (!$isAdmin && !$userId) { header('Location: userLogin.php'); exit; }

// Admin can open any invoice, a customer only their own (anyone could open any invoice before)
$invoiceId = isset($_GET['InvoiceId']) ? (int)$_GET['InvoiceId'] : 0;
$rows = selectData("SELECT * FROM `sales` WHERE id='$invoiceId'" . ($isAdmin ? '' : " AND user_id='$userId'"));
if (!count($rows)) { header('Location: ' . ($isAdmin ? 'adminOrders.php' : 'userOrders.php')); exit; }
$s = $rows[0];
$userRows = selectData("SELECT * FROM `users` WHERE id='" . (int)$s['user_id'] . "'");
$cust = count($userRows) ? $userRows[0] : array('name' => 'Deleted user', 'phone' => '', 'email' => '');
$items = selectData("SELECT * FROM `sales_items` WHERE sales_id='$invoiceId' ORDER BY id");
$subTotal = 0;
foreach ($items as $it) $subTotal += $it['total'];
$paid = $s['payment_status'] == '1';
$back = $isAdmin ? 'adminOrderDetails.php?id=' . $invoiceId : 'userOrderDetails.php?id=' . $invoiceId;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Invoice #<?php echo $invoiceId; ?> · Scrap Management System</title>
  <link rel="icon" type="image/png" href="../images/logo-mark.png">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="../assets/sms.css">
  <link rel="stylesheet" href="../assets/admin.css">
  <style>
    body.sms.admin { padding-bottom: 0; }
    .inv-bar { position: sticky; top: 0; z-index: 10; display: flex; gap: 10px; align-items: center; padding: 12px 16px;
      background: rgba(255,255,255,.92); backdrop-filter: blur(12px); border-bottom: 1px solid var(--line); }
    .inv-bar strong { flex: 1; font-size: 16px; }
    .inv-wrap { max-width: 860px; margin: 0 auto; padding: 16px; }
    .invoice { background: #fff; border-radius: 20px; box-shadow: var(--shadow); padding: 22px 18px; position: relative; overflow: hidden; }
    .inv-head { display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; padding-bottom: 18px; border-bottom: 2px solid var(--ink); }
    .inv-head img { height: 52px; }
    .inv-head h1 { font-size: 26px; font-weight: 800; letter-spacing: .02em; text-align: right; }
    .inv-head p { color: var(--muted); font-size: 13px; text-align: right; }
    .inv-parties { display: grid; grid-template-columns: 1fr; gap: 16px; padding: 18px 0; }
    .inv-parties h3 { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); margin-bottom: 6px; }
    .inv-parties p { font-size: 14px; line-height: 1.6; }
    .inv-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .inv-table th { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); padding: 10px 8px; background: var(--bg); }
    .inv-table td { padding: 12px 8px; border-bottom: 1px solid var(--line); vertical-align: top; }
    .inv-table .num { text-align: right; white-space: nowrap; }
    .inv-sum { margin-left: auto; width: 100%; max-width: 320px; margin-top: 14px; }
    .inv-sum div { display: flex; justify-content: space-between; padding: 6px 0; font-size: 14px; color: var(--ink-2); }
    .inv-sum .grand { border-top: 2px solid var(--ink); margin-top: 6px; padding-top: 12px; font-size: 18px; font-weight: 800; color: var(--ink); }
    .stamp { position: absolute; right: 24px; top: 120px; transform: rotate(-14deg); border: 3px solid currentColor; border-radius: 12px;
      padding: 4px 14px; font-weight: 800; font-size: 22px; letter-spacing: .1em; opacity: .18; pointer-events: none; }
    .stamp.paid { color: var(--brand); } .stamp.unpaid { color: var(--danger); }
    .inv-foot { margin-top: 24px; padding-top: 14px; border-top: 1px dashed var(--line); color: var(--muted); font-size: 13px; text-align: center; }
    @media (min-width: 640px) { .invoice { padding: 36px; } .inv-parties { grid-template-columns: 1fr 1fr 1fr; } }
    @media (max-width: 560px) { .inv-table .hide-sm { display: none; } }
    @media print {
      .inv-bar { display: none; }
      body.sms.admin { background: #fff; }
      .inv-wrap { padding: 0; max-width: none; }
      .invoice { box-shadow: none; border-radius: 0; padding: 0; }
    }
  </style>
</head>
<body class="sms admin">
  <div class="inv-bar">
    <a href="<?php echo e($back); ?>" class="icon-btn" aria-label="Back"><i class="fa-solid fa-arrow-left"></i></a>
    <strong>Invoice #<?php echo $invoiceId; ?></strong>
    <button type="button" class="btn btn-primary btn-sm" onclick="window.print()"><i class="fa-solid fa-print"></i> Print / PDF</button>
  </div>

  <div class="inv-wrap">
    <article class="invoice">
      <span class="stamp <?php echo $paid ? 'paid' : 'unpaid'; ?>"><?php echo $paid ? 'PAID' : 'UNPAID'; ?></span>
      <header class="inv-head">
        <img src="../images/logo1.png" alt="Scrap Management System">
        <div><h1>INVOICE</h1><p>#<?php echo $invoiceId; ?> &middot; <?php echo date('d M Y', strtotime($s['dt'])); ?></p></div>
      </header>

      <section class="inv-parties">
        <div><h3>From</h3><p><strong>Scrap Management System</strong><br>Nr Railway Station, Vadodara<br>+91 8140599726<br>vedang16102000@gmail.com</p></div>
        <div><h3>Bill to</h3><p><strong><?php echo e($cust['name']); ?></strong><br><?php echo nl2br(e($s['delivery_address'])); ?><br><?php echo e($cust['phone']); ?><br><?php echo e($cust['email']); ?></p></div>
        <div><h3>Payment</h3><p><?php echo $s['payment_mode'] == '0' ? 'Pay on delivery' : 'Online'; ?><br>Status: <strong><?php echo $paid ? 'Paid' : 'Pending'; ?></strong>
          <?php if (!empty($s['transaction_id'])) { ?><br>Txn: <?php echo e($s['transaction_id']); ?><?php } ?></p></div>
      </section>

      <table class="inv-table">
        <thead><tr><th>#</th><th>Product</th><th class="num">Qty</th><th class="num hide-sm">Price</th><th class="num">Amount</th></tr></thead>
        <tbody>
          <?php foreach ($items as $i => $it) { ?>
            <tr><td><?php echo $i + 1; ?></td><td><strong><?php echo e($it['product_name']); ?></strong></td>
              <td class="num"><?php echo (int)$it['qty']; ?></td><td class="num hide-sm"><?php echo money($it['price']); ?></td><td class="num"><?php echo money($it['total']); ?></td></tr>
          <?php } ?>
          <?php if (!count($items)) { ?><tr><td colspan="5" class="muted">No items saved for this order.</td></tr><?php } ?>
        </tbody>
      </table>

      <div class="inv-sum">
        <div><span>Subtotal</span><span><?php echo money($subTotal); ?></span></div>
        <div><span>Tax</span><span><?php echo money($s['tax']); ?></span></div>
        <div><span>Delivery</span><span><?php echo money($s['delivery_changes']); ?></span></div>
        <div class="grand"><span>Total</span><span><?php echo money($s['total']); ?></span></div>
      </div>

      <p class="inv-foot">Thank you for recycling with us! Questions about this invoice? Call +91 8140599726.</p>
    </article>
  </div>
</body>
</html>
