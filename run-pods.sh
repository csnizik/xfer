# Script for executing PODS in a CI/CD pipeline
# Requirements:
# 	Docker
# 	Docker image named "pods"

# TODO: Script is dependent on docker-compose container name being "pods-container" (Need to fix?)
# TODO: script assumes we are serving on port PORT in our docker compose file

export DRUSH="vendor/bin/drush"
export ENV="sfi.dev"


# If docker ps shows that there is no instance of pods-container (Running or dead) 
if [ ! "$(docker ps -q -f name=pods-container)" ]; then

  	# echo "y\n" | docker system prune
    # run your container
    # TODO: Mount logs folder
    docker build . -t pods:latest
    docker-compose up -d

    sleep 5

    export PODS_CONTAINER_ID=$(docker ps -q -f name=pods-container -f status=running)

    echo "docker exec $PODS_CONTAINER_ID vendor/bin/drush cr"
    docker exec $PODS_CONTAINER_ID vendor/bin/drush cr     
 
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en field_layout     
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en field_ui 
 
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en cig_pods     



# If there is a running container with name "pods-container"
else
  if [ "$(docker ps -aq -f status=running -f name=pods-container)" ]; then
    export PODS_CONTAINER_ID=$(docker ps -q -f name=pods-container -f status=running)
    # Use drush commands to tell the database to get rid of config objects
    # it is holding for cig_pods/field_layout/field_ui
    if ["$(docker exec $PODS_CONTAINER_ID vendor/bin/drush pm:list | grep PODS)"];

    then
      docker exec $PODS_CONTAINER_ID vendor/bin/drush pmu cig_pods  
      docker exec $PODS_CONTAINER_ID vendor/bin/drush pmu field_ui 
      docker exec $PODS_CONTAINER_ID vendor/bin/drush pmu field_layout     
    fi
 
    
    docker stop $PODS_CONTAINER_ID
    docker rm $PODS_CONTAINER_ID

      # Update code, call vendor/bin/drush 
      # Uninstall cig_pods module
      # Remove custom module code from web/modules/custom/cig_pods
      # Copy in new code to the docker container at the web/modules/custom/cig_pods folder
      # Reinstall the module
      # Call vendor/bin/drush updb...
    docker build . -t pods:latest

    # Change the settings.php file after the docker build? 

    docker-compose up -d

    # Find the enw container id of the pods-container
    sleep 5

    # Maybe need to wait for containers to spin up?
    # Enable the field_ui, field_layout module and the cig_pods module
    # field_layout and field_ui come bundled with farmOS so we don't list them as explicit dependencies
    export PODS_CONTAINER_ID=$(docker ps -q -f name=pods-container -f status=running)
    docker exec $PODS_CONTAINER_ID vendor/bin/drush cr     
 
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en field_layout     
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en field_ui 
 
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en cig_pods     

    # docker exec $PODS_CONTAINER_ID vendor/bin/drush pm-uninstall cig_pods     
      # Clears your data.
    # docker exec $PODS_CONTAINER_ID vendor/bin/drush en structure_sync
    # docker exec $PODS_CONTAINER_ID vendor/bin/drush import-taxonomies
      # Looks in the config/sync directory for the structure_sync.data file
      # Reads in all these values.
      # As long as the taxonomies data     

    # TODO enable structure_sync module and import the data present in structure_sync.yml under config/sync
  fi
	
fi
docker ps -a # shows all active/inactive containers

