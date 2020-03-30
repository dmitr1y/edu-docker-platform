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
    printf "Доступные действия:\n";
    print_style "   install" "info"; printf "\t\t Инициализация сервиса.\n"
    print_style "   migrate" "info"; printf "\t\t Выполнить начальные миграции.\n"
    print_style "   up [services]" "success"; printf "\t Запуск сервисов docker-compose платформы.\n"
    print_style "   user-apps" "success"; printf "\t\t Запуск пользовательских приложений.\n"
    print_style "   down" "danger"; print_style "\t\t\t Остановка и удаление контейнеров.\n" "danger"
    print_style "   bash" "success"; printf "\t\t\t Открыть терминал контенера с ядром.\n"
    print_style "   user-sh" "success"; printf "\t\t Открыть терминал с пользовательскими приложениями.\n"
}

if [[ $# -eq 0 ]] ; then
    print_style "Missing arguments.\n" "danger"
    display_options
    exit 1
fi

if [ "$1" == "up" ] ; then
    print_style "Запуск сервисов платформы...\n" "info"
    shift # removing first argument
    docker-compose up -d ${@}

elif [ "$1" == "down" ]; then
    print_style "Остановка и удаление контейнеров платформы...\n" "danger"
    docker-compose stop

elif [ "$1" == "bash" ]; then
    print_style "Терминал контейнера с ядром\n" "danger"
    docker-compose exec core bash

elif [ "$1" == "user-sh" ]; then
    print_style "Терминал контейнера с пользовательским docker-compose\n" "info"
    docker-compose exec dind sh

elif [ "$1" == "user-apps" ]; then
    print_style "Запуск пользовательских сервисов...\n" "info"
    docker-compose exec dind docker-compose -f /storage/docker-compose.yml up -d

elif [ "$1" == "install" ]; then
    print_style "Инициализация сервиса\n" "info"
    docker-compose exec core bash install.sh

elif [ "$1" == "migrate" ]; then
    print_style "Инициализация базы данных\n" "info"
    docker-compose exec core bash migrate.sh

else
    print_style "Неверная команда.\n" "danger"
    display_options
    exit 1
fi
