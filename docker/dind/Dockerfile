FROM docker:18-dind

# Установка docker-compose для ручного управления контейнерами пользователей
RUN apk add --no-cache docker-compose

VOLUME /var/lib/docker
EXPOSE 2375

ENTRYPOINT ["dockerd-entrypoint.sh"]
CMD []
