# Script for executing PODS in a CI/CD pipeline
# Requirements:
# 	Docker
# 	Docker image named "pods"

# TODO: Script is dependent on docker-compose container name being "pods-container" (Need to fix?)
# TODO: script assumes we are serving on port PORT in our docker compose file

export DRUSH="vendor/bin/drush"


# If docker ps shows that there is no instance of pods-container (Running or dead) 
if [ ! "$(docker ps -q -f name=pods-container)" ]; then

    # If there is only pods-container which are inactive
    if [ "$(docker ps -aq -f status=exited -f name=pods-container)" ]; then
        # Remove the container from docker instance
        docker rm pods-container
    else
    # Some kind of "update-in-place" method here.
    # If there is an active "pods-container"
        docker-compose down
        docker rm pods-container 
	# echo "y\n" | docker system prune
    fi
    # run your container
    # TODO: Mount logs folder
    docker build . -t pods:latest - -no-cache
    docker-compose up -d

else
  # If there is a running container with name "pods-container"
  export DO_PURGE=1
  if [ "$(docker ps -aq -f status=running -f name=pods-container)" ]; then
    export PODS_CONTAINER_ID=$(docker ps -q -f name=pods-container -f status=running)
    if [$DO_PURGE]; then
      docker stop $PODS_CONTAINER_ID
      docker rm $PODS_CONTAINER_ID
    # If we are not purging
    else
      # Ideas?
            docker stop $PODS_CONTAINER_ID
      # Update code, call vendor/bin/drush 
      # Uninstall cig_pods module
      # Remove custom module code from web/modules/custom/cig_pods
      # Copy in new code to the docker container at the web/modules/custom/cig_pods folder
      # Reinstall the module
      # Call vendor/bin/drush updb...

    fi
    docker build . -t pods:latest --no-cache

    # Change the settings.php file after the docker build? 

    docker-compose up -d

    # Find the enw container id of the pods-container
    export PODS_CONTAINER_ID=$(docker ps -q -f name=pods-container -f status=running)

    # Maybe need to wait for containers to spin up?
    sleep 5
    # Enable the field_ui, field_layout module and the cig_pods module
    # field_layout and field_ui come bundled with farmOS so we don't list them as explicit dependencies
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en field_layout     
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en field_ui     
    docker exec $PODS_CONTAINER_ID vendor/bin/drush en cig_pods     
  fi
	
fi
docker ps -a # shows all active/inactive containers

