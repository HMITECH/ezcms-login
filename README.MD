# ezCMS UPDATE REPO README #

This project has been decapited.
Use ezcms-login repo now
https://github.com/HMITECH/ezcms-login



### What is this repository for? ###

* Quick summary
* ezCMS Version 5.0 UPDATE 
* Clone and pull this repo to update ezCM.

### How do I get set up? ###

* See How to install ezCMS.md
* See How to install ezCMS on AWS.md

### How to install ezCMS
### --------------------

1) In cpanel run terminal or SSH terminal: ( or ssh using any agent)
Login to SSH and run this command 
	"git clone https://github.com/HMITECH/ezcms-login.git"

2) Rename the ezcms-login folder for added security (optional)
	$ mv ezcms-login cms-login

3) Copy everything from root_files to website root
	cp -r ezcms-login/root_files/* ./

4) Edit config.php in the web root folder and enter your database credentials.
Set use redis to false to start with and enable if you have redis db0 free later
	$nano config.php

5) Import the database sql file (use the latest version)
Currently it is ezcms.5.5.sql
	$ mysql  -u -p'password' dbname < ezcms-login/_sql/ezcms.x.x.sql


#### Using the CLI ####

* CLone this repo inside the root of your ezCMS.
* Execute git pull to get the latest code.
* Refer to the release notes for any changes to the database.

### Contributors ###

* Mohd Ahmed
* Mosh Ahmed
* Avinash Jagwani
* Kavya NM
* Firdaus Nagree

### Who do I talk to? ###

* Repo owner or send email to support @ hmi-tech.net
* Other community or team contact
