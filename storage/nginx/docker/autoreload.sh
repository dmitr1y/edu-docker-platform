#!/bin/bash
echo "--- starting Nginx ---">&2
/usr/sbin/nginx -g "daemon on;"

echo "--- starting Nginx conf watcher ---">&2
DIR="/etc/nginx/conf.d"
inotifywait -m -r -e moved_to -e create -e delete "$DIR" --format "%f" | while read f
do
    echo " --- reloading Nginx configuration  ---">&2
     /usr/sbin/nginx -t
    if [ $? -eq 0 ]
    then
        /usr/sbin/nginx -s reload
    else
        echo "[ERROR] BAD CONFIGURATION ">&2
    fi
done
