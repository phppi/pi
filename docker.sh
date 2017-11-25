#!/usr/bin/env bash

# -----------------------------------------
# Init Constants
# -----------------------------------------
ACTION=$1; shift # Action is already saved, so we can shift first arg

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# -----------------------------------------
# Docker Tools Version
# -----------------------------------------
DOCKER_TOOLS_VERSION=1.19.3

# -----------------------------------------
# Config Variables
# -----------------------------------------

DOCKER_COMPOSE_FILE=$DIR/docker-compose.yml

BASE_IP=172.21.98
PROJECT_NAME=pi-compiler

# -----------------------------------------
# Docker tools checkout
# -----------------------------------------

source ./_injector.sh

# -----------------------------------------
# Build Action
# -----------------------------------------

buildAction() {
	docker build $DIR/_docker/php --build-arg DOCKER_UID=$UID --tag $PROJECT_NAME-php
}


_dockerCompose() {
	docker-compose -f $DOCKER_COMPOSE_FILE $*
}

if [[ $ACTION == "build" ]]
then
	buildAction


elif [[ $ACTION == "run" ]]; then
	_dockerCompose stop

	injector nginx root
	injector php root

	buildAction

	_dockerCompose up -d

	docker network disconnect intranet $PROJECT_NAME-nginx
	docker network connect intranet $PROJECT_NAME-nginx --ip $BASE_IP.20

	_dockerCompose logs --tail="30" --follow

elif [[ $ACTION == 'console' ]]; then
	docker exec -it $PROJECT_NAME-php php bin/pi-compiler.php $*

elif [[ $ACTION == 'test' ]]; then
	docker exec -it $PROJECT_NAME-php php vendor/bin/codecept $*

elif [[ $ACTION == 'stan' ]]; then
	docker exec -t $PROJECT_NAME-php composer dump -q
	docker exec -t $PROJECT_NAME-php php vendor/bin/phpstan analyse src "$@" -c .phpstan.neon --ansi

elif [[ $ACTION == 'stan-next' ]]; then
	docker exec -t $PROJECT_NAME-php composer dump -q
	docker exec -t $PROJECT_NAME-php php vendor/bin/phpstan analyse src "$@" -c .phpstan.next.neon --ansi

elif [[ $ACTION == 'lint' ]]; then
	docker exec -t $PROJECT_NAME-php php vendor/bin/parallel-lint --exclude vendor $*

elif [[ $ACTION == 'php' ]]; then
	docker exec -it $PROJECT_NAME-php php $*

elif [[ $ACTION == 'composer' ]]; then
	docker exec -t $PROJECT_NAME-php composer $*

elif [[ $ACTION == 'run-test' ]]; then
	while true
	do
		docker exec -it $PROJECT_NAME-php php vendor/bin/codecept run
		sleep 3
	done
else

	echo "Unknown action '$ACTION'"
fi

exit 0
