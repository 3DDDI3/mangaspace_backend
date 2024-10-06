import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.defaults.withXSRFToken = true;


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

// window.Echo.channel('test_channel').listen('TestEvent', e => {

// });

let domain = "http://api.mangaspace.ru:83";

$("input[name='login']").on("click", function (e) {
    e.preventDefault();
    window.axios.get(`${domain}/api/auth/csrf-cookie`).then(response => {
        const data = {
            name: 'admin',
            password: '1234',
        };
        window.axios.post(`${domain}/v1.0/auth/login`, data);
    });
});

$("input[name='logout']").on("click", function (e) {
    e.preventDefault();
    window.axios.post(`${domain}/v1.0/auth/logout`).then(response => {
        console.log(response);
    });
});

window.axios.get(`${domain}/v1.0/auth/check`)
    .then(response => {
        window.Echo.private(`chat.${response.data.user.id}`)
            .listen('WS\\Scraper\\ParseEvent', (e) => {
                alert(e.message);
            });
    });

$("input[name='parse']").on("click", function () {
    window.axios.get(`${domain}/v1.0/scraper/parse`)
        .then(response => {

        });
});