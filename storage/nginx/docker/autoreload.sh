#!/bin/bash
echo "[AUTORELOAD NGINX] starting Nginx">&2
/usr/sbin/nginx -g "daemon on;"

echo "[AUTORELOAD NGINX] starting Nginx conf watcher">&2
DIR="/etc/nginx/conf.d"
inotifywait -m -r -e moved_to -e create -e delete "$DIR" --format "%f" | while read f
do
    echo "[AUTORELOAD NGINX] reloading Nginx configuration">&2
     /usr/sbin/nginx -t
    if [ $? -eq 0 ]
    then
        /usr/sbin/nginx -s reload
    else
        echo "[AUTORELOAD NGINX][ERROR] BAD CONFIGURATION">&2
    fi
done
