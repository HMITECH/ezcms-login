<?php
/*
 * ============================================================
 * Replace HTTP Links and Images with HTTPS
 * ============================================================
 *
 * PURPOSE
 * -------
 * Upgrades all http:// URLs to https:// in both link hrefs
 * and image srcs across the content block.
 *
 * WHY THIS MATTERS
 * ----------------
 * If your site runs on HTTPS but content contains http://
 * references, browsers will flag these as "mixed content"
 * and may block them entirely. This causes:
 *   - Broken images or links in the browser
 *   - Security warnings shown to visitors
 *   - Loss of the padlock icon on the page
 *
 * This is especially common after migrating a site to HTTPS
 * or importing content from an older site.
 *
 * WHAT IT UPDATES
 * ---------------
 * - <a href="http://...">  → <a href="https://...">
 * - <img src="http://..."> → <img src="https://...">
 *
 * NOTE
 * ----
 * This only changes the protocol prefix. If the remote server
 * does not support HTTPS the link will break. Run
 * Find-Broken-Links or Find-Broken-Images after this macro
 * to verify all URLs are still reachable.
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'replace-http-https.csv';

$updated = 0;

// --- Update <a href="http://..."> links ---
foreach ($html->find('a') as $link) {
    if (substr($link->href, 0, 7) === 'http://') {
        $link->href = 'https://' . substr($link->href, 7);
        $updated++;
    }
}

// --- Update <img src="http://..."> images ---
foreach ($html->find('img') as $img) {
    if (substr($img->src, 0, 7) === 'http://') {
        $img->src = 'https://' . substr($img->src, 7);
        $updated++;
    }
}

// Summary
if ($updated) {
    $this->log("$updated URL(s) upgraded from http:// to https://", 'success');
} else {
    $this->log('No http:// URLs found — nothing to change', 'inverse');
}
?>
