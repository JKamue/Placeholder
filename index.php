<?php

$standard_width  = 800;
$standard_height = 400;
$max_image_size  = 1000 * 1000;

$width  = $_GET["width"]  ?? $standard_width;
$height = $_GET["height"] ?? $standard_height;

// Check if input is a number
if (!is_numeric($width) || !is_numeric($height)) {
    echo "Size has to be numeric!";
    exit();
}

// Make sure they are ints
$width = round($width);
$height = round($height);

// Check if both are positive
if ($width < 1 || $height < 1) {
    echo "Size has to be bigger than zero";
    exit();
}

// Protection against oversized images
if ($width * $height > $max_image_size) {
    echo "Image too large!";
    exit();
}


// Generate the image
$im = imagecreate($width, $height);

// Set colors
$bg        = imagecolorallocate($im, 200, 200, 200);
$textcolor = imagecolorallocate($im, 50, 50, 50);
$font      = 'Roboto-Medium.ttf';

if ($width > $height * 1.3) {
    // If image is horizontal
    $text       = "{$width}x{$height}";
    $top        = 2.5 * $height / 4;
    $k          = 1 + (-3 + strlen($width) + strlen($height)) / 10;
    $text_width = $height * $k;
    $left       = ($width - $text_width) / 2;
    imagettftext($im, $height / 4, 0, $left, $top, $textcolor, $font, $text);
} else {
    if ($width > $height * 0.8) {
        // Edgecase (image nearly square)
        $letter_height = $width / 5;
        $left          = $width / 3.7;
        $x_left        = $width / 2.3;
    } else {
        // If image is portrait
        $letter_height = $width / 3;
        $left          = $width / 8;
        $x_left        = $width / 2.5;
    }
    $text_size       = $width;
    $xheight         = $letter_height * 1.2;
    $letter_distance = $letter_height / 15;
    $top             = ($height - (2 * $letter_distance + $xheight)) / 2;
    
    imagettftext($im, $letter_height, 0, $left, $top, $textcolor, $font, $width);
    imagettftext($im, $letter_height, 0, $x_left, $top + $letter_height + $letter_distance, $textcolor, $font, "x");
    imagettftext($im, $letter_height, 0, $left, $top + $letter_height + 2 * $letter_distance + $xheight, $textcolor, $font, $height);
}

// Display image
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>