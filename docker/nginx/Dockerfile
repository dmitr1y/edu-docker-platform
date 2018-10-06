FROM nginx:latest
MAINTAINER Dmitriy Konstantinov <dmitriy@konstantinov.com.ru>
RUN apt update
RUN apt install -y inotify-tools curl
#RUN apt install -y dnsmasq

ADD autoreload.sh /autoreload.sh
RUN chmod +x /autoreload.sh

EXPOSE 80 443

CMD ["/autoreload.sh"]
