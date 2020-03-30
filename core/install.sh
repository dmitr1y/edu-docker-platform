#!/bin/bash
# Выполнение инициализирующих миграций
composer install --no-dev
php init --env=Production --overwrite=No
cd ../storage && docker-compose up -d && cd ./..
