<?php
/*
 * ============================================================
 * Add rel="noopener noreferrer" to External Links
 * ============================================================
 *
 * PURPOSE
 * -------
 * Finds all <a> tags that open in a new tab (target="_blank")
 * and ensures they have rel="noopener noreferrer" set.
 *
 * WHY THIS MATTERS
 * ----------------
 * When a link uses target="_blank" without rel="noopener",
 * the opened page can access and manipulate the opener window
 * via window.opener — a known phishing and security risk.
 *
 * Adding rel="noopener noreferrer":
 *   - noopener   : blocks the new tab from accessing window.opener
 *   - noreferrer : also prevents the Referer header being sent,
 *                  which gives additional privacy protection
 *
 * This fix is recommended by Google, OWASP, and MDN for all
 * target="_blank" links pointing to external sites.
 *
 * BEHAVIOUR
 * ---------
 * - Only processes links with target="_blank"
 * - If rel already contains noopener/noreferrer, nothing is changed
 * - Existing rel values (e.g. rel="nofollow") are preserved and
 *   the noopener/noreferrer values are appended
 *
 * EXAMPLE
 * -------
 * Before: <a href="https://example.com" target="_blank">Visit</a>
 * After:  <a href="https://example.com" target="_blank" rel="noopener noreferrer">Visit</a>
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'add-noopener-links.csv';

// Find all anchor tags in the content block
$links = $html->find('a');
$count = count($links);

if ($count) {

    $this->log("Found $count link(s)", 'info');
    $updated = 0;

    foreach ($links as $link) {

        // Only care about links that open in a new tab
        if (strtolower($link->target) !== '_blank') {
            continue;
        }

        $rel = $link->rel ? strtolower($link->rel) : '';

        // Check which values are already present
        $hasNoopener   = strpos($rel, 'noopener')   !== false;
        $hasNoreferrer = strpos($rel, 'noreferrer') !== false;

        if (!$hasNoopener || !$hasNoreferrer) {
            // Build the final rel value, preserving any existing values
            $parts = $rel ? explode(' ', trim($rel)) : [];
            if (!$hasNoopener)   $parts[] = 'noopener';
            if (!$hasNoreferrer) $parts[] = 'noreferrer';
            $link->rel = implode(' ', array_unique($parts));
            $updated++;
        }
    }

    // Summary
    if ($updated) {
        $this->log("$updated link(s) updated with rel=\"noopener noreferrer\"", 'success');
    } else {
        $this->log('All target="_blank" links already have noopener/noreferrer', 'inverse');
    }

} else {
    $this->log('No links found in this content block', 'inverse');
}
?>
