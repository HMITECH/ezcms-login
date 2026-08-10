# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

ezCMS is a developer-focused PHP CMS that gives direct code access via an integrated CodeMirror editor. This repo (`ezcms-login/`) is the CMS admin panel; the public-facing website is served from the web root (populated from `root_files/`).

## No Build System

Runtime PHP project — no compilation, bundling, or package manager. PHP 8.0+ and MySQL/MariaDB required. Redis is optional (`useRedis => false` in `config.php`, the default).

There is **no test framework and no test suite**. The only automated check is the GitHub Actions workflow `.github/workflows/phpmd.yml`, which runs PHPMD's `codesize` ruleset on every push/PR to `master` and uploads SARIF results (non-blocking — `continue-on-error: true`). To reproduce locally:

```bash
phpmd . text codesize
```

## Installation / Setup

```bash
# Copy web root files to the document root
cp -r root_files/* /var/www/html/

# Place this admin repo as a SUBDIRECTORY of that web root
#   e.g. /var/www/html/ezcms-login/

# Configure database (lives at the web root, NOT in this repo)
nano /var/www/html/config.php

# Import latest schema
mysql -u root -p dbname < _sql/ezcms.6.0.sql
```

See `nginx.conf.sample` (or `root_files/.htaccess` for Apache) for URL rewriting and security blocks on `includes/`, `macros/`, and `filemanager/config/`.

## Architecture

### Deployment layout — admin panel is a subfolder of the web root

This is the single most important structural fact. `root_files/*` is copied to the document root; **this repo is then placed inside that same document root** (e.g. `/var/www/html/ezcms-login/`). The admin panel and the frontend therefore **share** `cms.class.php` and `config.php`, which live at the web root — one level above this repo.

That is why the require paths climb out of the repo:
- `class/ezcms.class.php` → `require_once("../cms.class.php")` (CWD is the repo root when an admin `*.php` page runs)
- `scripts/login.php` → `require_once("../../cms.class.php")`

`root_files/cms.class.php` defines `class db extends PDO` — the shared DB/Redis layer. Every admin class extends `ezCMS`, which extends `db`.

### Request flow

**Admin:** `index.php` (login form) → `scripts/login.php` (auth) → any top-level `*.php` admin page → `class/*.class.php` → `db` (PDO) → MySQL

**Frontend:** `root_files/index.php` → `getSiteData()` + `getPageData($uri)` → sets template vars (`$maincontent`, `$header`, `$sidebar`, `$siderbar`, `$footer`) → `include($page['layout'])` → HTML

### Admin page convention

Each top-level admin `*.php` page follows the same shape: `require_once` its class → `new ez<Thing>()` (the constructor handles the whole POST lifecycle) → render HTML with a left file/page tree (`$cms->treehtml`), a CodeMirror editor, and a self-posting `<form>` carrying `$cms->csrfField()`. To add a content-type screen, mirror an existing pair like `includes.php` + `class/includes.class.php`.

### Core classes (`class/`)

- `ezcms.class.php` — base class; constructor enforces login, starts session, verifies CSRF on POST, loads the logged-in user, handles editor theme/bg-color AJAX
- `pages.class.php` — page CRUD and publish/draft lifecycle (largest class)
- `layouts.class.php`, `includes.class.php`, `styles.class.php`, `scripts.class.php` — the corresponding content types
- `macros.class.php` / `macro.class.php` — macro management vs. single-macro execution
- `controller.class.php` — URL routing rules; `redirect.class.php` — 301 redirects
- `users.class.php`, `profile.class.php`, `settings.class.php`, `find.class.php` (site-wide search)

### Frontend / DB / caching (`root_files/cms.class.php`)

- **Page `id = 2` is the 404 page.** Unpublished pages return 404 to the public but render for logged-in admins.
- On a miss, `getPageDatabase()` checks the `redirects` table for a 301; otherwise logs to `log404`.
- Redis keys (when enabled): `{dbName}-site`, `{dbName}-page-{uri}`, `{dbName}-404page`. **TTLs are long, not short** — site/404 cached ~15 days (`3600*12*30`), pages ~5 days (`3600*12*10`). Editing content must invalidate these keys or changes won't appear.
- Git-style revision history is captured into the `git_pages` / `git_files` tables (see `pageRevision()` in `ezcms.class.php`).

### Macro system

Macros are standalone PHP scripts in `root_files/macros/`. `root_files/macros/macro.php` is the template/engine and documents the contract: each macro receives one content block's HTML parsed into `$html` via **PHP Simple HTML DOM** (`include/simple-html-dom.php`), mutates it, and returns the result. Blocks available: `maincontent`, `sidecontent`, `sidercontent`. Drop a new file into `root_files/macros/` to register a macro.

### CodeMirror editor

Lives in `codemirror/`. Supports PHP, HTML, CSS, JS, XML with themes (monokai, dracula, …), code folding, and diff/merge for git-style revision comparison via `js/gitFileCode.js`.

## Security conventions

- **Auth:** passwords hashed with MySQL `SHA2(?, 512)`. Legacy plaintext rows are matched (`passwd = SHA2(?,512) OR passwd = ?`) and transparently re-hashed on next successful login (`scripts/login.php`).
- **CSRF:** `csrfToken()` / `csrfField()` / `verifyCsrf()` in `ezcms.class.php`; the base constructor auto-verifies on every POST, so every state-changing form must emit `$cms->csrfField()`.
- **Headers:** `X-Frame-Options: deny` + CSP `frame-ancestors 'none'` (set in `index.php` and `include/head.php`).
- **SQL:** use PDO prepared statements for anything touching user input. Note: parts of the legacy codebase still concatenate trusted/internal IDs into `query()` strings — do not extend that pattern to user-supplied values.
- Web-server config blocks direct access to `includes/`, `macros/`, `filemanager/config/`.

## PHP style

Codebase is being modernised to PHP 8.0+ idioms:
- `[]` instead of `array()`
- short destructuring `[$a, $b] = ...` instead of `list()`
- match/arrow functions where they improve clarity
