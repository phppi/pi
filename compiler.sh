#!/usr/bin/env bash

docker build _docker --tag pi-app -q

docker run -it --volume=`pwd`:/var/docker pi-app php index.php $*
