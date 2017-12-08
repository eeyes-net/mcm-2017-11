<template>
    <div>
        <h2>
            竞赛列表
            <span class="float-right">
                <b-button variant="primary" @click="create">发布新竞赛</b-button>
            </span>
        </h2>
        <admin-match-table :matches="matchesData.data" @edit="edit" @destory="destory"></admin-match-table>
        <b-pagination :total-rows="matchesData.total" v-model="matchesData.current_page" :per-page="matchesData.per_page" :limit="paginationLimit" @change="changePage"></b-pagination>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                matchesData: {},
                paginationLimit: 10
            };
        },
        mounted() {
            let page = this.$router.currentRoute.query.page || 1;
            this.getMatches(page);
        },
        beforeRouteUpdate(to, from, next) {
            let page = to.query.page || 1;
            this.getMatches(page);
            next();
        },
        methods: {
            getMatches(page = 1) {
                axios.get('/api/admin/match', {
                    params: {
                        page: page
                    }
                }).then(response => {
                    this.matchesData = response.data;
                });
            },
            create() {
                this.$router.push('/admin/match/create');
            },
            edit(match) {
                this.$router.push('/admin/match/' + match.id + '/edit');
            },
            destory(match) {
                if (confirm('您确定要删除 #' + match.id + ' 《' + match.title + '》吗？')) {
                    axios.delete('/api/admin/match/' + match.id)
                        .then(response => {
                            this.getMatches(this.matchesData.current_page);
                        });
                }
            },
            changePage(page) {
                this.$router.push('/admin/match?page=' + page);
            }
        }
    }
</script>
