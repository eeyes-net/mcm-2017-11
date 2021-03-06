// Common libraries: jQuery, Bootstrap, axios...
require('./bootstrap');

// Vue.js
window.Vue = require('vue');

// Bootstrap Vue
require('bootstrap-vue');

// Home Vue Component
Vue.component('layouts-error', require('./components/layouts/Error.vue'));
Vue.component('index-layouts-team-info', require('./components/index/layouts/TeamInfo.vue'));
Vue.component('home-layouts-sidebar', require('./components/home/layouts/Sidebar.vue'));
Vue.component('home-user', require('./components/home/User.vue'));
Vue.component('home-team', require('./components/home/Team.vue'));
Vue.component('home-recruit', require('./components/home/Recruit.vue'));
Vue.component('home-recruit-card', require('./components/home/recruit/Card.vue'));

const home = new Vue({
    el: '#home'
});
