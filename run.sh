#!/bin/bash

# This shell script is an optional tool to simplify
# the installation and usage of docker platform.

# To run, make sure to add permissions to this file:
# chmod 755 sync.sh

# USAGE EXAMPLE:
# Install compose packages: ./sync.sh install
# Start services with core, nginx and mysql: ./sync.sh up core nginx mysql
# Start portainer: ./sync.sh up portainer
# Start user apps: ./sync.sh up user-apps
# Stop containers: ./sync.sh down

# prints colored text
print_style () {

    if [ "$2" == "info" ] ; then
        COLOR="96m"
    elif [ "$2" == "success" ] ; then
        COLOR="92m"
    elif [ "$2" == "warning" ] ; then
        COLOR="93m"
    elif [ "$2" == "danger" ] ; then
        COLOR="91m"
    else #default color
        COLOR="0m"
    fi

    STARTCOLOR="\e[$COLOR"
    ENDCOLOR="\e[0m"

    printf "$STARTCOLOR%b$ENDCOLOR" "$1"
}

display_options () {
    printf "Available options:\n";
    print_style "   install" "info"; printf "\t\t Installs compose packages gem on the host machine.\n"
    print_style "   up [services]" "success"; printf "\t Runs docker compose services.\n"
    print_style "   user-apps" "success"; printf "\t\t Runs users apps services.\n"
    print_style "   down" "success"; printf "\t\t\t Stops containers.\n"
    print_style "   bash" "success"; printf "\t\t\t Opens bash on the core.\n"
}

if [[ $# -eq 0 ]] ; then
    print_style "Missing arguments.\n" "danger"
    display_options
    exit 1
fi

if [ "$1" == "up" ] ; then
    print_style "Running Docker Compose\n" "info"
    shift # removing first argument
    docker-compose up -d ${@}

elif [ "$1" == "down" ]; then
    print_style "Stopping Docker Compose\n" "info"
    docker-compose stop

elif [ "$1" == "bash" ]; then
    docker-compose exec core bash

elif [ "$1" == "user-apps" ]; then
    print_style "Running Docker Compose\n" "info"
    docker-compose up -d
    print_style "Running user apps\n" "info"
    docker-compose exec core docker-compose -f /platform/storage/docker-compose.yml up -d

elif [ "$1" == "install" ]; then
    print_style "Initializing Compose packages\n" "info"
    print_style "May take a while on the first run\n" "info"
    composer --working-dir=core install

else
    print_style "Invalid arguments.\n" "danger"
    display_options
    exit 1
fi
