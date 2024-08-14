FROM node:21

WORKDIR /var/www/laravel

EXPOSE ${PORT}

RUN npm install --save-dev laravel-echo pusher-js

CMD npm run dev