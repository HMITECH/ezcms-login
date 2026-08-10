# Changelog

All notable changes to ezCMS are documented here. The format is based on
[Keep a Changelog](https://keepachangelog.com/), and the project aims to follow
semantic-ish major versioning.

## [7.0] - 2026-08-10

Version 7 is a front-end modernization release: the admin panel moves from
Bootstrap 2 / older jQuery to a current, vendored stack, the revision system is
reworked to load lazily, and the database schema gains the indexes those queries
rely on. The public site, macro engine, and content model are unchanged — an
in-place upgrade only touches the admin panel and adds a few indexes.

### Added
- **CodeMirror editor toolbar** across every editor page (layouts, includes,
  styles, scripts, controller, macros): find / replace / go-to-line, code
  folding by level, and a persisted font-size control (`initCMToolbar` in
  `js/gitFileCode.js`).
- **Lazy-loaded revision logs with pagination** on every page that shows one
  (settings, the six file editors, pages, users). Logs now fetch after render
  via AJAX, page at 10 rows each (page numbers on the left, "Showing x–y of N"
  on the right), transition in place, and pull a revision's content on demand
  instead of dumping every revision inline — pages with hundreds of revisions
  now render instantly.
- **CSRF protection** on all state-changing admin forms, plus `X-Frame-Options`
  / CSP `frame-ancestors` headers.
- Sample **nginx** config mirroring the Apache `.htaccess` rules.
- `_sql/ezcms.7.0.sql` (fresh install) and `_sql/upgrade.6-to-7.sql` (in-place
  index migration).

### Changed
- **Bootstrap 2.3.1 → 5.3.8** and **jQuery → 4.0.0** (+ `jquery-migrate`),
  both vendored under `vendor/` and `js/`. Legacy BS2 markup keeps working
  through three transitional compatibility shims (`css/bs2-compat.css`,
  `css/ezcms-icons.css`, `js/bs-typeahead-compat.js`).
- **CodeMirror 5.11.0 → 5.65.21** (final v5; CM6 intentionally skipped).
- Admin base font set to 14px globally; header, footer, treeview, buttons,
  checkboxes, info labels, dropdown forms, `find.php`, and `redirects.php`
  restyled to match the previous look on the new framework.
- README rewritten around ezCMS's philosophy; PHP core modernized to 8.0+
  idioms (`[]`, short destructuring).
- **Database indexes** brought in line with the query patterns:
  - `git_files` — added `createdby`, `createdon`
  - `git_pages` — added `createdon`
  - `site` — added `createdby`
  - `pages` — dropped redundant duplicate indexes (`proprity`, `url_2`)

### Fixed
- Change alerts with the legacy `warn` class now map to Bootstrap 5 `warning`
  so they render with a background.
- "There are no revisions." empty state now spans the correct 5 columns.
- Include-path helper on `styles.php` / `scripts.php` now derives the real admin
  folder name instead of assuming a 5-character folder.
- CodeMirror find/replace/goto dialogs, fold levels, and the sidebar-toggle
  layout regressions.

### Upgrading from 6.x
No schema (column) changes. Pull the new code, then apply the index migration:

```bash
mysql -u root -p dbname < _sql/upgrade.6-to-7.sql
```

Fresh installs should import `_sql/ezcms.7.0.sql`.

## [6.x]

Earlier releases. See `_sql/ezcms.6.0.sql` and the git history for details.
