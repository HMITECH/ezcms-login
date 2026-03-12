<?php
/*
 * ============================================================
 * Find Broken Images
 * ============================================================
 *
 * PURPOSE
 * -------
 * Scans all <img src="..."> tags in the content and checks
 * each image URL with an HTTP HEAD request. Any image that
 * returns a non-200 status code is logged as broken.
 *
 * This macro is READ-ONLY — it reports problems but does not
 * change any page content.
 *
 * WHAT IT CHECKS
 * --------------
 * - Local image paths (starting with /) are converted to
 *   absolute URLs using the current server hostname.
 * - Only http/https URLs are tested. Data URIs and relative
 *   paths without a leading slash are skipped.
 *
 * NOTE: Running this on pages with many images may be slow
 * due to the outbound HTTP request for each image.
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'broken-images.csv';

// Find all image tags in the content block
$imgs  = $html->find('img');
$count = count($imgs);

if ($count) {

    $this->log("Found $count image(s) to check", 'info');
    $broken = 0;

    foreach ($imgs as $img) {

        $src = $img->src;

        // Skip empty src and data URIs (e.g. data:image/png;base64,...)
        if (!$src || substr($src, 0, 5) === 'data:') {
            continue;
        }

        // Convert root-relative paths (/path) to absolute for cURL
        if ($src[0] === '/') {
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            $src    = $scheme . '://' . $_SERVER['HTTP_HOST'] . $src;
        }

        // Only test http/https URLs
        if (substr($src, 0, 4) === 'http') {
            $ch = curl_init($src);
            curl_setopt($ch, CURLOPT_NOBODY, true);         // HEAD request only, no body
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);          // 10 second timeout per image
            curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status !== 200) {
                $broken++;
                $this->log("Broken image ($status): $src", 'important');
            }
        } else {
            $this->log("Skipped (unsupported path): $src", 'warning');
        }
    }

    // Summary
    if ($broken) {
        $this->log("$broken broken image(s) found", 'important');
    } else {
        $this->log('All images are OK', 'success');
    }

} else {
    $this->log('No images found in this content block', 'inverse');
}
?>
