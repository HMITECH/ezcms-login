# ezCMS

**A tiny, developer-first CMS that gets out of your way.**

ezCMS gives you direct access to your site's PHP, HTML, CSS, and JavaScript
through an in-browser [CodeMirror](https://codemirror.net/) editor — no
abstraction layers, no visual page builders, no framework lock-in. You write
the code; ezCMS just stores it, versions it, and serves it.

**Version 7**

---

## Why ezCMS

- **Tiny.** The whole thing is a handful of PHP files and a small database
  schema. There's no bundler, no package manager, no build step, and next to
  no runtime overhead.

- **The CMS isn't needed to run your site — only to edit it.** The public
  site is served by a thin front-end (`root_files/`): a small router
  (`index.php`) that looks up the requested URL, assembles the page, and
  `include`s a layout. That's it. The admin panel (`ezcms-login/`, this repo)
  is a *separate* application used to author content. You can lock it down,
  move it off the public path, or take it offline entirely and your site keeps
  running exactly the same.

- **No framework, no methodology forced on you.** ezCMS has zero opinions about
  how you build. Write vanilla PHP/HTML/JS, or bring React, Vue, Alpine,
  htmx, Tailwind, Bootstrap — whatever you like. Pages, layouts, includes,
  stylesheets and scripts are just code you edit; you decide the architecture.

- **Real projects, not just brochure sites.** Because it's plain code with a
  database and PHP behind it, people have built PWAs, dashboards, and other
  genuinely complex apps on ezCMS — not only static content pages.

- **Direct code access with a safety net.** Git-style revision history for
  every page and file, CSRF protection, PDO prepared statements, and optional
  Redis caching come built in.

---

## How it works

```
Web root (document root)                 ← the live site (runtime)
├── index.php            front-end router: URL → page → layout → HTML
├── cms.class.php        shared PDO/Redis data layer
├── config.php           DB credentials
├── layout*.php          your layouts
├── includes/  macros/  site-assets/
└── ezcms-login/         ← the admin panel (this repo): the editor
```

- **Front-end (runtime):** `index.php` resolves the request URI to a page in
  the database, pulls in the site/page content blocks, and includes the chosen
  layout file. Unpublished pages 404 for the public but preview for logged-in
  admins; unknown URLs fall through to 301 redirects or a CMS 404 page.
- **Admin (editor):** a login-gated panel that edits pages, layouts, includes,
  CSS, JS and macros in the CodeMirror editor, with a file/page tree, search,
  revisions, and a diff viewer.

---

## Features

- In-browser code editor (CodeMirror) with syntax highlighting for PHP, HTML,
  CSS, JS, and XML, plus themes (Monokai, Dracula, and many more)
- Page, layout, include, stylesheet, and script management
- Git-style revision history and diff/merge for pages and files
- Macro system for batch content processing (PHP Simple HTML DOM)
- URL routing / controller rules and 404 → 301 redirect management
- Optional Redis page caching
- CSRF protection and PDO prepared statements throughout

---

## Requirements

- PHP 8.0+
- MySQL / MariaDB
- Nginx (recommended) or Apache
- Redis (optional)

No Node, no Composer, no build tools required.

---

## Installation

See the dedicated guides:

- [DEPLOYMENT.md](DEPLOYMENT.md) — Nginx + PHP-FPM + MariaDB, step by step
- [How to install ezCMS.md](How%20to%20install%20ezCMS.md) — general install on any Linux/cPanel server
- [How to Install ezCMS on AWS.md](How%20to%20Install%20ezCMS%20on%20AWS.md) — AWS EC2 (Ubuntu + Nginx)

### Quick start

```bash
# Clone this repo (the admin panel)
git clone https://github.com/HMITECH/ezcms-login.git

# Copy the front-end files to your web root...
cp -r ezcms-login/root_files/* /var/www/html/
# ...and place the admin panel inside that same web root
cp -r ezcms-login /var/www/html/ezcms-login

# Import the latest database schema
mysql -u root -p dbname < ezcms-login/_sql/ezcms.7.0.sql

# Configure database credentials (and Redis, if used)
nano /var/www/html/config.php
```

The seeded admin login is `admin@localhost` / `ezcms` — **change it on first
login.**

---

## Updating

```bash
cd /path/to/ezcms-login
git pull
```

Check `_sql/` for any upgrade scripts between your current version and the latest.

---

## Roadmap

- **MCP server for ezCMS** — an [Model Context Protocol](https://modelcontextprotocol.io/)
  interface so AI agents can browse, edit, and manage a site's pages, layouts
  and code directly inside ezCMS, the same way a developer does in the editor.

---

## Contributors

- Mohd Ahmed
- Mosh Ahmed
- Avinash Jagwani
- Kavya NM
- Firdaus Nagree

---

## Support

Open an issue or email **support@hmi-tech.net**
