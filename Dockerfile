# Inherit from the Drupal 9 image on Docker Hub.
FROM drupal:9-php7.4-apache


# Wipe the template project provided by Drupal Docker image
RUN rm -rf /opt/drupal/*
RUN mkdir /opt/drupal/PODS

WORKDIR /opt/drupal/PODS

COPY composer.json composer.json
COPY config/sync config/sync
# COPY web/sites/default/ web/sites/default/

COPY patches patches

# Do I need to case on different environments here?
# TODO: Change back to sfi.dev to serve? Or it's okay because Docker?
COPY pods.conf /etc/apache2/sites-available/pods.conf

RUN pwd

# RUN cat /etc/apache2/apache2.conf

# RUN ls /etc/apache2/sites-enabled | xargs -I {} cat /etc/apache2/sites-enabled/{}

# RUN ls /etc/apache2/sites-available | xargs -I {} cat /etc/apache2/sites-available/{}

# Install Git (Needed by composer utility)
RUN apt-get update \
  && apt-get install -y git unzip

# PODS folder is potentially misplaced...bole-apps.com/?#home
RUN composer install

# COPY web/modules/custom/cig_pods web/modules/custom/cig_pods
COPY web/sites/default/settings.php web/sites/default/settings.php
RUN chmod -R a+w web/sites/default/
# Update the settings.php file
# TODO: define our own settings.php file


# Deploy code
# Run database updates

RUN a2ensite pods.conf
# Ubuntu equivalent of apache2ctl enable pods.conf
RUN a2dissite 000-default.conf


# Debug prints
# RUN ls /etc/apache2/sites-available/
# RUN cat /etc/apache2/ports.conf
# RUN cat /etc/apache2/apache2.conf
# RUN ls /etc/apache2/sites-enabled | xargs -I {} cat /etc/apache2/sites-enabled/{}


# TODO: Figure out why apache2-foregroud is caled here. I don't understand how there is both an "ENTRYPOINT" script and a "CMD"present. 
CMD ["apache2-foreground"]
