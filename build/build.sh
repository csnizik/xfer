#!/bin/bash

# Get the script path and the parent directory path.
SCRIPTPATH="$( cd "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
PARENTPATH="$(dirname $SCRIPTPATH)"

# Temporarily copy composer.json and patches to this directory.
cp "${SCRIPTPATH}/../composer.json" ./
cp -r "${SCRIPTPATH}/../patches" ./

# Build the Docker container.
sudo docker build -t pods-build . --no-cache

# Run the Docker container with custom modules mounted as a volume.
sudo docker run --rm -v "${PARENTPATH}/web/modules/custom:/pods/web/modules/custom" -d --name pods-build pods-build

# Generate a tarball of the built codebase and copy that out of the container.
sudo docker exec pods-build tar -czvf /tmp/codebase.tar.gz /pods
sudo docker cp pods-build:/tmp/codebase.tar.gz ./
sudo chown ${UID}:${UID} codebase.tar.gz

# Cleanup (stop the container and remove the temporary composer.json).
sudo docker stop pods-build
rm "${SCRIPTPATH}/composer.json"
rm -r "${SCRIPTPATH}/patches"
