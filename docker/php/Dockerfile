FROM php:7.4-fpm
ARG LOCAL_USER

RUN apt-get update \
&& apt-get install -y libonig-dev libicu-dev libzip-dev libpq-dev \
&& docker-php-ext-install pdo_mysql zip \
&& pecl install apcu \
&& docker-php-ext-install zip pdo pdo_pgsql mbstring \
&& docker-php-ext-enable apcu \
&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer \
&& rm -rf /var/lib/apt/lists/*

WORKDIR /usr/src/app

COPY --chown=${LOCAL_USER} . /usr/src/app

RUN PATH=$PATH:/usr/src/vendor/bin:bin
