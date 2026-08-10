# Deploying ezCMS (Nginx + PHP-FPM + MariaDB)

This guide deploys ezCMS behind **Nginx** with **PHP-FPM** and **MariaDB/MySQL**
on a Debian/Ubuntu server. Replace `example.com` and the paths below with your
own domain and layout.

> ezCMS has no build step — it's runtime PHP. "Deploying" means: lay the files
> out correctly, create the database, point a web-server vhost at it, and lock
> down the sensitive paths.

---

## 1. Layout you are aiming for

The single rule that governs everything: **the admin panel is a subdirectory of
the web root**, and the shared `config.php` + `cms.class.php` live at the web
root (one level *above* the admin panel). The admin code reaches them with
`../cms.class.php` / `../../cms.class.php`, so this layout is not optional.

```
/var/www/ezcms/                    <- web root (document root)
├── config.php                     <- DB credentials (from root_files/, edited)
├── cms.class.php                  <- shared PDO/Redis layer (from root_files/)
├── index.php                      <- front-end router
├── layout*.php, style.css, ...    <- front-end templates/assets
├── includes/  macros/  site-assets/
└── ezcms-login/                   <- ADMIN PANEL (this repo), a subfolder
    ├── index.php  pages.php  ...
    ├── class/  codemirror/  filemanager/  ...
    └── _sql/ezcms.6.0.sql
```

---

## 2. Prerequisites

```bash
nginx -v                 # nginx 1.24+
php -v                   # PHP 8.0+ (with php-fpm; socket /run/php/phpX.Y-fpm.sock)
mysql --version          # MariaDB 10.x / MySQL 8.x
```

Install anything missing with `apt install nginx php-fpm php-mysql mariadb-server`.
Redis is **optional** and off by default (`useRedis => false`).

---

## 3. Deploy the files

Copy the front-end web-root files, then drop the admin panel in as a subfolder.

```bash
SRC=/opt/ezcms/ezcms-login          # where you cloned this repo
DEST=/var/www/ezcms                 # web root

sudo mkdir -p "$DEST"
sudo cp -r "$SRC"/root_files/.  "$DEST"/     # front-end + shared config.php/cms.class.php
sudo cp -r "$SRC"             "$DEST"/ezcms-login
sudo rm -rf "$DEST"/ezcms-login/.git "$DEST"/ezcms-login/.github
```

### Permissions

PHP-FPM runs as **www-data**; give it ownership of the tree. The admin editor
writes files (pages/includes/layouts), so it needs write access:

```bash
sudo chown -R www-data:www-data /var/www/ezcms
sudo find /var/www/ezcms -type d -exec chmod 755 {} \;
sudo find /var/www/ezcms -type f -exec chmod 644 {} \;
sudo chmod 640 /var/www/ezcms/config.php     # secret: strip world-read
```

---

## 4. Create the database

The schema does **not** create the database — create it first, then import.
`_sql/ezcms.6.0.sql` is the latest schema and seeds a `site` row, four `pages`
(incl. the id=2 404 page), and one admin user.

```bash
# Create DB + a dedicated user (run as MySQL root; MariaDB uses socket auth)
sudo mysql <<'SQL'
CREATE DATABASE IF NOT EXISTS ezcms CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER IF NOT EXISTS 'ezcms'@'localhost' IDENTIFIED BY 'CHANGE_ME';
GRANT ALL PRIVILEGES ON ezcms.* TO 'ezcms'@'localhost';
FLUSH PRIVILEGES;
SQL

# Import the schema INTO that database
sudo mysql ezcms < /opt/ezcms/ezcms-login/_sql/ezcms.6.0.sql
```

**Seeded admin login:** `admin@localhost` / `ezcms`. The password is stored in
plaintext in the seed and is transparently re-hashed to `SHA2(…,512)` on first
successful login. **Change it immediately after deploying.**

---

## 5. Configure `config.php`

Edit the `config.php` that now sits at the web root:

```php
<?php
return [
    'dbHost'   => 'localhost',
    'dbUser'   => 'ezcms',
    'dbPass'   => '<the password you set above>',
    'dbName'   => 'ezcms',
    'dbTime'   => '+0:00',
    'useRedis' => false,
];
```

Sanity-check the DB connection as the web-server user before touching Nginx:

```bash
sudo -u www-data php -r '$c=include "/var/www/ezcms/config.php";
  new PDO("mysql:host={$c["dbHost"]};dbname={$c["dbName"]}",$c["dbUser"],$c["dbPass"]);
  echo "DB OK\n";'
```

---

## 6. Nginx vhost

