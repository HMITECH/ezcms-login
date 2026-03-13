# ezCMS

ezCMS is a developer-focused content management system that gives you direct access to your site's PHP, HTML, CSS, and JavaScript code through an integrated CodeMirror editor — no abstraction layers, no visual builders.

**Version 7**

---

## Features

- In-browser code editor (CodeMirror) with syntax highlighting for PHP, HTML, CSS, JS, and XML
- Theme support: Monokai, Dracula, and more
- Page, layout, include, stylesheet, and script management
- Git-style revision history for pages and files
- Macro system for batch content processing
- URL routing / controller rules
- Redis page caching (optional)
- CSRF protection, PDO prepared statements throughout

---

## Requirements

- PHP 8.0+
- MySQL / MariaDB
- Nginx (recommended) or Apache
- Redis (optional)

---

## Installation

See the dedicated guides:

- [How to install ezCMS.md](How%20to%20install%20ezCMS.md) — general install on any Linux/cPanel server
- [How to Install ezCMS on AWS.md](How%20to%20Install%20ezCMS%20on%20AWS.md) — step-by-step for AWS EC2 (Ubuntu + Nginx)

---

## Quick Start

```bash
# Clone the admin panel
git clone https://github.com/HMITECH/ezcms-login.git

# Copy web root files
cp -r ezcms-login/root_files/* /var/www/html/

# Import the database schema (use latest)
mysql -u root -p dbname < ezcms-login/_sql/ezcms.6.0.sql

# Configure database credentials and Redis
nano /var/www/html/config.php
```

---

## Updating

```bash
cd /path/to/ezcms-login
git pull
```

Check `_sql/` for any upgrade scripts between your current version and the latest.

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
