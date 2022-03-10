# Script for executing PODS in a CI/CD pipeline
# Requirements:
# 	Docker
# 	Docker image named "pods"
export PORT=85
if [ ! "$(docker ps -q -f name=pods-container)" ]; then
    if [ "$(docker ps -aq -f status=exited -f name=pods-container)" ]; then
        # cleanup
        docker rm pods-container
    fi
    # run your container
    # TODO: Mount logs folder
    docker run -p ${PORT}:80 -d --name pods-container pods:latest
fi
docker ps -a #shows all active/inactive containers

