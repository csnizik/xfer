# Inherit from the official Drupal 9 PHP 7.4 image.
FROM drupal:9-php7.4-apache

# Install Git (required by Composer).
RUN apt-get update && apt-get install -y git
