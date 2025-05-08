# BServer Radius Server v1.0.1
1. Install Webserver Apache, PHP, PostgreSQL or MariaDB (MySQL)
2. Install FreeRadius
3. Enable sqlcounter (accessperiod & quotalimit) [MySQL](https://github.com/boingsolutions/BServer/tree/master/SQL/sqlcounter-mysql) / [PostgreSQL](https://github.com/BServer-net/BServer/tree/master/SQL/sqlcounter-postgresql)
4. Upload to Webserver
5. Import Schema.sql & Data.sql [SQL](https://github.com/boingsolutions/BServer/tree/master/SQL)

## Install 
```
- install git or composer
```
```
cd /var/www/html
git clone https://github.com/boingsolutions/BServer.git
```
or
```
cd /var/www/html
wget https://github.com/BServer-net/BServer/archive/master.zip
unzip *.zip
```
or
```
cd /var/www/html
composer require BServer/BServer
```
### Note:
```
Enable: htaccess, php-mcrypt, php-ssh2, php-gd, php-curl, php-xml, php-xsl, php-zip
```
Edit Username & Password ssh on [api/config.php](https://github.com/boingsolutions/BServer/blob/master/api/config.php)

```
- Username: admin
- Password: admin
```
Phone: +048998106262
```
