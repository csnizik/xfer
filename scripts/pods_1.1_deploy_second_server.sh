#!/bin/bash

SUPPORTED_ENVS='dev test uat stage prod'
if [ $# -ne 2 ]; then
        echo "Usage - <your_pods_tarball_file_version_number> <environment (dev/test/uat/stage/prod)>"
        exit 1
fi

# Designate the log file.
LOGFILE="/app/upload/pods_$1_update.log"

# Ensure that the required files have been uploaded to the /app/upload directory
if [[ ! -f /app/upload/pods_$1.tar.gz ]]; then
		echo "The file pods_$1.tar.gz does not exist in the /app/upload directory on this server."  2>&1|tee $LOGFILE
		echo "Please download pods_$1.tar.gz to the /app/upload directory and run this script again."  2>&1|tee $LOGFILE
		exit 1
elif [[ ! -f /app/upload/pods.$2.settings.php ]]; then
		echo "The file pods.$2.settings.php does not exist in the /app/upload directory on this server."  2>&1|tee $LOGFILE
		echo "Please download pods.$2.settings.php to the /app/upload directory and run this script again."  2>&1|tee $LOGFILE
		exit 1
fi

# If there is already a Pods installation, make a backup of the existing settings file.
if [[ -f /app/www/html/pods/web/sites/default/settings.php ]]; then
		echo "Creating a backup of the existing settings.php file." 2>&1|tee $LOGFILE
		cp -fp /app/www/html/pods/web/sites/default/settings.php /app/upload/pods.$2.settings.php.bak
fi

# Since the required files exist in the /app/upload directory, we can proceed with installing PODS on the server.
if [[ "$SUPPORTED_ENVS" == *"$2"* ]]; then

        echo "Enter Drupal maintenance mode"
        /app/www/html/pods/vendor/bin/drush sset system.maintenance_mode 1 2>&1|tee -a $LOGFILE

        echo "Removing the /app/www/pods folder..." 2>&1|tee -a $LOGFILE
        cd /app/www/html
        rm -rf /app/www/html/pods
		wait

        echo "Restoring the PODS tarball file: /app/upload/pods_$1.tar.gz" 2>&1|tee -a $LOGFILE
        tar -xzvf /app/upload/pods_$1.tar.gz
		wait

        echo "Copying the /app/upload/pods.$2.settings.php file to the settings file..." 2>&1|tee -a $LOGFILE
        cp /app/upload/pods.$2.settings.php /app/www/html/pods/web/sites/default/settings.php

        echo "Running drush to clear and rebuild cache..." 2>&1|tee -a $LOGFILE
        /app/www/html/pods/vendor/bin/drush cr 2>&1|tee -a $LOGFILE
		wait

        echo "Changing application ownership to appadmin..." 2>&1|tee -a $LOGFILE
        chown -R appadmin:appadmin /app/www/html

        echo "Turn off Drupal maintenance mode"
        /app/www/html/pods/vendor/bin/drush sset system.maintenance_mode 0 2>&1|tee -a $LOGFILE

        echo "Script completed." 2>&1|tee -a $LOGFILE
        exit 0
fi
echo "Environment argument is not valid" 2>&1|tee -a $LOGFILE
exit 1