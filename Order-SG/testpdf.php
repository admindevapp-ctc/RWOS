<?php
// Load a png image with alpha channel
$png = imagecreatefrompng('sup_logo/720411.png');

// Turn off alpha blending
imagealphablending($png, false);

// Do desired operations

// Set alpha flag
//imagesavealpha($png, true);

// Output image to browser
header('Content-Type: image/png');

imagepng($png);
imagedestroy($png);
?>