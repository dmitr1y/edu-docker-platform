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
5. Выполнить команду `docker-compose up -d` для запуска всех сервисов в фоновом режиме
6. Зайти в сервеис **core** через `docker-compose exec core bash` и произвести инициализацию
```bash
composer install
./init
./yii migrate/up
./yii migrate-queue
./yii migrate-user
./yii migrate/up --migrationPath=@yii/rbac/migrations
./yii rbac/init
cd storage && docker-compose up -d
```
7. Сконфигурировать данные доступа в `core/common/config/params-local.php`
