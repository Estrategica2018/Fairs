# AWS Install
Instancia para proyecto Laravel AWS
sudo apt-get install apache2
sudo apt-get install php7.3
sudo service apache2 restart
—————
sudo apt-get install libapache2-mod-php
sudo apt-get install php7.3-xml
sudo apt-get install unzip
sudo apt-get install php7.3-zip
sudo apt-get install php7.3-mysql
sudo apt-get install php7.3-gd
sudo apt-get install php7.3-bcmath
sudo apt-get install php7.3-mbstring
sudo apt-get install php7.3-curl
——******* Presento error al descargar los siguientes comandos y se soluciono con esa linea de código
add-apt-repository ppa:ondrej/php
apt update
——*******
sudo a2enmod rewrite

sudo apt-get install php7.3-bcmath
sudo apt-get install php7.3-mbstring

sudo apt-get install php7.3-curl
sudo service apache2 restart
sudo service apache2 status


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

#Agregar subdominios 

docker ps -a -> ingresar al docker
docker exec -it b8971a90c69b /bin/sh -> ingresar al contenedor - depende del tipo del contenedor (bin/sh o bin/bash)
Cd /etc/nginx/conf.d/ -> ir al .conf
Crear un copia del .conf , agregar el nombre del subdominio y cambio de nombre de docker
certbot --nginx
Ejecutar la renovación para todos los dominos agregados
