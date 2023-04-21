#!/bin/bash

SUPPORTED_ENVS='dev test uat stage prod'
if [ $# -ne 3 ]; then
        echo "Usage - <your_pods_tarball_file_version_number> <environment (dev/test/uat/stage/prod)> <your_drupal_password>"
        exit 1
fi

# Designate the log file.
LOGFILE="/app/upload/pods_$1_init.log"
UPGRADE_SCENARIO="false"

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
if [[ -f /app/www/html/PODS-Dev.Dev/web/sites/default/settings.php ]]; then
        UPGRADE_SCENARIO="true"
		echo "Creating a backup of the existing settings.php file." 2>&1|tee $LOGFILE
		cp -fp /app/www/html/PODS-Dev.Dev/web/sites/default/settings.php /app/upload/pods.$2.settings.php.bak
fi

# Since the required files exist in the /app/upload directory, we can proceed with installing PODS on the server.
if [[ "$SUPPORTED_ENVS" == *"$2"* ]]; then
		
        echo "Enter Drupal maintenance mode"
        /app/www/html/pods/vendor/bin/drush sset system.maintenance_mode 1 2>&1|tee -a $LOGFILE

        echo "Removing the /app/www/pods folder..." 2>&1|tee -a $LOGFILE
        cd /app/www/html
        rm -rf /app/www/html/pods 2>&1|tee -a $LOGFILE
		wait

        echo "Restoring the PODS tarball file: /app/upload/pods_$1.tar.gz" 2>&1|tee -a $LOGFILE
        tar -xzvf /app/upload/pods_$1.tar.gz 2>&1|tee -a $LOGFILE
		wait

        echo "Copying the /app/upload/pods.$2.settings.php file to the settings file..." 2>&1|tee -a $LOGFILE
        cp /app/upload/pods.$2.settings.php.bak  /app/www/html/PODS-Dev.Dev/web/sites/default/settings.php 2>&1|tee -a $LOGFILE

        if [[ "$UPGRADE_SCENARIO" == "false" ]]; then
            echo "Running drush site-install command..." 2>&1|tee -a $LOGFILE
            /app/www/html/pods/vendor/bin/drush site-install -y --account-pass=$3 --site-name="NRCS Producer Operations Data System" farm farm.modules="base" 2>&1 | tee -a $LOGFILE
    		wait

            echo "Running drush config-set for css.preprocess..." 2>&1|tee -a $LOGFILE
            /app/www/html/pods/vendor/bin/drush  -y config-set  system.performance css.preprocess 0 2>&1|tee -a $LOGFILE
            echo "Running drush config-set for js.preprocess..." 2>&1|tee -a $LOGFILE
            /app/www/html/pods/vendor/bin/drush -y config-set system.performance js.preprocess 0 2>&1|tee -a $LOGFILE

            echo "Running drush to clear and rebuild cache..." 2>&1|tee -a $LOGFILE
            /app/www/html/pods/vendor/bin/drush cr 2>&1|tee -a $LOGFILE
		    wait

            echo "Running /app/www/html/pods/vendor/bin/drush en -y structure_sync farm_map_mapbox cig_pods..." 2>&1|tee -a $LOGFILE
            /app/www/html/pods/vendor/bin/drush en -y structure_sync farm_map_mapbox cig_pods 2>&1|tee -a $LOGFILE
		    wait

            echo "Importing taxonomies..." 2>&1|tee -a $LOGFILE
            /app/www/html/pods/vendor/bin/drush import:taxonomies -y --choice=full 2>&1|tee -a $LOGFILE
		    wait

        else
            #TODO add commands for the in place upgrade
            echo "Run Drupal updates script" 2>&1|tee -a $LOGFILE
            /app/www/html/pods/vendor/bin/drush updb 2>&1|tee -a $LOGFILE
        fi
        echo "Running drush to clear and rebuild cache..." 2>&1|tee -a $LOGFILE
        /app/www/html/pods/vendor/bin/drush cr 2>&1|tee -a $LOGFILE
        wait

        echo "Changing application ownership to appadmin..." 2>&1|tee -a $LOGFILE
        chown -R appadmin:appadmin /app/www/html 2>&1|tee -a $LOGFILE

        echo "Turn off Drupal maintenance mode"
        /app/www/html/pods/vendor/bin/drush sset system.maintenance_mode 0 2>&1|tee -a $LOGFILE

        echo "Script completed." 2>&1|tee -a $LOGFILE
        exit 0
fi
echo "Environment argument is not valid" 2>&1|tee -a $LOGFILE
exit 1