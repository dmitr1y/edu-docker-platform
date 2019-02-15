FROM node:9

RUN apt update

RUN apt install git -y

RUN git clone https://github.com/dmitr1y/discrete-eltech.git /usr/discrete-eltech

WORKDIR /usr/discrete-eltech

RUN npm install --silent


CMD [ "node", "index.js" ]
