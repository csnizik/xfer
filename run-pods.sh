# Script for executing PODS in a CI/CD pipeline
# Requirements:
# 	Docker
# 	Docker image named "pods"
if ["$(docker ps -q -f name=pods-container)"]; then
	if ["$(docker ps -aq -f status=exited -f name=pods-container)"]; then
	docker rm pods-container
	fi
	# Build from the pods image, name the container "pods-container" and run in detached mode
	# Take in arguments here to grab the properly tagged one?
fi

docker run -d --name pods-container pods
docker ps -a
