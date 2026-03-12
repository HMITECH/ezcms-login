<?php
/*
 * ============================================================
 * Add Bootstrap Classes to Tables
 * ============================================================
 *
 * PURPOSE
 * -------
 * Adds Bootstrap table CSS classes to any <table> element
 * that does not already have them, so tables are styled
 * consistently with the rest of the site.
 *
 * This is particularly useful after importing content from
 * Word documents, older CMS exports, or plain HTML pages
 * where tables have no styling at all.
 *
 * CLASSES ADDED
 * -------------
 * The following Bootstrap 2/3 table classes are added:
 *   table           — base Bootstrap table styles
 *   table-striped   — alternating row background colours
 *   table-bordered  — borders on all cells
 *   table-hover     — highlight row on mouse-over
 *
 * Remove any of the classes from $classesToAdd below if you
 * do not want them applied to all tables on the page.
 *
 * BEHAVIOUR
 * ---------
 * - If a table already has the "table" class, it is skipped
 *   entirely (assumed to already be styled intentionally).
 * - Existing class attributes on the table are preserved.
 *
 * EXAMPLE
 * -------
 * Before: <table>
 * After:  <table class="table table-striped table-bordered table-hover">
 *
 * ============================================================
 */

// Optional: uncomment to also write results to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'add-bootstrap-table-classes.csv';

// Edit this list to control which classes are applied
$classesToAdd = ['table', 'table-striped', 'table-bordered', 'table-hover'];

// Find all table tags in the content block
$tables = $html->find('table');
$count  = count($tables);

if ($count) {

    $this->log("Found $count table(s)", 'info');
    $updated = 0;

    foreach ($tables as $table) {

        // Get existing classes as an array (handle empty/missing class)
        $existing = $table->class ? explode(' ', trim($table->class)) : [];

        // Skip tables that already have the base 'table' class
        if (in_array('table', $existing)) {
            $this->log("Skipped (already styled): &lt;table class=\"" . $table->class . "\"&gt;", 'notice');
            continue;
        }

        // Merge existing classes with the new ones (no duplicates)
        $merged       = array_unique(array_merge($existing, $classesToAdd));
        $table->class = implode(' ', array_filter($merged));
        $updated++;
    }

    // Summary
    if ($updated) {
        $this->log("$updated table(s) updated with Bootstrap classes", 'success');
    } else {
        $this->log('All tables already have Bootstrap classes', 'inverse');
    }

} else {
    $this->log('No tables found in this content block', 'inverse');
}
?>
