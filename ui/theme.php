<?php
// ui/theme.php - picks light or dark before the page paints (no white flash).
// Saved choice (theme button, assets/sms.js) wins, else the phone/computer setting.
?>
<script>
(function () {
  var t = null;
  try { t = localStorage.getItem('sms-theme'); } catch (e) {}
  if (t !== 'light' && t !== 'dark') t = window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  document.documentElement.setAttribute('data-theme', t);
})();
</script>
