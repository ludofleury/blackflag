FROM nginx
COPY ./conf.d/php-symfony.conf /etc/nginx/conf.d/default.conf
RUN mkdir -p /var/app/public && touch /var/app/public/index.php