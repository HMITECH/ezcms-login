How to install ezCMS
--------------------

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
	$ mysql  -u -p'password' dbname < ezcms-login/_sql/ ezcms.5.5.sql

DONE !