<?php
/*
 * ============================================================
 * Dummy Logging Demo
 * ============================================================
 *
 * PURPOSE
 * -------
 * This macro does NOT modify any page content.
 * It simply logs one message in each label style so you can
 * see what the output looks like in the Execute screen.
 *
 * Run this on any page to verify that the macro engine is
 * working and to understand the available log label types
 * before writing your own macros.
 *
 * ============================================================
 */

// Optional: uncomment to also write log output to a CSV file.
// The file will be saved to site-assets/logs/macro/
// $this->logFile = 'dummy-macro.csv';

$this->log( 'Default  — appears with a Grey background',   'notice'    );
$this->log( 'Success  — appears with a Green background',  'success'   );
$this->log( 'Warning  — appears with an Orange background','warning'   );
$this->log( 'Important — appears with a Red background',   'important' );
$this->log( 'Info     — appears with a Blue background',   'info'      );
$this->log( 'Inverse  — appears with a Black background',  'inverse'   );
?>
