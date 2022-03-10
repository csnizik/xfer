# Inherit from the Drupal 9 image on Docker Hub.
FROM drupal:9-php7.4-apache

WORKDIR /var/www/html/PODS

COPY composer.json composer.json
COPY config/sync config/sync
COPY web/modules/custom/cig_pods web/modules/custom/cig_pods
COPY pods.sfi.dev.conf /etc/apache2/sites-available/pods.conf

RUN pwd
RUN ls /etc/apache2/sites-available/

# Install Git (Needed by composer utility)
RUN apt-get update \
  && apt-get install -y git unzip

# RUN cat /etc/apache2/apache2.conf

# This command shows that is looking for a Document Root at /var/www/html
RUN ls /etc/apache2/sites-enabled | xargs -I {} cat /etc/apache2/sites-enabled/{}

RUN ls /etc/apache2/sites-available | xargs -I {} cat /etc/apache2/sites-available/{}

RUN cat /etc/apache2/ports.conf

# PODS folder is potentially misplaced...
# RUN composer install

# Deploy code
# Run database updates

RUN a2ensite pods.conf


# TODO: Figure out why apache2-foregroud is caled here. I don't understand how there is both an "ENTRYPOINT" script and a "CMD"present. 
CMD ["apache2-foreground"]
