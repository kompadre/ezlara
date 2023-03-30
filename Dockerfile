FROM php:8.2-fpm

RUN apt-get update && apt-get install git unzip libonig-dev libcurl4-openssl-dev -y --no-install-recommends

RUN docker-php-ext-install pdo pdo_mysql mbstring curl mysqli

ARG USER_ID
ARG GROUP_ID

RUN userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -d /app -g www-data www-data

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN mkdir -p /app/vendor

COPY . /app

RUN chown www-data /app /app/vendor
RUN chown -R www-data /app/storage
RUN chown -R www-data /app/bootstrap/cache

WORKDIR /app

USER www-data

RUN composer update --no-cache

ENTRYPOINT [ "/app/entrypoint.sh" ]
