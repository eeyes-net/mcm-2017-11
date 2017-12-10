<template>
    <div>
        <h2>用户列表</h2>
        <admin-user-table :users="usersData.data" @edit="edit" @destory="destory"></admin-user-table>
        <b-pagination :total-rows="usersData.total" v-model="usersData.current_page" :per-page="usersData.per_page" :limit="10" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                usersData: {},
            };
        },
        watch: {
            '$route' (to, from) {
                let page = to.query.page || 1;
                this.getUsers(page);
            }
        },
        mounted() {
            this.getUsers();
        },
        beforeRouteUpdate(to, from, next) {
            this.getUsers(to);
            next();
        },
        methods: {
            getUsers(route) {
                route = route || this.$router.currentRoute;
                let page = route.query.page || 1;
                axios.get('/api/admin/user', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.usersData = response.data;
                });
            },
            create() {
                this.$router.push('/admin/user/create');
            },
            edit(user) {
                this.$router.push('/admin/user/' + user.id + '/edit');
            },
            destory(user) {
                if (confirm('您确定要删除 #' + user.id + ' 用户吗？')) {
                    axios.delete('/api/admin/user/' + user.id)
                        .then(response => {
                            this.getUsers();
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/user?page=' + page);
            }
        }
    }
</script>
