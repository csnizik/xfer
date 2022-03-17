# Script for executing PODS in a CI/CD pipeline
# Requirements:
# 	Docker
# 	Docker image named "pods"
# 	PORT : the desired listening port.

# TODO: Make PORT read from an environment variable.
# TODO: Script is dependent on docker-compose container name being "pods-container" (Need to fix?)
export PORT=85


# If docker ps shows that there is no instance of pods-container (Running or dead) 
if [ ! "$(docker ps -q -f name=pods-container)" ]; then

    # If there is only pods-container which are inactive
    if [ "$(docker ps -aq -f status=exited -f name=pods-container)" ]; then
        # Remove the container from docker instance
        docker rm pods-container
    else
    # If there is an active "pods-container"
        docker-compose down
        docker rm pods-container 
	# echo "y\n" | docker system prune
    fi
    # run your container
    # TODO: Mount logs folder
    docker build . -t pods:latest --no-cache
    docker-compose up -d

else
  # If there is a running container with name "pods-container"
  if [ "$(docker ps -aq -f status=running -f name=pods-container)" ]; then
	export PODS_CONTAINER_ID=$(docker ps -q -f name=pods-container -f status=running)
	echo $PODS_CONTAINER_ID
    docker stop pods-container
    docker rm pods-container
    docker build . -t pods:latest --no-cache
    docker-compose up -d     
  fi
	
fi
docker ps -a # shows all active/inactive containers

