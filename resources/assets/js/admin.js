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
Vue.component('ckeditor', require('vue-ckeditor2/src/Ckeditor.vue'));

// Admin Vue Component
Vue.component('admin-index', require('./components/admin/Index.vue'));
Vue.component('admin-layouts-sidebar', require('./components/admin/layouts/Sidebar.vue'));
// Admin Post Vue Component
Vue.component('admin-post-index', require('./components/admin/post/Index.vue'));
Vue.component('admin-post-create', require('./components/admin/post/Create.vue'));
Vue.component('admin-post-edit', require('./components/admin/post/Edit.vue'));
Vue.component('admin-post-table', require('./components/admin/post/Table.vue'));
Vue.component('admin-post-editor', require('./components/admin/post/Editor.vue'));
// Admin Match Vue Component
Vue.component('admin-match-index', require('./components/admin/match/Index.vue'));
Vue.component('admin-match-create', require('./components/admin/match/Create.vue'));
Vue.component('admin-match-edit', require('./components/admin/match/Edit.vue'));
Vue.component('admin-match-table', require('./components/admin/match/Table.vue'));
Vue.component('admin-match-editor', require('./components/admin/match/Editor.vue'));
Vue.component('admin-match-team-index', require('./components/admin/match/team/Index.vue'));
// Admin Match Snapshot Vue Component
Vue.component('admin-match-snapshot-index', require('./components/admin/match_snapshot/Index.vue'));
Vue.component('admin-match-snapshot-table', require('./components/admin/match_snapshot/Table.vue'));
// Admin Team Vue Component
Vue.component('admin-team-index', require('./components/admin/team/Index.vue'));
Vue.component('admin-team-table', require('./components/admin/team/Table.vue'));
// Admin Recruit Vue Component
Vue.component('admin-recruit-index', require('./components/admin/recruit/Index.vue'));
Vue.component('admin-recruit-table', require('./components/admin/recruit/Table.vue'));
Vue.component('admin-recruit-edit', require('./components/admin/recruit/Edit.vue'));
Vue.component('admin-recruit-editor', require('./components/admin/recruit/Editor.vue'));
// Admin User Vue Component
Vue.component('admin-user-index', require('./components/admin/user/Index.vue'));
Vue.component('admin-user-table', require('./components/admin/user/Table.vue'));
Vue.component('admin-user-edit', require('./components/admin/user/Edit.vue'));
Vue.component('admin-user-editor', require('./components/admin/user/Editor.vue'));
Vue.component('admin-user-team-index', require('./components/admin/user/team/Index.vue'));

const router = new VueRouter({
    mode: 'history',
    routes: [
        {path: '/admin', component: Vue.component('admin-index')},
        {path: '/admin/post', component: Vue.component('admin-post-index')},
        {path: '/admin/post/create', component: Vue.component('admin-post-create')},
        {path: '/admin/post/:post_id/edit', component: Vue.component('admin-post-edit')},
        {path: '/admin/user', component: Vue.component('admin-user-index')},
        {path: '/admin/user/:user_id/edit', component: Vue.component('admin-user-edit')},
        {path: '/admin/user/:user_id/team', component: Vue.component('admin-user-team-index')},
        {path: '/admin/team', component: Vue.component('admin-team-index')},
        {path: '/admin/team/:team_id/edit', component: Vue.component('admin-team-index')},
        {path: '/admin/team', component: Vue.component('admin-team-index')},
        {path: '/admin/match', component: Vue.component('admin-match-index')},
        {path: '/admin/match/create', component: Vue.component('admin-match-create')},
        {path: '/admin/match/:match_id/edit', component: Vue.component('admin-match-edit')},
        {path: '/admin/match/:match_id/team', component: Vue.component('admin-match-team-index')},
        {path: '/admin/match_snapshot', component: Vue.component('admin-match-snapshot-index')},
        {path: '/admin/recruit', component: Vue.component('admin-recruit-index')},
        {path: '/admin/recruit/:recruit_id/edit', component: Vue.component('admin-recruit-edit')},
    ]
});

const admin = new Vue({
    router
}).$mount('#admin');