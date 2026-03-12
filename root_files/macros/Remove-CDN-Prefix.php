<?php
/*
 * ============================================================
 * Remove CDN Prefix from Image Sources
 * ============================================================
 *
 * PURPOSE
 * -------
 * Strips the CDN URL prefix from image src attributes,
 * reverting them back to direct local paths.
 *
 * This is the companion/undo macro for Add-CDN-Prefix.
 * Use it if you need to switch CDN providers, roll back the
 * CDN change, or migrate the site.
 *
 * Change $cdnUrl below to match what was used when the CDN
 * prefix was originally added.
 *
 * EXAMPLE
 * -------
 * Before: <img src="/cdn-cgi/image/quality=75,f=auto/site-assets/images/logo.jpg">
 * After:  <img src="/site-assets/images/logo.jpg">
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'remove-cdn-prefix.csv';

// Must match the prefix that was added by Add-CDN-Prefix
$cdnUrl = '/cdn-cgi/image/quality=75,f=auto';

// Find all image tags in the content block
$imgs  = $html->find('img');
$count = count($imgs);

if ($count) {

    $this->log("Found $count image(s)", 'info');
    $updated = 0;

    foreach ($imgs as $img) {

        // Only process images that actually have the CDN prefix
        if (strpos($img->src, $cdnUrl) === 0) {

            // Strip the CDN prefix to recover the original local path
            $localPath = substr($img->src, strlen($cdnUrl));

            // Sanity check — the remainder should be a /site-assets path
            if (strpos($localPath, '/site-assets') === 0) {
                $img->src = $localPath;
                $updated++;
            } else {
                $this->log("Could not recover local path for: " . $img->src, 'warning');
            }
        }
    }

    // Summary
    if ($updated) {
        $this->log("$updated image(s) had CDN prefix removed", 'success');
    } else {
        $this->log('No CDN-prefixed images found', 'inverse');
    }

} else {
    $this->log('No images found in this content block', 'inverse');
}
?>
