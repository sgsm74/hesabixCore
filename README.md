# **hesabix**

Hesabix is first open source accounting software in persian language with web interface.

for see full project Demo see main website at [https://hesabix.ir](https://hesabix.ir)

## Before Installation

For install hesabixCore you need this tools

* Web server : Apache,NginX,...
* Database: Mysql, mariaDB,SqlServer,....
* PHP: php : +8.1
* php extentions: php-Intl, php-mbstring, php-http, php-raphf
* composer

## Installation

* Copy or clone project in web server directory . if you use shared hosting panels like cpanel or directadmin copy files in root directory and public_html folder will be rewrited.
* create database in your DBMS and edit .env file in root of project
* Install dependencies with run this command

```
composer install
```

* edit .env file and set database connection string with your username and password and name of database
* create local env file with run this command

```
composer dump-env prod
```

* login to your database managment like phpmyadmin and import file located in hesabixBackup/databaseFiles/hesabix-db-default.sql
* go to hesabixCore folder in cli and update database with this command

```
php bin/console doctrine:schema:update --force --complete
```

open root domain address in browser you should see hesabix api main page.

## Connect to email service

For connect hesabix to your email service edit .env.local.php file located in hesabixCore folder and set your email server connection string in MAILER_DSN parameter. for more information about connection strings see symfony mailer documents.  [Click Here](https://symfony.com/doc/current/mailer.html#transport-setup)

after set connection string edit mailer.yaml located in configs folder and set header for send emails.

## Donation

for help developers please use this link
[https://zarinp.al/hesabix.ir](https://zarinp.al/hesabix.ir)
