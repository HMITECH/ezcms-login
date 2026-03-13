# How to Install ezCMS

General installation guide for any Linux server or cPanel environment.

---

## Prerequisites

- PHP 8.0+
- MySQL / MariaDB
- Nginx or Apache
- Git
- Redis (optional)

---

## Steps

### 1. Clone the repository

Log in via SSH or open the cPanel terminal, then run:

```bash
git clone https://github.com/HMITECH/ezcms-login.git
```

### 2. Rename the folder (recommended)

Renaming reduces exposure of the admin path:

```bash
mv ezcms-login login
```

### 3. Copy web root files

Copy everything from `root_files/` to your site's document root:

```bash
cp -r login/root_files/* /var/www/html/
```

Adjust `/var/www/html/` to match your actual web root (e.g. `public_html/` on cPanel).

### 4. Configure the database

Edit `config.php` in the web root and fill in your database credentials:

```bash
nano /var/www/html/config.php
```

Set `use_redis` to `false` to start. You can enable it later once Redis is available.

### 5. Import the database schema

Use the latest SQL file from the `_sql/` directory:

```bash
mysql -u dbuser -p'yourpassword' dbname < login/_sql/ezcms.6.0.sql
```

### 6. Configure your web server

Copy and adapt the sample Nginx config:

```bash
cp login/nginx.conf.sample /etc/nginx/sites-available/yoursite.conf
# edit as needed, then enable and reload
ln -s /etc/nginx/sites-available/yoursite.conf /etc/nginx/sites-enabled/
nginx -t && systemctl reload nginx
```

The Nginx config includes security blocks for `/includes/`, `/macros/`, and `/filemanager/config/`.

---

## Done

Visit your domain to confirm the site loads. Log in at `yourdomain.com/login/` (or whatever you renamed the folder to).

---

## Notes

- The admin panel lives at the renamed `login/` path — keep this out of the web root.
- Redis caching keys follow the pattern `{dbName}-page-{uri}` with ~6-hour TTL.
- Revision history is stored in the `git_pages` and `git_files` tables.
