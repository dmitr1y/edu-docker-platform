#!/usr/bin/env bash

dt=$(date '+%d/%m/%Y %H:%M:%S');

echo '['"$dt"'] Starting cleanup....'

echo '['"$dt"'] Removing unused containers'
docker container prune -f

echo '['"$dt"'] Removing unused images'
docker image prune -f

echo '['"$dt"'] Removing unused networks'
docker network prune -f

#todo debug only
echo '['"$dt"'] Removing unused volumes'
docker volume prune -f

printenv
