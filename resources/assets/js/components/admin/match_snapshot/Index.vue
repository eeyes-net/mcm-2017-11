<template>
    <div>
        <h2>
            竞赛快照列表
        </h2>
        <admin-match-snapshot-table :match-snapshots="data.data" @export="exportUser"></admin-match-snapshot-table>
        <b-pagination :total-rows="data.total" v-model="data.current_page" :per-page="data.per_page" :limit="10" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                data: {}
            };
        },
        mounted() {
            this.get();
        },
        beforeRouteUpdate(to, from, next) {
            this.get(to);
            next();
        },
        methods: {
            get(route) {
                route = route || this.$router.currentRoute;
                let page = route.query.page || 1;
                axios.get('/api/admin/match/snapshot', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.data = response.data;
                });
            },
            changePage(page) {
                this.$router.push('/admin/match?page=' + page);
            },
            exportUser(matchSnapshot) {
                window.open('/api/admin/match/snapshot/' + matchSnapshot.id + '/user/export');
            }
        }
    };
</script>
