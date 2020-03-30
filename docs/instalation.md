# Edu modules
## Установка

**Необходимые пакеты:**
* Docker
* docker-compose
* git

## Порядок запуска
1. Установить доменное имя в `docker/nginx/confs/default.conf` и `docker/nginx/confs/evklid-trainer.conf`
2. Установить пароль от СУБД в `docker-compose.yml` для сервисов:
⋅⋅*mysql
⋅⋅*mongo
3. Сгенерировать и настроить сертификаты для nginx в `docker/nginx/confs/default.conf` и положить их в директорию 
`docker/nginx/certs/`
4. Сконфигурировать данные доступа для тренажареа Евклида в файле `docker/default_apps/evklid-trainer/access.js`
5. Запустить контейнеры в фоновом режиме с помощью команды `docker-compose up -d`
6. Произвести инициализацию сервиса `./run.sh install`
7. Сконфигурировать данные доступа в `core/common/config/params-local.php`
8. Произвести инициализацию БД `./run.sh migrate`
