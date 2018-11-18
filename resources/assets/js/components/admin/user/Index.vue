<template>
    <div>
        <h2>用户列表
            <div class="float-sm-right mt-2 mt-sm-0">
                <b-form inline @submit.prevent="search(q)">
                    <b-input class="mr-sm-2 mb-2 mb-sm-0" placeholder="智能搜索" v-model="q" />
                    <b-button variant="primary" type="submit">搜索</b-button>
                </b-form>
            </div>
        </h2>
        <admin-user-table :users="usersData.data" @edit="edit" @destory="destory"></admin-user-table>
        <b-pagination :total-rows="usersData.total" v-model="usersData.current_page" :per-page="usersData.per_page" :limit="10" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                usersData: {},
                q: ''
            };
        },
        watch: {
            '$route'(to, from) {
                this.getUsers();
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
                let q = route.query.q || '';
                let page = route.query.page || 1;
                axios.get('/api/admin/user', {
                    params: {
                        q: q,
                        page: page
                    }
                }).then(response => {
                    this.usersData = response.data;
                });
            },
            search(q) {
                this.$router.push({
                    path: '/admin/user',
                    query: {
                        q: q
                    }
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
                let route = route || this.$router.currentRoute;
                let q = route.query.q || '';
                this.$router.push({
                    path: '/admin/user',
                    query: {
                        q: q,
                        page: page,
                    }
                });
            }
        }
    };
</script>
