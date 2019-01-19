FROM ubuntu

MAINTAINER Dmitriy Konstantinov <dmitriy@konstantinov.com.ru>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get clean && apt-get update
RUN apt install -y docker.io cron
RUN rm -rf /var/lib/apt/lists/*

ADD clean_docker.sh /clean_docker.sh
RUN chmod u+x /clean_docker.sh

ADD crontab /etc/cron.d/clean-cron
RUN chmod 0644 /etc/cron.d/clean-cron
RUN crontab /etc/cron.d/clean-cron
RUN touch /var/log/cron.log

CMD ["cron", "-f"]
