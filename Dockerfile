FROM php:8.2-fpm

# Install nginx, supervisor, and system dependencies
RUN apt-get update && apt-get install -y --fix-missing \
    nginx git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    libfreetype6-dev libjpeg62-turbo-dev libicu-dev \
    zip unzip ghostscript ssmtp supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Disable OPCache
RUN echo "opcache.enable=0" > /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.enable_cli=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Setup mail
RUN echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf \
    && echo 'sendmail_path = "/usr/sbin/ssmtp -t"' > /usr/local/etc/php/conf.d/mail.ini

# Increase PHP upload limits
RUN echo "upload_max_filesize = 200M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 200M" >> /usr/local/etc/php/php.ini

# Install PHP extensions (gd requires explicit flags on PHP 8.x)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd gettext xml intl zip mysqli \
    && docker-php-ext-enable mbstring exif pcntl bcmath gd gettext xml intl zip mysqli

# Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Remove default nginx config and install ours
RUN rm -f /etc/nginx/sites-enabled/default
COPY ./docker-compose/nginx/default.conf.cloudrun /etc/nginx/conf.d/default.conf

# Supervisor config to run nginx + php-fpm together
COPY ./supervisor.conf /etc/supervisor/conf.d/supervisord.conf

# Logging dirs
RUN mkdir -p /var/log/nginx /var/log/supervisor

WORKDIR /var/www/html

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
