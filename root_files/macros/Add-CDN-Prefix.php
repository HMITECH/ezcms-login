<?php
/*
 * ============================================================
 * Add CDN Prefix to Image Sources
 * ============================================================
 *
 * PURPOSE
 * -------
 * Prepends a CDN URL prefix to the src attribute of local
 * images so they are served through your CDN instead of
 * directly from the web server.
 *
 * The default prefix is configured for Cloudflare's Image
 * Resizing feature, which also converts images to WebP/AVIF
 * automatically for supported browsers.
 *
 * Change $cdnUrl below to match whichever CDN you are using.
 *
 * EXAMPLE
 * -------
 * Before: <img src="/site-assets/images/logo.jpg">
 * After:  <img src="/cdn-cgi/image/quality=75,f=auto/site-assets/images/logo.jpg">
 *
 * REVERTING
 * ---------
 * Use the Remove-CDN-Prefix macro to undo this change.
 * A revision is always saved before any changes are made.
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'add-cdn-prefix.csv';

// Change this to match your CDN's image URL prefix
// Cloudflare Image Resizing default:
$cdnUrl = '/cdn-cgi/image/quality=75,f=auto';

// Find all image tags in the content block
$imgs  = $html->find('img');
$count = count($imgs);

if ($count) {

    $this->log("Found $count image(s)", 'info');
    $updated = 0;

    foreach ($imgs as $img) {

        // Only process local images stored in site-assets
        if (strpos($img->src, '/site-assets') === 0) {
            $img->src = $cdnUrl . $img->src;
            $updated++;
        }
    }

    // Summary
    if ($updated) {
        $this->log("$updated image(s) updated with CDN prefix", 'success');
    } else {
        $this->log('No local images found to update', 'inverse');
    }

} else {
    $this->log('No images found in this content block', 'inverse');
}
?>
