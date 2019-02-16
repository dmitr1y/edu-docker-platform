FROM node:9

RUN apt update
RUN apt install git -y

RUN git clone https://github.com/dmitr1y/discrete-eltech.git /usr/discrete-eltech

WORKDIR /usr/discrete-eltech
RUN npm install --silent

# Добавление данных доступа
ADD access.js /usr/discrete-eltech/access.js
WORKDIR /usr/discrete-eltech/public

# Сборка bundle
RUN ../node_modules/.bin/webpack -p
WORKDIR /usr/discrete-eltech

CMD [ "node", "index.js" ]
