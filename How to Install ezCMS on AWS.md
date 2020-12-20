How to install ezcms on AMS EC2
-------------------------------

Sign up for amazon account and spin up an EC2 instance.
	Make a note of the instance endpoint and IP
		instance		: 	i-0ac8bcaa2262b3a8cPublic 
		DNS				: 	ec2-18-233-156-91.compute-1.amazonaws.com
		IPv4 Public IP 	:	18.233.156.91
	Configure domain or subdmain for easy access
		Add A record aws.hmi-tech.com 18.233.156.91

Allow SSH in EC2 Config
	Open Inbound ports 
		HTTP 	TCP 80 0.0.0.0/0
		HTTP 	TCP 80 ::/0
		SSH  	TCP 22 0.0.0.0/0
		HTTPS 	TCP 443 0.0.0.0/0
		HTTPS 	TCP 443 ::/0

	Key Pairs in AWS console, (Network and security)
		Add public key here

Login via SSH
	user: ubuntu
	pass: use the key

Install LAMP
	sudo apt-get upgrade
	sudo apt-get update
	sudo apt-get install apache2 php7.2 mysql-server
	sudo apt-get update

Install phpMyAdmin
	cd /var/www/html/
	wget https://files.phpmyadmin.net/phpMyAdmin/5.0.1/phpMyAdmin-5.0.1-english.tar.gz
	tar -xvzf phpMyAdmin-5.0.1-english.tar.gz 
	mv phpMyAdmin-5.0.1-english pmpa
	rm phpMyAdmin-5.0.1-english.tar.gz

Install ezCMS
	Files / Repo
		cd /var/www/html/
		rm index.html 
		git clone https://github.com/HMITECH/ezcms-login.git
		mv ezcms-login/ login
		cp -r login/root_files/. ../../
	Database
		$ mysql
		mysql> CREATE DATABASE 'dbsite';
		mysql> CREATE USER 'dbcms' IDENTIFIED BY 'TdbpFxfqdFdwXDDz';
		mysql> GRANT USAGE ON *.* TO 'dbcms'@'localhost';
	Wrap up
		Edit config.php to include the database creds
		$ nano config.php 

Install SSL
	sudo apt-get update
	sudo apt-get install software-properties-common
	sudo add-apt-repository universe
	sudo add-apt-repository ppa:certbot/certbot
	sudo apt-get update
	sudo sudo apt-get install certbot python-certbot-apache
	certbot --apache
	certbot renew --dry-run