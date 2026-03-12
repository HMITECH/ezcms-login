# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

ezCMS is a developer-focused PHP CMS that gives direct code access via an integrated CodeMirror editor. The admin panel lives in this repo (`ezcms-login/`); the public-facing website lives at the web root (populated from `root_files/`).

## No Build System

This is a runtime PHP project — no compilation, bundling, or package manager. PHP 8.0+ and MySQL/MariaDB are required. Redis is optional (disable in `config.php` to start).

## Installation / Setup

```bash
# Clone and copy web root files
cp -r root_files/* /var/www/html/

# Configure database
nano /var/www/html/config.php

# Import latest schema
mysql -u root -p dbname < _sql/ezcms.6.0.sql
```

See `nginx.conf.sample` for web server configuration (URL rewriting, security blocks for `/includes/`, `/macros/`, `/filemanager/config/`).

## Architecture

### Two-tier layout

| Directory | Role |
|-----------|------|
| `ezcms-login/` (this repo) | CMS admin panel, served from a non-public path |
| `root_files/` | Web root files copied to the site's document root |

### Request flow

**Admin:** `index.php` (login) → `scripts/login.php` (auth) → any `*.php` admin page → `class/*.class.php` → `root_files/cms.class.php` (PDO layer) → MySQL

**Frontend:** `root_files/index.php` → URL lookup in DB → assemble layout + includes + blocks → optional Redis cache → output HTML

### Core classes (`class/`)

- `ezcms.class.php` — base class; all others extend or use it
- `pages.class.php` — page CRUD and publish/draft lifecycle
- `layouts.class.php`, `includes.class.php`, `styles.class.php`, `scripts.class.php` — manage the corresponding content types
- `macros.class.php` / `macro.class.php` — batch content processing
- `controller.class.php` — URL routing rules
- `users.class.php` — admin user management and session handling
- `find.class.php` — site-wide search

### Database / caching

- PDO abstraction in `root_files/cms.class.php` (extends PDO directly)
- Redis keys: `{dbName}-site`, `{dbName}-page-{uri}` with ~6-hour TTL
- Git-style revision history stored in `git_pages` and `git_files` tables

### Macro system

Macros are standalone PHP scripts in `root_files/macros/`. Each receives page content, transforms it, and returns the result. The execution engine is `root_files/macros/macro.php`. New macros just need to be dropped into that directory.

### CodeMirror editor

Lives in `codemirror/`. Supports PHP, HTML, CSS, JS, XML with themes (monokai, dracula, etc.), code folding, diff/merge, and git-style revision comparison via `js/gitFileCode.js`.

## Security conventions

- CSRF tokens required on all state-changing forms
- Passwords hashed with SHA2(512)
- `X-Frame-Options: deny` and CSP headers set in `include/head.php`
- PDO prepared statements throughout — do not concatenate user input into queries
- Nginx config blocks direct access to `includes/`, `macros/`, `filemanager/config/`

## PHP style

Recent codebase modernisation targets PHP 8.0+ idioms:
- Use `[]` instead of `array()`
- Use short array destructuring `[$a, $b] = ...` instead of `list()`
- Match/arrow functions where they improve clarity
