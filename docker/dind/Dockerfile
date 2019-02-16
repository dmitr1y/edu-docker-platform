FROM docker:dind

# Установка docker-compose для ручного управления контейнерами пользователей
RUN apk add --no-cache py-pip openssl bash
RUN pip install docker-compose

VOLUME /var/lib/docker
EXPOSE 2375

ENTRYPOINT ["dockerd-entrypoint.sh"]
CMD []
