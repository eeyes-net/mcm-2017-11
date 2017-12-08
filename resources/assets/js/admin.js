// Common libraries: jQuery, Bootstrap, axios...
require('./bootstrap');

// Vue.js
window.Vue = require('vue');
// Vue Router
window.VueRouter = require('vue-router').default;
Vue.use(VueRouter);

// Bootstrap Vue
require('bootstrap-vue');

// CKEditor
window.CKEDITOR_BASEPATH = '/dist/ckeditor/';
require('ckeditor');
// CKEditor Vue Component
require('vue-ckeditor2');
Vue.component('ckeditor', require('vue-ckeditor2'));

// Admin Vue Component
Vue.component('admin-index', require('./components/admin/Index.vue'));
Vue.component('admin-layouts-sidebar', require('./components/admin/layouts/Sidebar.vue'));
// Admin Post Vue Component
Vue.component('admin-post-index', require('./components/admin/post/Index.vue'));
Vue.component('admin-post-create', require('./components/admin/post/Create.vue'));
Vue.component('admin-post-edit', require('./components/admin/post/Edit.vue'));
Vue.component('admin-post-table', require('./components/admin/post/Table.vue'));
Vue.component('admin-post-editor', require('./components/admin/post/Editor.vue'));

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {path: '/admin', component: Vue.component('admin-index')},
        {path: '/admin/post', component: Vue.component('admin-post-index')},
        {path: '/admin/post/create', component: Vue.component('admin-post-create')},
        {path: '/admin/post/:post_id/edit', component: Vue.component('admin-post-edit')},
        {path: '/admin/user', component: Vue.component('admin-index')},
        {path: '/admin/team', component: Vue.component('admin-index')},
        {path: '/admin/match', component: Vue.component('admin-index')},
        {path: '/admin/recruit', component: Vue.component('admin-index')},
    ]
});

const admin = new Vue({
    router
}).$mount('#admin');