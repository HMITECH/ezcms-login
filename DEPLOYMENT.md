# Deploying ezCMS (Nginx + PHP-FPM + MariaDB)

This guide walks through deploying ezCMS behind **Nginx** with **PHP-FPM** and
**MariaDB/MySQL**. The worked example is the live staging site at
**https://example.com:5550**, deployed on Ubuntu 24.04. Adapt the
domain, port, and paths for your own environment.

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
/var/www/ezcms/        <- web root (document root)
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
# Ubuntu 24.04 example — the staging host already had these:
nginx -v                 # nginx/1.24.0
php -v                   # PHP 8.3 (with php-fpm8.3, socket /run/php/php8.3-fpm.sock)
mysql --version          # MariaDB 10.11
```

Install anything missing with `apt install nginx php-fpm php-mysql mariadb-server`.
Redis is **optional** and off by default (`useRedis => false`).

---

## 3. Deploy the files

Copy the front-end web-root files, then drop the admin panel in as a subfolder.

```bash
SRC=/opt/ezcms/ezcms-login        # this repo
DEST=/var/www/ezcms           # web root

mkdir -p "$DEST"
cp -r "$SRC"/root_files/.  "$DEST"/        # front-end + shared config.php/cms.class.php
cp -r "$SRC"             "$DEST"/ezcms-login
rm -rf "$DEST"/ezcms-login/.git "$DEST"/ezcms-login/.github
```

### Permissions

PHP-FPM runs as **www-data**. On this host `www-data` is a member of the
`deploy` group, and `/var/www` is group-readable (`drwxr-x---`), so php-fpm
can already traverse and read the tree via group membership. The admin editor
also *writes* files (pages/includes/layouts), so keep directories
group-writable and `setgid` (new files stay in the `deploy` group):

```bash
cd "$DEST"
find . -type d -exec chmod 2775 {} \;     # rwxrwsr-x — group-writable + setgid
find . -type f -exec chmod 664  {} \;     # rw-rw-r--
chmod 640 config.php                        # secret: strip world-read
```

> If php-fpm on your host runs as a user with **no** access to `/home`, deploy
> under `/var/www/` instead, or add an ACL: `setfacl -m u:www-data:rx <path>`.

---

## 4. Create the database

The schema does **not** create the database — create it first, then import.
`_sql/ezcms.6.0.sql` is the latest schema and seeds a `site` row, four `pages`
(incl. the id=2 404 page), and one admin user.

```bash
# Create DB + a dedicated user (run as MySQL root; MariaDB uses socket auth)
sudo mysql <<'SQL'
CREATE DATABASE IF NOT EXISTS ezcms_staging CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER IF NOT EXISTS 'ezcms_staging'@'localhost' IDENTIFIED BY 'CHANGE_ME';
GRANT ALL PRIVILEGES ON ezcms_staging.* TO 'ezcms_staging'@'localhost';
FLUSH PRIVILEGES;
SQL

# Import the schema INTO that database
sudo mysql ezcms_staging < /opt/ezcms/ezcms-login/_sql/ezcms.6.0.sql
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
    'dbUser'   => 'ezcms_staging',
    'dbPass'   => '<the password you set above>',
    'dbName'   => 'ezcms_staging',
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

Create `/etc/nginx/sites-available/ezcms-staging.conf`. This is the exact config
serving the staging site — port **5550**, reusing the domain's existing Let's
Encrypt certificate. The front controller rewrites unknown URLs to `index.php`;
`.php` is executed by php-fpm; sensitive paths are denied.

```nginx
server {
    listen 5550 ssl;
    listen [::]:5550 ssl;
    server_name example.com;

    root  /var/www/ezcms;
    index index.php index.html;

    client_max_body_size 16M;

    ssl_certificate     /etc/letsencrypt/live/example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;
    include             /etc/letsencrypt/options-ssl-nginx.conf;
    ssl_dhparam         /etc/letsencrypt/ssl-dhparams.pem;

    access_log /var/log/nginx/ezcms-staging-access.log;
    error_log  /var/log/nginx/ezcms-staging-error.log;

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

Enable, test, open the firewall port, reload:

```bash
sudo ln -s /etc/nginx/sites-available/ezcms-staging.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo ufw allow 5550/tcp        # only if ufw is active and using a non-standard port
sudo systemctl reload nginx
```

> **Standard (port 80/443) deploys:** drop the `listen 5550 ssl` lines and the
> non-standard-port firewall rule; use `certbot --nginx -d your-domain` to
> obtain and wire up the certificate. See `nginx.conf.sample` for a plain
> port-80 starting point.

---

## 7. Verify

```bash
B=https://example.com:5550
curl -sk -o /dev/null -w "home     %{http_code}\n" "$B/"
curl -sk -o /dev/null -w "route    %{http_code}\n" "$B/about"          # -> 200
curl -sk -o /dev/null -w "404 pg   %{http_code}\n" "$B/nope"           # -> 404
curl -sk -o /dev/null -w "admin    %{http_code}\n" "$B/ezcms-login/"   # -> 200
curl -sk -o /dev/null -w "config   %{http_code}\n" "$B/config.php"     # -> 403 (blocked)
```

### Front-end home page
The seeded home page renders with the default header / two asides / footer:

![ezCMS front-end home page](docs/deployment/01-home.png)

### Admin login
`/ezcms-login/` — sign in with the seeded `admin@localhost` / `ezcms`:

![ezCMS admin login](docs/deployment/02-admin-login.png)

### 404 handling
Unknown URLs serve the CMS 404 page (page id=2) with an HTTP 404 status:

![ezCMS 404 page](docs/deployment/03-404.png)

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
rsync -a --exclude config.php root_files/.  /var/www/ezcms/
rsync -a --exclude .git --exclude .github . /var/www/ezcms/ezcms-login/
# Apply any new _sql/upgrade*.sql between your version and the latest, then:
sudo systemctl reload php8.3-fpm
```

## 10. Teardown (staging)

```bash
sudo rm /etc/nginx/sites-enabled/ezcms-staging.conf
sudo systemctl reload nginx
sudo ufw delete allow 5550/tcp
sudo mysql -e "DROP DATABASE ezcms_staging; DROP USER 'ezcms_staging'@'localhost';"
rm -rf /var/www/ezcms
```

---

## Staging quick reference

| Item             | Value                                             |
|------------------|---------------------------------------------------|
| URL              | https://example.com:5550               |
| Admin            | https://example.com:5550/ezcms-login/  |
| Web root         | `/var/www/ezcms`                      |
| Nginx vhost      | `/etc/nginx/sites-available/ezcms-staging.conf`   |
| Database         | `ezcms_staging` (user `ezcms_staging`@localhost)  |
| DB credentials   | `/var/www/ezcms/config.php`           |
| PHP-FPM socket   | `/run/php/php8.3-fpm.sock`                         |
| Seeded admin     | `admin@localhost` / `ezcms` (change on first login) |
| Logs             | `/var/log/nginx/ezcms-staging-{access,error}.log` |
