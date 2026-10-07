<?php
// Redraws the SMS logo: same idea as the old one (green + blue ring, red "SMS", green wordmark),
// but drawn 4x larger and scaled down so the edges are smooth, with proper centring and alignment.
// Run: php -d extension=gd make_logo.php <outDir>

$out = rtrim($argv[1], '/\\');
$BOLD = 'C:/Windows/Fonts/segoeuib.ttf';
$SS = 4; // supersampling factor

$GREEN = array(22, 163, 74);   // #16a34a  (site brand green)
$BLUE  = array(14, 165, 233);  // #0ea5e9
$RED   = array(220, 38, 38);   // #dc2626
$DARKG = array(21, 128, 61);   // #15803d

function canvas($w, $h, $bg = null) {
    $im = imagecreatetruecolor($w, $h);
    imagealphablending($im, false);
    imagesavealpha($im, true);
    $fill = $bg ? imagecolorallocatealpha($im, $bg[0], $bg[1], $bg[2], 0) : imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefilledrectangle($im, 0, 0, $w, $h, $fill);
    imagealphablending($im, true);
    return $im;
}
function col($im, $c) { return imagecolorallocate($im, $c[0], $c[1], $c[2]); }
function disc($im, $cx, $cy, $r, $c) { imagefilledellipse($im, (int)$cx, (int)$cy, (int)($r * 2), (int)($r * 2), col($im, $c)); }

// Exact text box (ink only) for a font size
function box($font, $size, $text) {
    $b = imagettfbbox($size, 0, $font, $text);
    return array('w' => $b[2] - $b[0], 'h' => $b[1] - $b[7], 'x' => $b[0], 'top' => $b[7], 'bottom' => $b[1]);
}
// Largest size whose ink width fits $maxW
function fit($font, $text, $maxW) {
    $s = 10;
    while (box($font, $s + 1, $text)['w'] <= $maxW) $s++;
    return $s;
}

// The round mark, centred at (cx, cy) with outer radius R (all in supersampled px)
function mark($im, $cx, $cy, $R, $font) {
    global $GREEN, $BLUE, $RED;
    disc($im, $cx, $cy, $R, $GREEN);                 // thin green outer ring
    disc($im, $cx, $cy, $R * 0.935, array(255, 255, 255)); // hairline white gap
    disc($im, $cx, $cy, $R * 0.905, $BLUE);           // thick blue ring
    disc($im, $cx, $cy, $R * 0.745, array(255, 255, 255)); // white centre
    // "SMS" centred optically on the white disc
    $size = fit($font, 'SMS', $R * 1.12);
    $b = box($font, $size, 'SMS');
    $x = $cx - $b['w'] / 2 - $b['x'];
    $y = $cy + $b['h'] / 2 - $b['bottom'];
    imagettftext($im, $size, 0, (int)$x, (int)$y, col($im, $RED), $font, 'SMS');
}

function downscale($big, $w, $h) {
    $small = canvas($w, $h);
    imagealphablending($small, false);
    imagecopyresampled($small, $big, 0, 0, 0, 0, $w, $h, imagesx($big), imagesy($big));
    imagesavealpha($small, true);
    return $small;
}

// ---------- 1) Full horizontal logo: 1200 x 480 ----------
$W = 1200; $H = 480;
$im = canvas($W * $SS, $H * $SS);
$R = 224 * $SS; $cx = 240 * $SS; $cy = 240 * $SS;
mark($im, $cx, $cy, $R, $BOLD);

$lines = array('Scrap', 'Management', 'System');
$textX = 512 * $SS;
$maxW = ($W - 24) * $SS - $textX;               // right margin 24px
$size = fit($BOLD, 'Management', $maxW);         // longest word sets the size
$capH = box($BOLD, $size, 'S')['h'];             // cap height
$gap = $capH * 0.80;                             // space between lines
$blockH = $capH * 3 + $gap * 2;
$top = $cy - $blockH / 2;
foreach ($lines as $i => $word) {
    $b = box($BOLD, $size, $word);
    $baseline = $top + $capH * ($i + 1) + $gap * $i;
    imagettftext($im, $size, 0, (int)($textX - $b['x']), (int)$baseline, col($im, $i == 1 ? $DARKG : $GREEN), $BOLD, $word);
}
imagepng(downscale($im, $W, $H), "$out/logo1.png", 9);

// ---------- 2) Mark only, transparent: 512 x 512 ----------
$S = 512;
$im = canvas($S * $SS, $S * $SS);
mark($im, $S * $SS / 2, $S * $SS / 2, 250 * $SS, $BOLD);
imagepng(downscale($im, $S, $S), "$out/logo-mark.png", 9);

// ---------- 3) App icons on white (phones mask them to a shape) ----------
foreach (array(192, 512) as $S) {
    $im = canvas($S * $SS, $S * $SS, array(255, 255, 255));
    mark($im, $S * $SS / 2, $S * $SS / 2, $S * 0.40 * $SS, $BOLD);  // 80% wide = safe zone for masks
    imagepng(downscale($im, $S, $S), "$out/icon-$S.png", 9);
}
echo "done\n";
