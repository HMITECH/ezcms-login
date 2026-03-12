<?php
/*
 * ============================================================
 * Find Images Missing Alt Text
 * ============================================================
 *
 * PURPOSE
 * -------
 * Scans all <img> tags and reports any that are missing an
 * alt attribute, or have an empty alt attribute.
 *
 * This macro is READ-ONLY — it reports problems but does not
 * change any page content.
 *
 * WHY THIS MATTERS
 * ----------------
 * The alt attribute serves two purposes:
 *
 * 1. ACCESSIBILITY — screen readers read the alt text aloud
 *    for visually impaired users. Missing alt text fails
 *    WCAG 2.1 accessibility guidelines.
 *
 * 2. SEO — search engines use alt text to understand image
 *    content. Missing alt text is a missed SEO opportunity.
 *
 * HOW TO FIX
 * ----------
 * After running this audit, edit each flagged page and add a
 * short descriptive alt attribute to each image, e.g.:
 *   <img src="/site-assets/images/team.jpg" alt="Our team at the 2024 conference">
 *
 * Images that are purely decorative should use an empty alt:
 *   <img src="/site-assets/images/divider.png" alt="">
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'missing-alt-text.csv';

// Find all image tags in the content block
$imgs  = $html->find('img');
$count = count($imgs);

if ($count) {

    $this->log("Found $count image(s)", 'info');
    $missing = 0;

    foreach ($imgs as $img) {

        // getAttribute returns false if attribute does not exist at all
        $hasAlt   = ($img->getAttribute('alt') !== false);
        $emptyAlt = ($hasAlt && trim($img->alt) === '');

        if (!$hasAlt) {
            $missing++;
            $this->log("Missing alt attribute: " . $img->src, 'important');
        } elseif ($emptyAlt) {
            // Empty alt is valid for decorative images — log as info only
            $this->log("Empty alt (decorative?): " . $img->src, 'notice');
        }
    }

    // Summary
    if ($missing) {
        $this->log("$missing image(s) are missing an alt attribute", 'important');
    } else {
        $this->log('All images have an alt attribute', 'success');
    }

} else {
    $this->log('No images found in this content block', 'inverse');
}
?>
