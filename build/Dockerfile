FROM drupal:9-php7.4-apache

# Install Git (Needed by composer utility)
RUN apt-get update \
  && apt-get install -y git unzip

# Make a directory to build our codebase in.
RUN mkdir /pods

# Copy the composer.json.
COPY composer.json /pods/composer.json

# Run composer install.
WORKDIR /pods
RUN composer install
