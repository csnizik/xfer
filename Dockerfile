# Inherit from the official Drupal 9 PHP 7.4 image.
FROM drupal:9-php7.4-apache

# Install Git (required by Composer), and mariadb-client (so Drush can drop
# the database during site-install).
RUN apt-get update && apt-get install -y git mariadb-client

# Set OPcache's revalidation frequency to 0 seconds for development.
# See https://www.php.net/manual/en/opcache.configuration.php#ini.opcache.revalidate-freq
RUN sed -i 's|opcache.revalidate_freq=60|opcache.revalidate_freq=0|g' /usr/local/etc/php/conf.d/opcache-recommended.ini

# Install and configure XDebug.
RUN yes | pecl install xdebug \
	&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini
