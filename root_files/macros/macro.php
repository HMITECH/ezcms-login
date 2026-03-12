<?php
/*
 * ============================================================
 * ezCMS Macro Template — Copy this file to create a new macro
 * ============================================================
 *
 * Macros let you automate bulk changes to page content across
 * your entire site. Each macro runs against the HTML of one or
 * more content blocks on a page before saving.
 *
 * CONTENT BLOCKS AVAILABLE
 * -------------------------
 * Content      (maincontent)   — main body of the page
 * Aside 1      (sidecontent)   — left/right sidebar
 * Aside 2      (sidercontent)  — second sidebar
 *
 * You choose which blocks to run against in the Execute screen.
 *
 * THE $html VARIABLE
 * ------------------
 * Each block's HTML is parsed into a DOM object exposed as $html.
 * This uses the PHP Simple HTML DOM library.
 *
 * Read elements:
 *   $links = $html->find('a');        // all <a> tags
 *   $imgs  = $html->find('img');      // all <img> tags
 *   $h2s   = $html->find('h2');       // all <h2> tags
 *
 * Modify attributes:
 *   $imgs[0]->src    = '/new/path.jpg';
 *   $imgs[0]->width  = '200px';
 *   $links[0]->href  = 'https://example.com';
 *   $links[0]->class = 'btn btn-primary';
 *
 * Read inner HTML / text:
 *   echo $imgs[0]->outertext;   // full tag as string
 *   echo $links[0]->innertext;  // text between tags
 *
 * Full documentation:
 *   https://simplehtmldom.sourceforge.io/manual.htm
 *   https://github.com/jkrrv/php-dom-parser
 *
 * REVISIONS
 * ---------
 * Every time a macro saves changes to a page a revision is
 * created automatically, so you can roll back if needed.
 *
 * LOGGING
 * -------
 * Use $this->log() to display messages in the Execute screen:
 *
 *   $this->log( 'Grey  — general info',        'notice'    );
 *   $this->log( 'Green — action succeeded',    'success'   );
 *   $this->log( 'Orange — something to check', 'warning'   );
 *   $this->log( 'Red   — error or problem',    'important' );
 *   $this->log( 'Blue  — informational',       'info'      );
 *   $this->log( 'Black — standout message',    'inverse'   );
 *
 * LOG TO FILE (optional)
 * ----------------------
 * Set $this->logFile to write a CSV log you can download via
 * the File Manager. The file is saved in site-assets/logs/macro/
 *
 *   $this->logFile = 'my-macro.csv';
 *
 * ============================================================
 * Add your macro code below this comment block.
 * ============================================================
 */
?>
