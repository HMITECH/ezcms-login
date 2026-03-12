<?php
/*
 * ============================================================
 * Add Height & Width Attributes to Images
 * ============================================================
 *
 * PURPOSE
 * -------
 * Reads the actual pixel dimensions of each image and adds
 * height and width attributes to any <img> tag that is
 * missing them.
 *
 * Explicitly setting image dimensions reduces Cumulative
 * Layout Shift (CLS), which is a Google Core Web Vitals
 * metric that affects SEO rankings.
 *
 * BEHAVIOUR
 * ---------
 * - Only local images (src starts with /) are processed.
 * - If an image already has a height OR width attribute,
 *   that attribute is left unchanged.
 * - If the image file cannot be read, it is logged as a warning.
 *
 * EXAMPLE
 * -------
 * Before: <img src="/site-assets/images/logo.jpg">
 * After:  <img src="/site-assets/images/logo.jpg" width="200px" height="100px">
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'add-height-width-images.csv';

// Find all image tags in the content block
$imgs  = $html->find('img');
$count = count($imgs);

if ($count) {

    $this->log("Found $count image(s)", 'info');
    $updated = 0;

    foreach ($imgs as $img) {

        $src = $img->src;

        // Skip empty src and data URIs
        if (!$src || substr($src, 0, 5) === 'data:') {
            continue;
        }

        // Convert root-relative path to absolute URL for getimagesize()
        if ($src[0] === '/') {
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            $src    = $scheme . '://' . $_SERVER['HTTP_HOST'] . $src;
        }

        if (substr($src, 0, 4) === 'http') {
            // Suppress errors — getimagesize returns false if it can't read the image
            $size = @getimagesize($src);

            if ($size) {
                [$width, $height] = $size;
                $updated++;
                // Only set the attribute if it is not already present
                if (!$img->width)  $img->width  = $width  . 'px';
                if (!$img->height) $img->height = $height . 'px';
            } else {
                $this->log("Could not read dimensions for: $src", 'warning');
            }
        } else {
            $this->log("Skipped (unsupported path): $src", 'warning');
        }
    }

    // Summary
    if ($updated) {
        $this->log("$updated image(s) updated with dimensions", 'success');
    } else {
        $this->log('No images needed updating', 'inverse');
    }

} else {
    $this->log('No images found in this content block', 'inverse');
}
?>
