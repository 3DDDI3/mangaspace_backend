import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

// window.Echo.channel('test_channel').listen('TestEvent', e => {

// });

$("input[name='login']").on("click", function (e) {
    e.preventDefault();
    window.axios.defaults.withCredential = true;
    window.axios.defaults.withXSRFToken = true;
    window.axios.get('http://api.mangaspace.ru:8082/api/auth/csrf-cookie',).then(response => {
        const data = {
            name: 'admin',
            password: '1234',
        };
        window.axios.post('http://api.mangaspace.ru:8082/v1.0/auth/login', data);
    });
});



$("input[name='logout']").on("click", function (e) {
    e.preventDefault();
    window.axios.defaults.withCredential = true;
    window.axios.defaults.withXSRFToken = true;
    window.axios.post('http://api.mangaspace.ru:8082/v1.0/auth/logout').then(response => {
        console.log(response);
    });
});

window.Echo.private('chat.1').listen('TestEvent', (e) => {
    alert(e.message.message);
});