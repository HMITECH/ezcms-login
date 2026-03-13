# How to Install ezCMS on AWS EC2

Step-by-step guide for deploying ezCMS on an Ubuntu EC2 instance with Nginx, PHP 8, MySQL, and SSL.

---

## 1. Launch an EC2 Instance

1. Log in to the AWS Console and launch a new EC2 instance (Ubuntu 22.04 LTS recommended).
2. Note your instance details:
   - Instance ID
   - Public DNS (e.g. `ec2-18-233-156-91.compute-1.amazonaws.com`)
   - Public IPv4 address

3. Point a domain or subdomain at the instance:
   - Add an **A record**: `yoursite.com` → `<Public IPv4>`

---

## 2. Configure Security Group (Inbound Rules)

Open the following ports in your instance's Security Group:

| Type  | Protocol | Port | Source          |
|-------|----------|------|-----------------|
| SSH   | TCP      | 22   | Your IP (or 0.0.0.0/0) |
| HTTP  | TCP      | 80   | 0.0.0.0/0, ::/0 |
| HTTPS | TCP      | 443  | 0.0.0.0/0, ::/0 |

Upload your public key under **Key Pairs** (Network & Security) in the EC2 console.

---

## 3. Connect via SSH

```bash
ssh -i your-key.pem ubuntu@<Public IPv4>
```

---

## 4. Install Nginx, PHP 8, and MySQL

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-cli php8.2-curl php8.2-mbstring php8.2-xml
```

Start and enable services:

```bash
sudo systemctl enable --now nginx mysql php8.2-fpm
```

### Secure MySQL

```bash
sudo mysql_secure_installation
```

---

## 5. Create the Database and User

```bash
sudo mysql
```

```sql
CREATE DATABASE dbsite;
CREATE USER 'dbcms'@'localhost' IDENTIFIED BY 'StrongPasswordHere';
GRANT ALL PRIVILEGES ON dbsite.* TO 'dbcms'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 6. Install ezCMS

```bash
cd /var/www/html
sudo rm -f index.nginx-debian.html

# Clone the admin panel
sudo git clone https://github.com/HMITECH/ezcms-login.git login

# Copy web root files
sudo cp -r login/root_files/. ./

# Set permissions
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
```

### Import the database schema

```bash
mysql -u dbcms -p'StrongPasswordHere' dbsite < login/_sql/ezcms.6.0.sql
```

### Configure the application

```bash
sudo nano /var/www/html/config.php
```

Fill in your database credentials. Set `use_redis` to `false` initially.

---

## 7. Configure Nginx

```bash
sudo cp login/nginx.conf.sample /etc/nginx/sites-available/yoursite.conf
sudo nano /etc/nginx/sites-available/yoursite.conf
```

Update `server_name` to match your domain. Then enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/yoursite.conf /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
```

---

## 8. Install phpMyAdmin (optional)

```bash
cd /var/www/html
sudo apt install -y phpmyadmin
```

Or download manually from [phpmyadmin.net](https://www.phpmyadmin.net/downloads/) and place it in a directory of your choosing.

---

## 9. Install SSL with Let's Encrypt

```bash
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot
sudo certbot --nginx -d yoursite.com -d www.yoursite.com
```

Test auto-renewal:

```bash
sudo certbot renew --dry-run
```

---

## Done

Your site should now be live at `https://yoursite.com`. The ezCMS admin panel is accessible at `https://yoursite.com/login/`.

---

## Notes

- Keep the `login/` directory outside the web root if possible, or restrict it in Nginx with an `auth_basic` block.
- Enable Redis later for page caching by installing `redis-server` and updating `config.php`.
- Pull updates anytime with `cd /var/www/html/login && sudo git pull`.
