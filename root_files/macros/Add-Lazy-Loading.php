<?php
/*
 * ============================================================
 * Add Lazy Loading to Images
 * ============================================================
 *
 * PURPOSE
 * -------
 * Adds the loading="lazy" attribute to every <img> tag that
 * does not already have a loading attribute set.
 *
 * Lazy loading tells the browser not to download images until
 * they are near the viewport. This reduces initial page load
 * time and bandwidth, and improves Google PageSpeed / Core
 * Web Vitals scores.
 *
 * This is a native HTML5 attribute supported by all modern
 * browsers — no JavaScript library is required.
 *
 * EXAMPLE
 * -------
 * Before: <img src="/site-assets/images/photo.jpg" alt="photo">
 * After:  <img src="/site-assets/images/photo.jpg" alt="photo" loading="lazy">
 *
 * NOTE
 * ----
 * Avoid lazy-loading the first visible image on the page
 * (the hero/banner image), as this can slow down Largest
 * Contentful Paint (LCP). If you have a hero image, add
 * loading="eager" to it manually after running this macro.
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'add-lazy-loading.csv';

// Find all image tags in the content block
$imgs  = $html->find('img');
$count = count($imgs);

if ($count) {

    $this->log("Found $count image(s)", 'info');
    $updated = 0;

    foreach ($imgs as $img) {
        // Only add the attribute if it is not already set
        if (!$img->loading) {
            $img->loading = 'lazy';
            $updated++;
        }
    }

    // Summary
    if ($updated) {
        $this->log("$updated image(s) updated with loading=\"lazy\"", 'success');
    } else {
        $this->log('All images already have a loading attribute', 'inverse');
    }

} else {
    $this->log('No images found in this content block', 'inverse');
}
?>
