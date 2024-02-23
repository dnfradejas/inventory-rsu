FROM ubuntu:20.04

RUN apt-get -y --fix-missing update && \
# Install apache, PHP, and supplimentary programs, openssh-server, curl, and lynx-cur
apt-get -y install software-properties-common && \
LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php && \
apt-get -y update && apt-cache pkgnames | grep php7.4 && apt-get -y update && apt-get -y install nginx php7.4 php7.4-fpm \ 
&& apt-get -y update && apt-get -y install php7.4-cli php7.4-gd php7.4-intl php7.4-common php7.4-mysql php7.4-curl curl \
php7.4-dom zip unzip php7.4-xml \
php7.4-zip php7.4-mbstring \
php7.4-json php7.4-opcache php7.4-dev php7.4-sqlite git vim iputils-ping \
&& apt-cache search php7.4 && apt-get -y update && \
curl -sS https://getcomposer.org/installer -o composer-setup.php && \
curl -s https://getcomposer.org/installer | php && \
mv composer.phar /usr/local/bin/composer && \
apt-get install sqlite3 libsqlite3-dev

RUN rm /etc/nginx/sites-enabled/default
RUN rm /etc/nginx/sites-available/default
# RUN ln -s /etc/nginx/sites-available/vhost-nginx.conf /etc/nginx/sites-enabled/vhost
# Set php version to use
RUN update-alternatives --set php /usr/bin/php7.4


WORKDIR /var/www/web


EXPOSE 80
EXPOSE 3000
EXPOSE 443

CMD service php7.4-fpm start && nginx -g "daemon off;"