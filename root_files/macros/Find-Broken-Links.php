<?php
/*
 * ============================================================
 * Find Broken Links
 * ============================================================
 *
 * PURPOSE
 * -------
 * Scans all <a href="..."> tags in the content and checks
 * each URL with an HTTP HEAD request. Any link that returns
 * a non-200 status code is logged as broken.
 *
 * This macro is READ-ONLY — it reports problems but does not
 * change any page content.
 *
 * WHAT IT CHECKS
 * --------------
 * - Local links (starting with /) are converted to absolute
 *   URLs using the current server's hostname before testing.
 * - Only http/https links are tested. Mailto, tel, anchor
 *   links (#) and relative paths are skipped.
 *
 * NOTE: Running this on pages with many links may be slow
 * due to the outbound HTTP request for each link.
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'broken-links.csv';

// Find all anchor tags in the content block
$links = $html->find('a');
$count = count($links);

if ($count) {

    $this->log("Found $count links to check", 'info');
    $broken = 0;

    foreach ($links as $link) {

        $src = $link->href;

        // Skip empty hrefs, anchors (#), mailto:, tel:, javascript:
        if (!$src || $src[0] === '#' || strpos($src, ':') !== false && substr($src, 0, 4) !== 'http') {
            continue;
        }

        // Convert root-relative URLs (/path) to absolute for cURL
        if ($src[0] === '/') {
            $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            $src    = $scheme . '://' . $_SERVER['HTTP_HOST'] . $src;
        }

        // Only test http/https URLs
        if (substr($src, 0, 4) === 'http') {
            $ch = curl_init($src);
            curl_setopt($ch, CURLOPT_NOBODY, true);          // HEAD request only, no body
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // follow redirects
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);           // 10 second timeout per link
            curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status !== 200) {
                $broken++;
                $this->log("Broken link ($status): $src", 'important');
            }
        } else {
            $this->log("Skipped (unsupported scheme): $src", 'warning');
        }
    }

    // Summary
    if ($broken) {
        $this->log("$broken broken link(s) found", 'important');
    } else {
        $this->log('All links are OK', 'success');
    }

} else {
    $this->log('No links found in this content block', 'inverse');
}
?>
