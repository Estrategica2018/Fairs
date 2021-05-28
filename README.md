# AWS Install

Step 1. Connecting instance
ssh -i  ferias_virtuales_ssh_aws.pem ubuntu@3.22.131.214


-PHP install 
https://websiteforstudents.com/installing-phpmyadmin-apache2-mysql-php-ubuntu-17-04-17-10/



COMPOSER INSTALL: https://www.digitalocean.com/community/tutorials/como-instalar-y-utilizar-composer-en-ubuntu-18-04-es
Step 1.1: Install Composer
sudo apt update
sudo apt install curl php-cli php-mbstring git unzip

cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php

https://composer.github.io/pubkeys.html
HASH=756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3


php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer


https://phoenixnap.com/kb/how-to-install-phpmyadmin-on-debian-10
Step 1.2: Install Apache


sudo apt install apache2 -y
systemctl status apache2



Home » SysAdmin » How to Install phpMyAdmin on Debian 10 (Buster)

Introduction

The phpMyAdmin utility is a graphical database management tool. By installing phpMyAdmin, you no longer need to use a default command-line interface to manage your databases.

PhpMyAdmin is a web-based application and requires the LAMP stack to work properly. This guide shows you how to lay the groundwork and install phpMyAdmin on Debian 10.

tutorial on how to Install and Secure phpMyAdmin on Debian 10
Prerequisites

Debian 10 (Buster) installed and running
A user account with sudo or root privileges
Access to a terminal window/command line
Step 1: Install LAMP Stack on Debian 10
You need a functioning web server for phpMyAdmin to work properly. This section shows you how to install the supporting software to turn your Debian 10 system into a web server.

If you already have a LAMP stack installed, you can skip directly to the Download phpMyAdmin section.

Step 1.1: Update Software Packages and Install wget
Access your terminal window, and update your software package lists using the following command:

sudo apt update
Installing outdated software packages is a severe security liability. Do not skip this step.

The wget utility allows you to download files directly from the terminal window. Enter the following command to install the wget tool:

sudo apt install wget -y
You now have the tools you need to install a LAMP stack and phpMyAdmin.

Step 1.2: Install Apache
Apache is the webserver software that processes requests and transmits data over an HTTP network. Open a terminal window, and install Apache by entering the following command:

sudo apt install apache2 -y
The process can take a few moments to complete. Enter the following command to make sure the Apache service is running:

systemctl status apache2
In the report that follows, you should see a green status that says active (running).

Status of Apache web-server on Debian 10.
Press Ctrl+z to return to the command prompt.

Step 1.3: Install PHP on Debian 10
The PHP programming language and coding environment is essential for running a web application like phpMyAdmin. Install core PHP packages and Apache and MySQL plugins with the following command:

sudo apt install php php-cgi php-mysqli php-pear php-mbstring php-gettext libapache2-mod-php php-common php-phpseclib php-mysql -y
Once the installation process is complete, verify that PHP has been installed:

php --version
The system displays the current version of PHP, along with the date of the release.

The version of PHP currently intalled on Debian 10 system.
Step 1.4: Install and Set Up MariaDB on Debian 10
This guide uses the MariaDB open-source relational database management system instead of MySQL. MariaDB and MySQL are compatible, and many of the commands and features are identical.

To install MariaDB, enter the following command into your terminal:

sudo apt install mariadb-server mariadb-client -y
Once the process is complete, verify the MariaDB installation with the following command:

systemctl status mariadb
Like with Apache, you see an active (running) status.


Secure MariaDB
sudo apt install mariadb-server mariadb-client -y
systemctl status mariadb
sudo mysql_secure_installation


Step 2: Download phpMyAdmin
wget -P Downloads https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.tar.gz







------------------
https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04-es
sudo mysql
CREATE USER user_fair_db IDENTIFIED BY 'user_fair_db_2021';


DB_DATABASE=fair
DB_USERNAME=user_fair_db
DB_PASSWORD=user_fair_db_2021

CREATE DATABASE fair;

GRANT ALL PRIVILEGES ON fair.* TO user_fair_db;



https://stackoverflow.com/questions/43408604/php7-install-ext-dom-issue
