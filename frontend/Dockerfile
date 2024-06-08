FROM yiisoftware/yii2-php:8.3-apache

COPY adminfin-vhost.conf /etc/apache2/sites-available/000-default.conf

# SSL
RUN mkdir -p /etc/apache2/cert-ssl
RUN a2enmod ssl && \
    openssl req -subj '/CN=adminfin.test/O=adminFin/C=ES' -new -newkey rsa:2048 -days 365 -nodes -x509 -keyout /etc/apache2/cert-ssl/server.key -out /etc/apache2/cert-ssl/server.pem

EXPOSE 80
EXPOSE 443