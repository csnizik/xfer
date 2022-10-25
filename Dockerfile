# Inherit from the official Drupal 9 PHP 7.4 image.
FROM drupal:9-php7.4-apache

# Install Git (required by Composer).
RUN apt-get update && apt-get install -y git

# Set OPcache's revalidation frequency to 0 seconds for development.
# See https://www.php.net/manual/en/opcache.configuration.php#ini.opcache.revalidate-freq
RUN sed -i 's|opcache.revalidate_freq=60|opcache.revalidate_freq=0|g' /usr/local/etc/php/conf.d/opcache-recommended.ini
