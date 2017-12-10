<template>
    <div>
        <h2>招募列表</h2>
        <admin-recruit-table :recruits="recruitsData.data" @edit="edit" @destory="destory"></admin-recruit-table>
        <b-pagination :total-rows="recruitsData.total" v-model="recruitsData.current_page" :per-page="recruitsData.per_page" :limit="10" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                recruitsData: {},
            };
        },
        mounted() {
            this.getRecruits();
        },
        beforeRouteUpdate(to, from, next) {
            this.getRecruits(to);
            next();
        },
        methods: {
            getRecruits(route) {
                route = route || this.$router.currentRoute;
                let page = route.query.page || 1;
                axios.get('/api/admin/recruit', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.recruitsData = response.data;
                });
            },
            create() {
                this.$router.push('/admin/recruit/create');
            },
            edit(recruit) {
                this.$router.push('/admin/recruit/' + recruit.id + '/edit');
            },
            destory(recruit) {
                if (confirm('您确定要删除 #' + recruit.id + ' 招募吗？')) {
                    axios.delete('/api/admin/recruit/' + recruit.id)
                        .then(response => {
                            this.getRecruits();
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/recruit?page=' + page);
            }
        }
    }
</script>