Create `/etc/nginx/sites-available/ezcms.conf`. The front controller rewrites
unknown URLs to `index.php`; `.php` is executed by php-fpm; sensitive paths are
denied.

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name example.com;

    root  /var/www/ezcms;
    index index.php index.html;

    client_max_body_size 16M;

    access_log /var/log/nginx/ezcms-access.log;
    error_log  /var/log/nginx/ezcms-error.log;

    # --- Security: never serve these over HTTP ---
    location = /config.php    { deny all; return 403; }   # DB credentials
    location = /cms.class.php { deny all; return 403; }   # shared PDO/Redis layer
    location ^~ /includes/    { deny all; return 403; }   # front-end PHP includes
    location ^~ /macros/      { deny all; return 403; }   # macro scripts
    location ^~ /ezcms-login/filemanager/config/ { deny all; return 403; }
    location ~ /\.            { deny all; return 403; }    # dotfiles (.htaccess, .git)

    # Admin panel: serve real files, 404 instead of hitting the front-end router.
    # (.php still handled by the regex block below — do NOT use ^~ here.)
    location /ezcms-login/ { try_files $uri $uri/ =404; }

    # Front controller: rewrite everything else to the CMS router.
    location / { try_files $uri $uri/ /index.php?$query_string; }

    # PHP-FPM
    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;   # match your PHP version
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 60;
    }
}
```

Enable, test, reload — then add TLS:

```bash
sudo ln -s /etc/nginx/sites-available/ezcms.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
sudo certbot --nginx -d example.com      # obtain + wire up the Let's Encrypt cert
```

> **Alternate port / staging:** to run a second instance on a non-standard port,
> use `listen 443 ssl;` → `listen 8443 ssl;` (reusing the same certificate) and
> open that port in your firewall (`ufw allow 8443/tcp`). See `nginx.conf.sample`
> for a minimal port-80 starting point.

---

## 7. Verify

```bash
B=https://example.com
curl -sk -o /dev/null -w "home     %{http_code}\n" "$B/"
curl -sk -o /dev/null -w "route    %{http_code}\n" "$B/about"          # -> 200
curl -sk -o /dev/null -w "404 pg   %{http_code}\n" "$B/nope"           # -> 404
curl -sk -o /dev/null -w "admin    %{http_code}\n" "$B/ezcms-login/"   # -> 200
curl -sk -o /dev/null -w "config   %{http_code}\n" "$B/config.php"     # -> 403 (blocked)
```

Then browse the site (`/`), sign in at `/ezcms-login/` with the seeded
`admin@localhost` / `ezcms`, and confirm the CMS 404 page serves for unknown URLs.

---

## 8. Post-deploy checklist

- [ ] Log in and **change the admin password** (Users screen); delete/rename the
      default `admin@localhost` account.
- [ ] Review `site` settings and the seeded pages under the **Pages** menu.
- [ ] Confirm the admin editor can **save** a page (verifies write permissions).
- [ ] If enabling Redis later: set `useRedis => true` and remember cache TTLs are
      long (site/404 ~15 days, pages ~5 days) — edits invalidate keys
      `{dbName}-site` / `{dbName}-page-{uri}`.

---

## 9. Updating an existing deployment

```bash
cd /opt/ezcms/ezcms-login && git pull
# Re-sync front-end + admin without clobbering config.php:
sudo rsync -a --exclude config.php root_files/.  /var/www/ezcms/
sudo rsync -a --exclude .git --exclude .github . /var/www/ezcms/ezcms-login/
# Apply any new _sql/upgrade*.sql between your version and the latest, then:
sudo systemctl reload php8.3-fpm
```

## 10. Teardown

```bash
sudo rm /etc/nginx/sites-enabled/ezcms.conf
sudo systemctl reload nginx
sudo mysql -e "DROP DATABASE ezcms; DROP USER 'ezcms'@'localhost';"
sudo rm -rf /var/www/ezcms
```

---

## Quick reference

| Item           | Value                                          |
|----------------|------------------------------------------------|
| URL            | https://example.com                            |
| Admin          | https://example.com/ezcms-login/               |
| Web root       | `/var/www/ezcms`                               |
| Nginx vhost    | `/etc/nginx/sites-available/ezcms.conf`        |
| Database       | `ezcms` (user `ezcms`@localhost)               |
| DB credentials | `/var/www/ezcms/config.php`                    |
| PHP-FPM socket | `/run/php/php8.3-fpm.sock`                      |
| Seeded admin   | `admin@localhost` / `ezcms` (change on first login) |
| Logs           | `/var/log/nginx/ezcms-{access,error}.log`      |
