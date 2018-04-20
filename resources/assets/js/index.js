require('./bootstrap');
require('jquery-pjax');

// Vue.js
window.Vue = require('vue');

// Bootstrap Vue
require('bootstrap-vue');

// Home Vue Component
Vue.component('layouts-error', require('./components/layouts/Error.vue'));
Vue.component('index-layouts-team-info', require('./components/index/layouts/TeamInfo.vue'));
Vue.component('index-post-modal', require('./components/index/post/Modal.vue'));
Vue.component('index-match-modal', require('./components/index/match/Modal.vue'));
Vue.component('index-recruit-modal', require('./components/index/recruit/Modal.vue'));

if (document.querySelector('.mcm-post')) {
    require('./index/post');
}
if (document.querySelector('.mcm-match')) {
    require('./index/match');
}
if (document.querySelector('.mcm-recruit')) {
    require('./index/recruit');
}
