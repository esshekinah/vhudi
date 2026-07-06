FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip locate ghostscript ssmtp \
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

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd gettext xml intl zip mysqli \
    && docker-php-ext-enable mbstring exif pcntl bcmath gd gettext xml intl zip mysqli

# Composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && find /var/www -type d -exec chmod 755 {} \; \
    && find /var/www -type f -exec chmod 644 {} \;

# Ensure files are readable by php-fpm
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Set working directory
WORKDIR /var/www

# Run as root for simplicity (avoids permission issues)
USER root
